<?php

    namespace Stui\StatuspageCLI\Commands\Maintenance;

    use Stui\StatuspageIo\Enums\IncidentStatus;
    use Stui\StatuspageIo\Exceptions\StatuspageException;

    class DeleteMaintenanceCommand extends \CLIFramework\Command
    {
        public function brief(): string
        {
            return "Deletes an existing incident";
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

            $incidentID = $this->getOptions()->get('incident');


            if (is_null($incidentID)) {
                echo 'No incident ID specified';
                exit(1);
            }

            $incident = new \Stui\StatuspageIo\Service\Incident($client);
            $incident->setId($incidentID);

            try {
                $incident->deleteIncident();
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
        }
    }
