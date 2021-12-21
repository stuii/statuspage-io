<?php

    declare(strict_types=1);

    namespace Stui\StatuspageIo\Enums;

    class IncidentStatus extends Enum
    {
        public const INVESTIGATING = 'investigating';
        public const IDENTIFIED = 'identified';
        public const MONITORING = 'monitoring';
        public const RESOLVED = 'resolved';
        public const SCHEDULED = 'scheduled';
        public const IN_PROGRESS = 'in_progress';
        public const VERIFYING = 'verifying';
        public const COMPLETED = 'completed';

        public static function isScheduledStatus(IncidentStatus $status): bool
        {
            return in_array($status->value, [
                self::SCHEDULED,
                self::IN_PROGRESS,
                self::VERIFYING,
                self::COMPLETED
            ]);
        }

        public static function isRealtimeStatus(IncidentStatus $status): bool
        {
            return in_array($status->value, [
                self::INVESTIGATING,
                self::IDENTIFIED,
                self::MONITORING,
                self::RESOLVED
            ]);
        }
    }
