<?php

    declare(strict_types=1);

    namespace Stui\StatuspageIo\Enums;

    class ComponentStatus extends Enum
    {
        public const OPERATIONAL = 'operational';
        public const UNDER_MAINTENANCE = 'under_maintenance';
        public const DEGRADED_PERFORMANCE = 'degraded_performance';
        public const PARTIAL_OUTAGE = 'partial_outage';
        public const MAJOR_OUTAGE = 'major_outage';
        public const NONE = '';
    }
