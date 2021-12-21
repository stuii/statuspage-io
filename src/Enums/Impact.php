<?php

    declare(strict_types=1);

    namespace Stui\StatuspageIo\Enums;

    class Impact extends Enum
    {
        public const CRITICAL = 'critical';
        public const MAINTENANCE = 'maintenance';
        public const NONE = 'none';
        public const MAJOR = 'major';
    }
