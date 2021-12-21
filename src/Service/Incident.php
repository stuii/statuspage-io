<?php

    declare(strict_types=1);

    namespace Stui\StatuspageIo\Service;

    use DateTime;
    use JetBrains\PhpStorm\ArrayShape;
    use Stui\StatuspageIo\Client;
    use Stui\StatuspageIo\Enums\ComponentStatus;
    use Stui\StatuspageIo\Enums\HttpMethod;
    use Stui\StatuspageIo\Enums\Impact;
    use Stui\StatuspageIo\Enums\IncidentStatus;
    use Stui\StatuspageIo\Exceptions\AuthenticationException;
    use Stui\StatuspageIo\Exceptions\InvalidValueException;
    use Stui\StatuspageIo\Exceptions\MissingFieldException;
    use Stui\StatuspageIo\Exceptions\ResponseException;

    class Incident
    {
        private Client $client;

        private ?string $id = null;

        private array $components = [];
        private ?bool $autoTransitionDeliverNotificationsAtEnd = null;
        private ?bool $autoTransitionDeliverNotificationsAtStart = null;
        private ?bool $autoTransitionToMaintenanceState = null;
        private ?bool $autoTransitionToOperationalState = null;
        private ?bool $autoTweetAtBeginning = null;
        private ?bool $autoTweetOnCompletion = null;
        private ?bool $autoTweetOnCreation = null;
        private ?bool $autoTweetOneHourBefore = null;
        private ?DateTime $backfillDate = null;
        private ?bool $backfilled = null;
        private ?string $body = null;
        private ?DateTime $createdAt = null;
        private array $componentIds = [];
        private bool $deliverNotifications = true;
        private ?IncidentStatus $incidentStatus = null;
        private ?Impact $impact = null;
        private ?Impact $impactOverride = null;
        private array $incidentUpdates = [];
        //private $metadata;
        private ?DateTime $monitoringAt = null;
        private ?string $name = null;
        private ?string $postmortemBody = null;
        private ?DateTime $postmortemBodyLastUpdatedAt = null;
        private ?bool $postmortemIgnored = null;
        private ?bool $postmortemNotifiedSubscribers = null;
        private ?bool $postmortemNotifiedTwitter = null;
        private ?bool $postmortemPublishedAt = null; // TODO: type correct?
        private ?DateTime $resolvedAt = null;
        private ?bool $scheduledAutoCompleted = null;
        private ?bool $scheduledAutoInProgress = null;
        private ?bool $scheduledAutoTransition = null;
        private ?DateTime $scheduledFor = null;
        private ?bool $scheduledRemindPrior = null;
        private ?DateTime $scheduledRemindedAt = null;
        private ?DateTime $scheduledUntil = null;
        private ?string $shortlink = null;
        private ?DateTime $updatedAt = null;

        private ?array $updateComponents = null;


        public function __construct(Client $client, ?string $name = null)
        {
            $this->client = $client;
            $this->setName($name);
        }

        /**
         * @param Client $client
         */
        public function setClient(Client $client): void
        {
            $this->client = $client;
        }

        /**
         * @throws MissingFieldException
         * @throws InvalidValueException
         */
        public function checkPostData(): void
        {
            if(is_null($this->getId())) {
                if (!isset($this->name) || $this->name === '') {
                    throw new MissingFieldException('Mandatory field "name" missing', 1001);
                }
            }

            if (!$this->getBackfilled() || is_null($this->getBackfilled())) {

                if (!is_null($this->getScheduledFor()) && !IncidentStatus::isScheduledStatus($this->getIncidentStatus())) {
                    throw new InvalidValueException('Cannot set a realtime status for a scheduled incident. Valid values are SCHEDULED, IN_PROGRESS, VERIFYING, COMPLETED.', 1003);
                }

                if (is_null($this->getScheduledFor()) && !IncidentStatus::isRealtimeStatus($this->getIncidentStatus())) {
                    throw new InvalidValueException('Cannot set a scheduled status for a realtime incident. Valid values are INVESTIGATING, IDENTIFIED, MONITORING, RESOLVED.', 1004);
                }

                if(is_null($this->getId())) {
                    if (count($this->getComponentIds()) === 0) {
                        throw new MissingFieldException('No affected "componentIds" have been provided.', 1005);
                    }

                    if (!isset($this->incidentStatus)) {
                        throw new MissingFieldException('Mandatory field "incident_status" missing', 1002);
                    }
                }
            }


            if (!is_null($this->getScheduledUntil()) && is_null($this->getScheduledFor()) || is_null($this->getScheduledUntil()) && !is_null($this->getScheduledFor())) {
                throw new InvalidValueException('Cannot set schedule maintenance, if not both scheduled_for and scheduled_until are provided.', 1006);
            }

            if ($this->getScheduledUntil() < $this->getScheduledFor()) {
                throw new InvalidValueException('Cannot set schedule_until earlier than schedule_for.', 1007);
            }

            if (!is_null($this->getScheduledFor()) && $this->getScheduledFor() < new DateTime()) {
                throw new InvalidValueException('Cannot schedule maintenance in past.', 1008);
            }

            if ($this->getBackfilled() && !is_null($this->getBackfillDate()) && $this->getBackfillDate() > new DateTime()) {
                throw new InvalidValueException('Backfill date must not lay in future', 1009);
            }
        }

        /**
         * @return array
         * @throws InvalidValueException
         * @throws MissingFieldException
         * @throws ResponseException
         * @throws AuthenticationException
         */
        #[ArrayShape(['httpCode' => "int", 'response' => "array"])] public function createNew(): array
        {
            $this->checkPostData();

            $this->setAutoTweetOnCreation(false);
            $this->setAutoTweetAtBeginning(false);
            $this->setAutoTweetOnCompletion(false);
            $this->setAutoTweetOneHourBefore(false);
            
            $data = $this->packData();

            $response = $this->client->send(
                url: $this->client::BASE_URI . '/pages/' . $this->client->getPageId() . '/incidents',
                data: $data,
                method: new HttpMethod(HttpMethod::POST)
            );

            if($response['httpCode'] === 422){
                throw new ResponseException('The API returned the following errors: "' . (is_array($response['response']['error']) ? implode('", "', $response['response']['error']) : $response['response']['error']) . '"', 1099);
            }
            $this->hydrateFromResponse($response['response']);

            return $response;
        }

        /**
         * @throws MissingFieldException|InvalidValueException|ResponseException|AuthenticationException
         */
        #[ArrayShape(['httpCode' => "int", 'response' => "array"])] public function scheduleMaintenance(DateTime $from, DateTime $to): array
        {
            $this->setImpactOverride(new Impact(Impact::MAINTENANCE));
            if($this->getIncidentStatus() === null) {
                $this->setIncidentStatus(new IncidentStatus(IncidentStatus::SCHEDULED));
            }
            $this->setScheduledFor($from);
            $this->setScheduledUntil($to);
            
            return $this->createNew();
        }

        /**
         * @throws MissingFieldException
         * @throws AuthenticationException
         * @throws ResponseException
         * @throws InvalidValueException
         */
        public function backfillIncident(DateTime $date)
        {
            $this->setBackfillDate($date);
            $this->setBackfilled(true);

            return $this->createNew();
        }
        
        
        private function packData(): array
        {
            $data = [
                'name' => $this->getName(),
                'component_ids' => $this->getComponentIds(),
                'status' => $this->getIncidentStatus()
            ];

            if(!is_null($this->getImpactOverride())){
                $data['impact_override'] = $this->getImpactOverride();
            }
            if (!is_null($this->getScheduledRemindPrior())) {
                $data['scheduled_remind_prior'] = $this->getScheduledRemindPrior();
            }
            if (!is_null($this->getScheduledAutoInProgress())) {
                $data['scheduled_auto_in_progress'] = $this->getScheduledAutoInProgress();
            }
            if (!is_null($this->getScheduledAutoCompleted())) {
                $data['scheduled_auto_completed'] = $this->getScheduledAutoCompleted();
            }
            if (!is_null($this->getScheduledFor())){
                $data['scheduled_for'] = $this->getScheduledFor()->format('c');
            }
            if (!is_null($this->getScheduledUntil())){
                $data['scheduled_until'] = $this->getScheduledUntil()->format('c');
            }
            if (!is_null($this->isDeliverNotifications())) {
                $data['deliver_notifications'] = $this->isDeliverNotifications();
            }
            if (!is_null($this->getAutoTransitionDeliverNotificationsAtEnd())) {
                $data['auto_transition_deliver_notifications_at_end'] = $this->getAutoTransitionDeliverNotificationsAtEnd();
            }
            if (!is_null($this->getAutoTransitionDeliverNotificationsAtStart())) {
                $data['auto_transition_deliver_notifications_at_start'] = $this->getAutoTransitionDeliverNotificationsAtStart();
            }
            if (!is_null($this->getAutoTransitionToMaintenanceState())) {
                $data['auto_transition_to_maintenance_state'] = $this->getAutoTransitionToMaintenanceState();
            }
            if (!is_null($this->getAutoTransitionToOperationalState())) {
                $data['auto_transition_to_operational_state'] = $this->getAutoTransitionToOperationalState();
            }
            if (!is_null($this->getAutoTweetAtBeginning())) {
                $data['auto_tweet_at_beginning'] = $this->getAutoTweetAtBeginning();
            }
            if (!is_null($this->getAutoTweetOnCompletion())) {
                $data['auto_tweet_on_completion'] = $this->getAutoTweetOnCompletion();
            }
            if (!is_null($this->getAutoTweetOnCreation())) {
                $data['auto_tweet_on_creation'] = $this->getAutoTweetOnCreation();
            }
            if (!is_null($this->getAutoTweetOneHourBefore())) {
                $data['auto_tweet_one_hour_before'] = $this->getAutoTweetOneHourBefore();
            }
            if (!is_null($this->getBackfillDate())) {
                $data['backfill_date'] = $this->getBackfillDate()?->format('c');
            }
            if (!is_null($this->getBackfilled())) {
                $data['backfilled'] = $this->getBackfilled();
            }
            if (!is_null($this->getBody())) {
                $data['body'] = $this->getBody();
            }
            if ($this->getUpdateComponents() === []){
                if($this->getComponents() !== []) {
                    $data['components'] = [];
                    foreach($this->getComponents() as $component){
                        $data['components'][$component->getId()] = $component->getStatus();
                    }
                }
            } else {
                $data['components'] = $this->getUpdateComponents();
            }
            if ($this->getComponentIds() !== []) {
                $data['component_ids'] = $this->getComponentIds();
            }

            //TODO: metadata


            return ['incident' => $data];
        }


        private function hydrateFromResponse(array $response)
        {
            $this->setId($response['id'] ?? null);
            $this->setComponents($response['components'] ?? []);
            $this->setCreatedAt(isset($response['created_at']) ? new DateTime($response['created_at']) : null);
            $this->setIncidentStatus(isset($response['status']) ? new IncidentStatus($response['status']) : null);
            $this->setImpact(isset($response['impact']) ? new Impact($response['impact']) : null);
            $this->setImpactOverride(isset($response['impact_override']) ? new Impact($response['impact_override']) : null);
            //$this->setIncidentUpdates($response['id'] ?? null); TODO
            //$this->setMetadata($response['id'] ?? null);
            $this->setMonitoringAt(isset($response['monitoring_at']) ? new DateTime($response['monitoring_at']) : null);
            $this->setName($response['name'] ?? null);
            $this->setPostmortemBody($response['postmortem_body'] ?? null);
            $this->setPostmortemBodyLastUpdatedAt(isset($response['postmortem_body_last_updated_at']) ? new DateTime($response['postmortem_body_last_updated_at']) : null);
            $this->setPostmortemIgnored($response['postmortem_ignored'] ?? null);
            $this->setPostmortemNotifiedSubscribers($response['postmortem_notified_subscribers'] ?? null);
            $this->setPostmortemNotifiedTwitter($response['postmortem_notified_twitter'] ?? null);
            //$this->setPostmortemPublishedAt($response['postmortem_published_at'] ?? null);
            $this->setResolvedAt(isset($response['resolved_at']) ? new DateTime($response['resolved_at']) : null);
            $this->setScheduledAutoCompleted($response['scheduled_auto_completed'] ?? null);
            $this->setScheduledAutoInProgress($response['scheduled_auto_in_progress'] ?? null);
            $this->setScheduledFor(isset($response['scheduled_for']) ? new DateTime($response['scheduled_for']) : null);
            $this->setScheduledRemindPrior($response['scheduled_remind_prior'] ?? null);
            $this->setScheduledRemindedAt(isset($response['scheduled_reminded_at']) ? new DateTime($response['scheduled_reminded_at']) : null);
            $this->setScheduledUntil(isset($response['scheduled_until']) ? new DateTime($response['scheduled_until']) : null);
            $this->setShortlink($response['shortlink'] ?? null);
            $this->setUpdatedAt(isset($response['updated_at']) ? new DateTime($response['updated_at']) : null);
        }

        /**
         * @throws ResponseException
         * @throws MissingFieldException
         * @throws AuthenticationException
         */
        public function getIncident(): array
        {
            if(is_null($this->getId())){
                throw new MissingFieldException("No Incident ID has been provided",1101);
            }

            $response = $this->client->send(
                url: $this->client::BASE_URI . '/pages/' . $this->client->getPageId() . '/incidents/' . $this->getId(),
                data: [],
                method: new HttpMethod(HttpMethod::GET)
            );
            $this->hydrateFromResponse($response['response']);

            return $response;
        }

        /**
         * @throws MissingFieldException
         * @throws AuthenticationException
         * @throws ResponseException
         */
        public function deleteIncident(): array
        {
            if(is_null($this->getId())){
                throw new MissingFieldException("No Incident ID has been provided",1101);
            }

            $response = $this->client->send(
                url: $this->client::BASE_URI . '/pages/' . $this->client->getPageId() . '/incidents/' . $this->getId(),
                data: [],
                method: new HttpMethod(HttpMethod::DELETE)
            );

            $this->hydrateFromResponse($response['response']);

            return $response;
        }

        /**
         * @throws AuthenticationException
         * @throws MissingFieldException
         * @throws ResponseException
         * @throws InvalidValueException
         */
        public function updateIncident(): array
        {
            if(is_null($this->getId())){
                throw new MissingFieldException("No Incident ID has been provided",1101);
            }

            $this->checkPostData();

            $data = $this->packData();

            $response = $this->client->send(
                url: $this->client::BASE_URI . '/pages/' . $this->client->getPageId() . '/incidents/' . $this->getId(),
                data: $data,
                method: new HttpMethod(HttpMethod::PATCH)
            );

            $this->hydrateFromResponse($response['response']);

            return $response;
        }


        /**
         * @param string|null $name
         */
        public function setName(?string $name): void
        {
            $this->name = $name;
        }

        /**
         * @param \Stui\StatuspageIo\Enums\IncidentStatus|null $incidentStatus
         */
        public function setIncidentStatus(?IncidentStatus $incidentStatus): void
        {
            $this->incidentStatus = $incidentStatus;
        }

        /**
         * @param \Stui\StatuspageIo\Enums\Impact|null $impactOverride
         */
        public function setImpactOverride(?Impact $impactOverride): void
        {
            $this->impactOverride = $impactOverride;
        }

        /**
         * @param \DateTime|null $scheduledFor
         */
        public function setScheduledFor(?DateTime $scheduledFor): void
        {
            $this->scheduledFor = $scheduledFor;
        }

        /**
         * @param \DateTime|null $scheduledUntil
         */
        public function setScheduledUntil(?DateTime $scheduledUntil): void
        {
            $this->scheduledUntil = $scheduledUntil;
        }

        /**
         * @param bool|null $scheduledRemindPrior
         */
        public function setScheduledRemindPrior(?bool $scheduledRemindPrior): void
        {
            $this->scheduledRemindPrior = $scheduledRemindPrior;
        }

        /**
         * @param bool|null $scheduledAutoInProgress
         */
        public function setScheduledAutoInProgress(?bool $scheduledAutoInProgress): void
        {
            $this->scheduledAutoInProgress = $scheduledAutoInProgress;
        }

        /**
         * @param bool|null $scheduledAutoCompleted
         */
        public function setScheduledAutoCompleted(?bool $scheduledAutoCompleted): void
        {
            $this->scheduledAutoCompleted = $scheduledAutoCompleted;
        }

        /**
         * @param bool $deliverNotifications
         */
        public function setDeliverNotifications(bool $deliverNotifications): void
        {
            $this->deliverNotifications = $deliverNotifications;
        }

        /**
         * @param bool|null $autoTransitionDeliverNotificationsAtEnd
         */
        public function setAutoTransitionDeliverNotificationsAtEnd(?bool $autoTransitionDeliverNotificationsAtEnd): void
        {
            $this->autoTransitionDeliverNotificationsAtEnd = $autoTransitionDeliverNotificationsAtEnd;
        }

        /**
         * @param bool|null $autoTransitionDeliverNotificationsAtStart
         */
        public function setAutoTransitionDeliverNotificationsAtStart(?bool $autoTransitionDeliverNotificationsAtStart
        ): void {
            $this->autoTransitionDeliverNotificationsAtStart = $autoTransitionDeliverNotificationsAtStart;
        }

        /**
         * @param bool|null $autoTransitionToMaintenanceState
         */
        public function setAutoTransitionToMaintenanceState(?bool $autoTransitionToMaintenanceState): void
        {
            $this->autoTransitionToMaintenanceState = $autoTransitionToMaintenanceState;
        }

        /**
         * @param bool|null $autoTransitionToOperationalState
         */
        public function setAutoTransitionToOperationalState(?bool $autoTransitionToOperationalState): void
        {
            $this->autoTransitionToOperationalState = $autoTransitionToOperationalState;
        }

        /**
         * @param bool|null $autoTweetAtBeginning
         */
        public function setAutoTweetAtBeginning(?bool $autoTweetAtBeginning): void
        {
            $this->autoTweetAtBeginning = $autoTweetAtBeginning;
        }

        /**
         * @param bool|null $autoTweetOnCompletion
         */
        public function setAutoTweetOnCompletion(?bool $autoTweetOnCompletion): void
        {
            $this->autoTweetOnCompletion = $autoTweetOnCompletion;
        }

        /**
         * @param bool|null $autoTweetOnCreation
         */
        public function setAutoTweetOnCreation(?bool $autoTweetOnCreation): void
        {
            $this->autoTweetOnCreation = $autoTweetOnCreation;
        }

        /**
         * @param bool|null $autoTweetOneHourBefore
         */
        public function setAutoTweetOneHourBefore(?bool $autoTweetOneHourBefore): void
        {
            $this->autoTweetOneHourBefore = $autoTweetOneHourBefore;
        }

        /**
         * @param \DateTime|null $backfillDate
         */
        public function setBackfillDate(?DateTime $backfillDate): void
        {
            $this->backfillDate = $backfillDate;
        }

        /**
         * @param bool|null $backfilled
         */
        public function setBackfilled(?bool $backfilled): void
        {
            $this->backfilled = $backfilled;
        }

        /**
         * @param string|null $body
         */
        public function setBody(?string $body): void
        {
            $this->body = $body;
        }

        /**
         * @param array $components
         */
        public function setComponents(array $components): void
        {
            $this->components = $components;
        }

        /**
         * @param string $componentId
         * @param ComponentStatus $componentStatus
         */
        public function addComponent(string $componentId, ComponentStatus $componentStatus): void
        {
            $this->components[$componentId] = $componentStatus;
        }

        /**
         * @param array $componentIds
         */
        public function setComponentIds(array $componentIds): void
        {
            $this->componentIds = $componentIds;
        }

        /**
         * @param string $componentId
         */
        public function addComponentId(string $componentId): void
        {
            $this->componentIds[] = $componentId;
        }

        /**
         * @param bool|null $scheduledAutoTransition
         */
        public function setScheduledAutoTransition(?bool $scheduledAutoTransition): void
        {
            $this->scheduledAutoTransition = $scheduledAutoTransition;
        }

        /**
         * @return Client
         */
        private function getClient(): Client
        {
            return $this->client;
        }

        /**
         * @return string|null
         */
        private function getName(): ?string
        {
            return $this->name;
        }

        /**
         * @return \Stui\StatuspageIo\Enums\IncidentStatus|null
         */
        private function getIncidentStatus(): ?IncidentStatus
        {
            return $this->incidentStatus;
        }

        /**
         * @return \Stui\StatuspageIo\Enums\Impact|null
         */
        private function getImpactOverride(): ?Impact
        {
            return $this->impactOverride;
        }

        /**
         * @return \DateTime|null
         */
        private function getScheduledFor(): ?DateTime
        {
            return $this->scheduledFor;
        }

        /**
         * @return \DateTime|null
         */
        private function getScheduledUntil(): ?DateTime
        {
            return $this->scheduledUntil;
        }

        /**
         * @return bool|null
         */
        private function getScheduledRemindPrior(): ?bool
        {
            return $this->scheduledRemindPrior;
        }

        /**
         * @return bool|null
         */
        private function getScheduledAutoInProgress(): ?bool
        {
            return $this->scheduledAutoInProgress;
        }

        /**
         * @return bool|null
         */
        private function getScheduledAutoCompleted(): ?bool
        {
            return $this->scheduledAutoCompleted;
        }

        /**
         * @return bool
         */
        private function isDeliverNotifications(): bool
        {
            return $this->deliverNotifications;
        }

        /**
         * @return bool|null
         */
        private function getAutoTransitionDeliverNotificationsAtEnd(): ?bool
        {
            return $this->autoTransitionDeliverNotificationsAtEnd;
        }

        /**
         * @return bool|null
         */
        private function getAutoTransitionDeliverNotificationsAtStart(): ?bool
        {
            return $this->autoTransitionDeliverNotificationsAtStart;
        }

        /**
         * @return bool|null
         */
        private function getAutoTransitionToMaintenanceState(): ?bool
        {
            return $this->autoTransitionToMaintenanceState;
        }

        /**
         * @return bool|null
         */
        private function getAutoTransitionToOperationalState(): ?bool
        {
            return $this->autoTransitionToOperationalState;
        }

        /**
         * @return bool|null
         */
        private function getAutoTweetAtBeginning(): ?bool
        {
            return $this->autoTweetAtBeginning;
        }

        /**
         * @return bool|null
         */
        private function getAutoTweetOnCompletion(): ?bool
        {
            return $this->autoTweetOnCompletion;
        }

        /**
         * @return bool|null
         */
        private function getAutoTweetOnCreation(): ?bool
        {
            return $this->autoTweetOnCreation;
        }

        /**
         * @return bool|null
         */
        private function getAutoTweetOneHourBefore(): ?bool
        {
            return $this->autoTweetOneHourBefore;
        }

        /**
         * @return \DateTime|null
         */
        private function getBackfillDate(): ?DateTime
        {
            return $this->backfillDate;
        }

        /**
         * @return bool|null
         */
        private function getBackfilled(): ?bool
        {
            return $this->backfilled;
        }

        /**
         * @return string|null
         */
        private function getBody(): ?string
        {
            return $this->body;
        }

        /**
         * @return array
         */
        private function getComponents(): array
        {
            return $this->components;
        }

        /**
         * @return array
         */
        private function getComponentIds(): array
        {
            return $this->componentIds;
        }

        /**
         * @return bool|null
         */
        private function getScheduledAutoTransition(): ?bool
        {
            return $this->scheduledAutoTransition;
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
         * @return DateTime|null
         */
        public function getCreatedAt(): ?DateTime
        {
            return $this->createdAt;
        }

        /**
         * @param DateTime|null $createdAt
         */
        public function setCreatedAt(?DateTime $createdAt): void
        {
            $this->createdAt = $createdAt;
        }

        /**
         * @return Impact|null
         */
        public function getImpact(): ?Impact
        {
            return $this->impact;
        }

        /**
         * @param Impact|null $impact
         */
        public function setImpact(?Impact $impact): void
        {
            $this->impact = $impact;
        }

        /**
         * @return array
         */
        public function getIncidentUpdates(): array
        {
            return $this->incidentUpdates;
        }

        /**
         * @param array $incidentUpdates
         */
        public function setIncidentUpdates(array $incidentUpdates): void
        {
            $this->incidentUpdates = $incidentUpdates;
        }

        /**
         * @return DateTime|null
         */
        public function getMonitoringAt(): ?DateTime
        {
            return $this->monitoringAt;
        }

        /**
         * @param DateTime|null $monitoringAt
         */
        public function setMonitoringAt(?DateTime $monitoringAt): void
        {
            $this->monitoringAt = $monitoringAt;
        }

        /**
         * @return string|null
         */
        public function getPostmortemBody(): ?string
        {
            return $this->postmortemBody;
        }

        /**
         * @param string|null $postmortemBody
         */
        public function setPostmortemBody(?string $postmortemBody): void
        {
            $this->postmortemBody = $postmortemBody;
        }

        /**
         * @return DateTime|null
         */
        public function getPostmortemBodyLastUpdatedAt(): ?DateTime
        {
            return $this->postmortemBodyLastUpdatedAt;
        }

        /**
         * @param DateTime|null $postmortemBodyLastUpdatedAt
         */
        public function setPostmortemBodyLastUpdatedAt(?DateTime $postmortemBodyLastUpdatedAt): void
        {
            $this->postmortemBodyLastUpdatedAt = $postmortemBodyLastUpdatedAt;
        }

        /**
         * @return bool|null
         */
        public function getPostmortemIgnored(): ?bool
        {
            return $this->postmortemIgnored;
        }

        /**
         * @param bool|null $postmortemIgnored
         */
        public function setPostmortemIgnored(?bool $postmortemIgnored): void
        {
            $this->postmortemIgnored = $postmortemIgnored;
        }

        /**
         * @return bool|null
         */
        public function getPostmortemNotifiedSubscribers(): ?bool
        {
            return $this->postmortemNotifiedSubscribers;
        }

        /**
         * @param bool|null $postmortemNotifiedSubscribers
         */
        public function setPostmortemNotifiedSubscribers(?bool $postmortemNotifiedSubscribers): void
        {
            $this->postmortemNotifiedSubscribers = $postmortemNotifiedSubscribers;
        }

        /**
         * @return bool|null
         */
        public function getPostmortemNotifiedTwitter(): ?bool
        {
            return $this->postmortemNotifiedTwitter;
        }

        /**
         * @param bool|null $postmortemNotifiedTwitter
         */
        public function setPostmortemNotifiedTwitter(?bool $postmortemNotifiedTwitter): void
        {
            $this->postmortemNotifiedTwitter = $postmortemNotifiedTwitter;
        }

        /**
         * @return bool|null
         */
        public function getPostmortemPublishedAt(): ?bool
        {
            return $this->postmortemPublishedAt;
        }

        /**
         * @param bool|null $postmortemPublishedAt
         */
        public function setPostmortemPublishedAt(?bool $postmortemPublishedAt): void
        {
            $this->postmortemPublishedAt = $postmortemPublishedAt;
        }

        /**
         * @return DateTime|null
         */
        public function getResolvedAt(): ?DateTime
        {
            return $this->resolvedAt;
        }

        /**
         * @param DateTime|null $resolvedAt
         */
        public function setResolvedAt(?DateTime $resolvedAt): void
        {
            $this->resolvedAt = $resolvedAt;
        }

        /**
         * @return DateTime|null
         */
        public function getScheduledRemindedAt(): ?DateTime
        {
            return $this->scheduledRemindedAt;
        }

        /**
         * @param DateTime|null $scheduledRemindedAt
         */
        public function setScheduledRemindedAt(?DateTime $scheduledRemindedAt): void
        {
            $this->scheduledRemindedAt = $scheduledRemindedAt;
        }

        /**
         * @return string|null
         */
        public function getShortlink(): ?string
        {
            return $this->shortlink;
        }

        /**
         * @param string|null $shortlink
         */
        public function setShortlink(?string $shortlink): void
        {
            $this->shortlink = $shortlink;
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
         * @return array|null
         */
        public function getUpdateComponents(): ?array
        {
            return $this->updateComponents;
        }

        public function addUpdateComponent(Component $component, ComponentStatus $status): ?array
        {
            $this->updateComponents[$component->getId()] = $status->jsonSerialize();
            return []; // TODO
        }

        /**
         * @param array|null $updateComponents
         */
        public function setUpdateComponents(?array $updateComponents): void
        {
            $this->updateComponents = $updateComponents;
        }



    }
