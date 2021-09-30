<?php

declare(strict_types=1);

namespace Stui\StatuspageIo\Service;

use DateTime;
use Stui\StatuspageIo\Enums\IncidentStatus;

class IncidentUpdate
{
    private ?string $id = null;
    private ?Incident $incident = null;
    private array $affectedComponents = [];
    private ?string $body = null;
    private ?DateTime $createdAt = null;
    private ?string $customTweet = null;
    private ?bool $deliverNotifications = null;
    private ?DateTime $displayAt = null;
    private ?IncidentStatus $status = null;
    private ?string $tweetId = null;
    private ?DateTime $twitterUpdatedAt = null;
    private ?DateTime $updatedAt = null;
    private ?bool $wantsTwitterUpdate = null;
}