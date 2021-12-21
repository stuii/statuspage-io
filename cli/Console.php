<?php

    namespace Stui\StatuspageCLI;

    use CLIFramework\Application;
    use Stui\StatuspageCLI\Commands\IncidentCommand;
    use Stui\StatuspageCLI\Commands\MaintenanceCommand;

    class Console extends Application
    {
        public function init()
        {
            parent::init(); // Standard commands
            $this->command('incident', IncidentCommand::class); // Custom commands
            $this->command('maintenance', MaintenanceCommand::class); // Custom commands
        }
    }
