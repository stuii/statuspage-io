<?php

declare(strict_types=1);

namespace Stui\StatuspageIo\Service;

use DateTime;
use Stui\StatuspageIo\Enums\ComponentStatus;

class Component
{
    private ?string $id = null;
    private ?Page $page = null;
    private ?string $groupId = null;
    private ?DateTime $cratedAt = null;
    private ?DateTime $updatedAt = null;
    private ?bool $group = null;
    private ?string $name = null;
    private ?string $description = null;
    private ?int $position = null;
    private ?ComponentStatus $status = null;
    private ?bool $showcase = null;
    private ?bool $onlyShowIfDegraded = null;
    private ?string $automationEmail = null;
    private ?DateTime $startDate = null;

    public function __construct(?string $id = null, ?array $hydrationArray = null)
    {
        if(!is_null($id)){
            $this->setId($id);
        }
        if(!is_null($hydrationArray)){
            $this->hydrateFromResponse($hydrationArray);
        }
    }

    public function hydrateFromResponse($data)
    {
        $this->setId($data['id']);
        //$this->setPage();
        // TODO
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Page|null
     */
    public function getPage(): ?Page
    {
        return $this->page;
    }

    /**
     * @param Page|null $page
     */
    public function setPage(?Page $page): void
    {
        $this->page = $page;
    }

    /**
     * @return string|null
     */
    public function getGroupId(): ?string
    {
        return $this->groupId;
    }

    /**
     * @param string|null $groupId
     */
    public function setGroupId(?string $groupId): void
    {
        $this->groupId = $groupId;
    }

    /**
     * @return DateTime|null
     */
    public function getCratedAt(): ?DateTime
    {
        return $this->cratedAt;
    }

    /**
     * @param DateTime|null $cratedAt
     */
    public function setCratedAt(?DateTime $cratedAt): void
    {
        $this->cratedAt = $cratedAt;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime|null $updatedAt
     */
    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return bool|null
     */
    public function getGroup(): ?bool
    {
        return $this->group;
    }

    /**
     * @param bool|null $group
     */
    public function setGroup(?bool $group): void
    {
        $this->group = $group;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int|null
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @param int|null $position
     */
    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }

    /**
     * @return ComponentStatus|null
     */
    public function getStatus(): ?ComponentStatus
    {
        return $this->status;
    }

    /**
     * @param ComponentStatus|null $status
     */
    public function setStatus(?ComponentStatus $status): void
    {
        $this->status = $status;
    }

    /**
     * @return bool|null
     */
    public function getShowcase(): ?bool
    {
        return $this->showcase;
    }

    /**
     * @param bool|null $showcase
     */
    public function setShowcase(?bool $showcase): void
    {
        $this->showcase = $showcase;
    }

    /**
     * @return bool|null
     */
    public function getOnlyShowIfDegraded(): ?bool
    {
        return $this->onlyShowIfDegraded;
    }

    /**
     * @param bool|null $onlyShowIfDegraded
     */
    public function setOnlyShowIfDegraded(?bool $onlyShowIfDegraded): void
    {
        $this->onlyShowIfDegraded = $onlyShowIfDegraded;
    }

    /**
     * @return string|null
     */
    public function getAutomationEmail(): ?string
    {
        return $this->automationEmail;
    }

    /**
     * @param string|null $automationEmail
     */
    public function setAutomationEmail(?string $automationEmail): void
    {
        $this->automationEmail = $automationEmail;
    }

    /**
     * @return DateTime|null
     */
    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    /**
     * @param DateTime|null $startDate
     */
    public function setStartDate(?DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

}
