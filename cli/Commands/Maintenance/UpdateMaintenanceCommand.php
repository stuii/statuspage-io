<?php

    namespace Stui\StatuspageCLI\Commands\Maintenance;

    use Stui\StatuspageIo\Enums\ComponentStatus;
    use Stui\StatuspageIo\Enums\Impact;
    use Stui\StatuspageIo\Enums\IncidentStatus;
    use Stui\StatuspageIo\Exceptions\StatuspageException;

    class UpdateMaintenanceCommand extends \CLIFramework\Command
    {
        public function brief(): string
        {
            return "Updates an existing incident";
        }
        public function execute()
        {
            $apiKey = $this->getOptions()->get('api-key');
            $pageId = $this->getOptions()->get('page-id');

            if (is_null($apiKey)) {
                echo 'No API Key specified';
                exit(1);
            }
            if (is_null($pageId)) {
                echo 'No Page ID specified';
                exit(1);
            }

            $client = new \Stui\StatuspageIo\Client(
                apiKey: $apiKey, pageId: $pageId
            );

            $description = $this->getOptions()->get('description');
            $incidentStatus = $this->getOptions()->get('incident-status');
            $incidentID = $this->getOptions()->get('incident');


            if (is_null($incidentID)) {
                echo 'No incident ID specified';
                exit(1);
            }

            if (is_null($incidentStatus)) {
                echo 'No incident status specified';
                exit(1);
            } else {
                try {
                    $incidentStatus = new IncidentStatus($incidentStatus);
                } catch (StatuspageException){
                    echo 'Invalid incident status specified';
                    exit(1);
                }
                if(!IncidentStatus::isScheduledStatus($incidentStatus)){
                    echo 'Invalid incident status specified';
                    exit(1);
                }
            }



            $incident = new \Stui\StatuspageIo\Service\Incident($client);
            $incident->setId($incidentID);

            try {
                $incident->getIncident();
            } catch(StatuspageException){
                echo 'Could not fetch incident';
                exit(1);
            }

            $incident->setIncidentStatus($incidentStatus);

            if(!is_null($description)){
                $incident->setBody($description);
            }

            try {
                $incident->updateIncident();
                exit(0);
            } catch (\Stui\StatuspageIo\Exceptions\StatuspageException $e){
                echo $e->getMessage();
                exit(1);
            }
        }

        public function options($opts): void
        {
            $opts->add('i|incident:', 'Specify the incident ID')
                ->isa('string');

            $opts->add('s|incident-status:', 'Specify the incident impact override')
                ->isa('string');

            $opts->add('d|description?', 'Specify a description for the incident')
                ->isa('string');




            $opts->add('a|api-key:', 'Specify the API Key')
                ->isa('string');

            $opts->add('p|page-id:', 'Specify the Page-ID')
                ->isa('string');
        }
    }
