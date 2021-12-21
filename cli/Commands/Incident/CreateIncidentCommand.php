<?php

    namespace Stui\StatuspageCLI\Commands\Incident;

    use Stui\StatuspageIo\Enums\ComponentStatus;
    use Stui\StatuspageIo\Enums\Impact;
    use Stui\StatuspageIo\Enums\IncidentStatus;
    use Stui\StatuspageIo\Exceptions\StatuspageException;

    class CreateIncidentCommand extends \CLIFramework\Command
    {
        public function brief(): string
        {
            return "Crates a new incident"; //Short description
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
            $incidentStatus = $this->getOptions()->get('incident-status');
            $impact = $this->getOptions()->get('impact');
            $componentIds = $this->getOptions()->get('component');
            $componentStatuses = $this->getOptions()->get('component-status');

            $components = [];

            if (is_null($title)) {
                echo 'No title specified';
                exit(1);
            }
            if (is_null($description)) {
                echo 'No description specified';
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
                if(!IncidentStatus::isRealtimeStatus($incidentStatus)){
                    echo 'Invalid incident status specified';
                    exit(1);
                }
            }

            if (!is_null($impact)) {
                try{
                    $impact = new Impact($impact);
                } catch(StatuspageException) {
                    echo 'Invalid impact specified';
                    exit(1);
                }
            }

            if (!is_null($componentIds)) {
                foreach ($componentIds as $index => $componentId) {
                    if (!isset($componentStatuses[$index])) {
                        echo 'Component has no matching status';
                        exit(1);
                    }
                    try {
                        $componentStatus = new ComponentStatus($componentStatuses[$index]);
                    } catch(StatuspageException){
                        echo 'Invalid component status specified';
                        exit(1);
                    }
                    $components[$componentId] = $componentStatus;
                }
            }


            $incident = new \Stui\StatuspageIo\Service\Incident($client, $title);
            $incident->setBody($description);

            foreach ($components as $componentId => $componentStatus) {
                $incident->addComponentId($componentId);
            }
            $incident->addComponent(
                componentId:     $componentId,
                componentStatus: $componentStatus
            );

            if (!is_null($impact)) {
                $incident->setImpactOverride($impact);
            }

            $incident->setIncidentStatus($incidentStatus);

            try {
                $incident->createNew();
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

            $opts->add('cs|component-status+', 'Specify the status of each affected component')
                ->isa('string');

            $opts->add('i|impact?', 'Specify the incident impact override')
                ->isa('string');

            $opts->add('is|incident-status:', 'Specify the incident status')
                ->isa('string');

            $opts->add('t|title:', 'Specify the title for the incident')
                ->isa('string');

            $opts->add('d|description:', 'Specify a description for the incident')
                ->isa('string');
        }

    }
