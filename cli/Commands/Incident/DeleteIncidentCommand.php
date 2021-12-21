<?php

    namespace Stui\StatuspageCLI\Commands\Incident;

    use Stui\StatuspageIo\Enums\IncidentStatus;
    use Stui\StatuspageIo\Exceptions\StatuspageException;

    class DeleteIncidentCommand extends \CLIFramework\Command
    {
        public function brief(): string
        {
            return "Deletes an existing incident";
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
