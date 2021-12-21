<?php

declare(strict_types=1);

namespace Stui\StatuspageIo\Service;

use Stui\StatuspageIo\Enums\IncidentStatus;

class AffectedComponent implements \JsonSerializable
{
    public function __construct(
        private string         $code,
        private string         $name,
        private IncidentStatus $oldStatus,
        private IncidentStatus $newStatus
    )
    {
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return IncidentStatus
     */
    public function getOldStatus(): IncidentStatus
    {
        return $this->oldStatus;
    }

    /**
     * @return IncidentStatus
     */
    public function getNewStatus(): IncidentStatus
    {
        return $this->newStatus;
    }

    public function jsonSerialize(): array
    {
        return [
            'code' => $this->getCode(),
            'name' => $this->getName(),
            'old_status' => $this->getOldStatus(),
            'new_status' => $this->getNewStatus()
        ];
    }
}