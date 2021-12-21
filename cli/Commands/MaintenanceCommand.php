<?php

    namespace Stui\StatuspageCLI\Commands;

    use CLIFramework\Command;
    use Stui\StatuspageCLI\Commands\Maintenance\DeleteMaintenanceCommand;
    use Stui\StatuspageCLI\Commands\Maintenance\ScheduleMaintenanceCommand;
    use Stui\StatuspageCLI\Commands\Maintenance\UpdateMaintenanceCommand;

    class MaintenanceCommand extends Command
    {
        public function init()
        {
            parent::init();
            $this->command('update', UpdateMaintenanceCommand::class);
            $this->command('delete', DeleteMaintenanceCommand::class);
            $this->command('schedule', ScheduleMaintenanceCommand::class);
        }
        public function brief(): string
        {
            return "Creates a new or manipulates an existing maintenance";
        }


    }
