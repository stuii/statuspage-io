<?php

    namespace Stui\StatuspageCLI\Commands\Maintenance;

    use Stui\StatuspageIo\Enums\ComponentStatus;
    use Stui\StatuspageIo\Enums\Impact;
    use Stui\StatuspageIo\Enums\IncidentStatus;
    use Stui\StatuspageIo\Exceptions\StatuspageException;

    class ScheduleMaintenanceCommand extends \CLIFramework\Command
    {
        public function brief(): string
        {
            return "Schedules a new maintenance";
        }
        public function execute()
        {
            $dotenv = \Dotenv\Dotenv::createImmutable('../');
            try {
                $dotenv->load();
            } catch (\Dotenv\Exception\ExceptionInterface) {
                echo 'No Dotenv File found.';
                exit(1);
            }

            $client = new \Stui\StatuspageIo\Client(
                apiKey: $_ENV['API_KEY'], pageId: $_ENV['PAGE_ID']
            );

            $title = $this->getOptions()->get('title');
            $description = $this->getOptions()->get('description');
            $componentIds = $this->getOptions()->get('component');
            $incidentStatus = $this->getOptions()->get('incident-status');


            $from = $this->getOptions()->get('start');
            $to = $this->getOptions()->get('end');

            if (is_null($title)) {
                echo 'No title specified';
                exit(1);
            }
            if (is_null($description)) {
                echo 'No description specified';
                exit(1);
            }
            if (is_null($from)) {
                echo 'No start specified';
                exit(1);
            } else {
                try{
                    $from = new \DateTime($from);
                } catch(\Exception){
                    echo 'Invalid date for start specified';
                    exit(1);
                }
            }
            if (is_null($to)) {
                echo 'No end specified';
                exit(1);
            } else {
                try{
                    $to = new \DateTime($to);
                } catch(\Exception $e){
                    var_dump($e->getMessage());
                    echo 'Invalid date for end specified';
                    exit(1);
                }
            }

            if (!is_null($incidentStatus)) {
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

            $incident = new \Stui\StatuspageIo\Service\Incident($client, $title);
            $incident->setBody($description);

            foreach ($componentIds as $componentId) {
                $incident->addComponentId($componentId);
            }
            if(!is_null($incidentStatus)){
                $incident->setIncidentStatus($incidentStatus);
            }

            try {
                $incident->scheduleMaintenance(
                    from: $from,
                    to: $to
                );
            } catch (\Stui\StatuspageIo\Exceptions\StatuspageException $e){
                echo $e->getMessage();
                exit(1);
            }
            $this->getLogger()->writeln($incident->getId());
            exit(0);
        }

        public function options($opts): void
        {
            $opts->add('c|component+', 'Specify the ID of an affected component')
                ->isa('string');

            $opts->add('t|title:', 'Specify the title for the incident')
                ->isa('string');

            $opts->add('d|description:', 'Specify a description for the incident')
                ->isa('string');

            $opts->add('s|start:', 'Specify a starting date and time')
                ->isa('string');

            $opts->add('e|end:', 'Specify a date and time, the incident should be over by')
                ->isa('string');

            $opts->add('x|incident-status?', 'Specify the incident status override')
                ->isa('string');
        }

    }
