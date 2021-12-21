<?php

    namespace Stui\StatuspageCLI\Commands;

    use CLIFramework\Command;
    use Stui\StatuspageCLI\Commands\Incident\CreateIncidentCommand;
    use Stui\StatuspageCLI\Commands\Incident\DeleteIncidentCommand;
    use Stui\StatuspageCLI\Commands\Incident\ScheduleIncidentCommand;
    use Stui\StatuspageCLI\Commands\Incident\UpdateIncidentCommand;

    class IncidentCommand extends Command
    {
        public function init()
        {
            parent::init();
            $this->command('create', CreateIncidentCommand::class);
            $this->command('update', UpdateIncidentCommand::class);
            $this->command('delete', DeleteIncidentCommand::class);
            $this->command('schedule', ScheduleIncidentCommand::class);
        }
        public function brief(): string
        {
            return "Creates a new or manipulates an existing incident";
        }


    }
