<?php

declare(strict_types=1);

namespace Stui\StatuspageIo\Service;

use DateTime;

class Page
{
    private ?string $id = null;
    private ?DateTime $createdAt = null;
    private ?DateTime $updatedAt = null;
    private ?string $name = null;
    private ?string $pageDescription = null;
    private ?string $headline = null;
    private ?string $branding = null;
    private ?string $subdomain = null;
    private ?string $domain = null;
    private ?string $url = null;
    private ?string $supportUrl = null;
    private ?bool $hiddenFromSearch = null;
    private ?bool $allowPageSubscribers = null;
    private ?bool $allowIncidentSubscribers = null;
    private ?bool $allowEmailSubscribers = null;
    private ?bool $allowSmsSubscribers = null;
    private ?bool $allowRssAtomFeeds = null;
    private ?bool $allowWebhookSubscribers = null;
    private ?string $notificationsFromEmail = null;
    private ?string $notifications_EmailFooter = null;
    private ?string $activityScore = null;
    private ?string $twitterUsername = null;
    private ?bool $viewersMustBeTeamMembers = null;
    private ?string $ipRestrictions = null;
    private ?string $city = null;
    private ?string $state = null;
    private ?string $country = null;
    private ?string $timeZone = null; // TODO: own object?
    private ?string $cssBodyBackgroundColor = null;
    private ?string $cssFontColor = null;
    private ?string $cssLightFontColor = null;
    private ?string $cssGreens = null;
    private ?string $cssYellows = null;
    private ?string $cssOranges = null;
    private ?string $cssBlues = null;
    private ?string $cssReds = null;
    private ?string $cssBorderColor = null;
    private ?string $cssGraphColor = null;
    private ?string $cssLinkColor = null;
    private ?string $cssNoData = null;
    private ?string $faviconLogo = null;
    private ?string $transactionalLogo = null;
    private ?string $heroCover = null;
    private ?string $emailLogo = null;
    private ?string $twitterLogo = null;

    public function __construct(string $pageId)
    {
        $this->setId($pageId);
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
    public function getPageDescription(): ?string
    {
        return $this->pageDescription;
    }

    /**
     * @param string|null $pageDescription
     */
    public function setPageDescription(?string $pageDescription): void
    {
        $this->pageDescription = $pageDescription;
    }

    /**
     * @return string|null
     */
    public function getHeadline(): ?string
    {
        return $this->headline;
    }

    /**
     * @param string|null $headline
     */
    public function setHeadline(?string $headline): void
    {
        $this->headline = $headline;
    }

    /**
     * @return string|null
     */
    public function getBranding(): ?string
    {
        return $this->branding;
    }

    /**
     * @param string|null $branding
     */
    public function setBranding(?string $branding): void
    {
        $this->branding = $branding;
    }

    /**
     * @return string|null
     */
    public function getSubdomain(): ?string
    {
        return $this->subdomain;
    }

    /**
     * @param string|null $subdomain
     */
    public function setSubdomain(?string $subdomain): void
    {
        $this->subdomain = $subdomain;
    }

    /**
     * @return string|null
     */
    public function getDomain(): ?string
    {
        return $this->domain;
    }

    /**
     * @param string|null $domain
     */
    public function setDomain(?string $domain): void
    {
        $this->domain = $domain;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     */
    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return string|null
     */
    public function getSupportUrl(): ?string
    {
        return $this->supportUrl;
    }

    /**
     * @param string|null $supportUrl
     */
    public function setSupportUrl(?string $supportUrl): void
    {
        $this->supportUrl = $supportUrl;
    }

    /**
     * @return bool|null
     */
    public function getHiddenFromSearch(): ?bool
    {
        return $this->hiddenFromSearch;
    }

    /**
     * @param bool|null $hiddenFromSearch
     */
    public function setHiddenFromSearch(?bool $hiddenFromSearch): void
    {
        $this->hiddenFromSearch = $hiddenFromSearch;
    }

    /**
     * @return bool|null
     */
    public function getAllowPageSubscribers(): ?bool
    {
        return $this->allowPageSubscribers;
    }

    /**
     * @param bool|null $allowPageSubscribers
     */
    public function setAllowPageSubscribers(?bool $allowPageSubscribers): void
    {
        $this->allowPageSubscribers = $allowPageSubscribers;
    }

    /**
     * @return bool|null
     */
    public function getAllowIncidentSubscribers(): ?bool
    {
        return $this->allowIncidentSubscribers;
    }

    /**
     * @param bool|null $allowIncidentSubscribers
     */
    public function setAllowIncidentSubscribers(?bool $allowIncidentSubscribers): void
    {
        $this->allowIncidentSubscribers = $allowIncidentSubscribers;
    }

    /**
     * @return bool|null
     */
    public function getAllowEmailSubscribers(): ?bool
    {
        return $this->allowEmailSubscribers;
    }

    /**
     * @param bool|null $allowEmailSubscribers
     */
    public function setAllowEmailSubscribers(?bool $allowEmailSubscribers): void
    {
        $this->allowEmailSubscribers = $allowEmailSubscribers;
    }

    /**
     * @return bool|null
     */
    public function getAllowSmsSubscribers(): ?bool
    {
        return $this->allowSmsSubscribers;
    }

    /**
     * @param bool|null $allowSmsSubscribers
     */
    public function setAllowSmsSubscribers(?bool $allowSmsSubscribers): void
    {
        $this->allowSmsSubscribers = $allowSmsSubscribers;
    }

    /**
     * @return bool|null
     */
    public function getAllowRssAtomFeeds(): ?bool
    {
        return $this->allowRssAtomFeeds;
    }

    /**
     * @param bool|null $allowRssAtomFeeds
     */
    public function setAllowRssAtomFeeds(?bool $allowRssAtomFeeds): void
    {
        $this->allowRssAtomFeeds = $allowRssAtomFeeds;
    }

    /**
     * @return bool|null
     */
    public function getAllowWebhookSubscribers(): ?bool
    {
        return $this->allowWebhookSubscribers;
    }

    /**
     * @param bool|null $allowWebhookSubscribers
     */
    public function setAllowWebhookSubscribers(?bool $allowWebhookSubscribers): void
    {
        $this->allowWebhookSubscribers = $allowWebhookSubscribers;
    }

    /**
     * @return string|null
     */
    public function getNotificationsFromEmail(): ?string
    {
        return $this->notificationsFromEmail;
    }

    /**
     * @param string|null $notificationsFromEmail
     */
    public function setNotificationsFromEmail(?string $notificationsFromEmail): void
    {
        $this->notificationsFromEmail = $notificationsFromEmail;
    }

    /**
     * @return string|null
     */
    public function getNotificationsEmailFooter(): ?string
    {
        return $this->notifications_EmailFooter;
    }

    /**
     * @param string|null $notifications_EmailFooter
     */
    public function setNotificationsEmailFooter(?string $notifications_EmailFooter): void
    {
        $this->notifications_EmailFooter = $notifications_EmailFooter;
    }

    /**
     * @return string|null
     */
    public function getActivityScore(): ?string
    {
        return $this->activityScore;
    }

    /**
     * @param string|null $activityScore
     */
    public function setActivityScore(?string $activityScore): void
    {
        $this->activityScore = $activityScore;
    }

    /**
     * @return string|null
     */
    public function getTwitterUsername(): ?string
    {
        return $this->twitterUsername;
    }

    /**
     * @param string|null $twitterUsername
     */
    public function setTwitterUsername(?string $twitterUsername): void
    {
        $this->twitterUsername = $twitterUsername;
    }

    /**
     * @return bool|null
     */
    public function getViewersMustBeTeamMembers(): ?bool
    {
        return $this->viewersMustBeTeamMembers;
    }

    /**
     * @param bool|null $viewersMustBeTeamMembers
     */
    public function setViewersMustBeTeamMembers(?bool $viewersMustBeTeamMembers): void
    {
        $this->viewersMustBeTeamMembers = $viewersMustBeTeamMembers;
    }

    /**
     * @return string|null
     */
    public function getIpRestrictions(): ?string
    {
        return $this->ipRestrictions;
    }

    /**
     * @param string|null $ipRestrictions
     */
    public function setIpRestrictions(?string $ipRestrictions): void
    {
        $this->ipRestrictions = $ipRestrictions;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     */
    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @param string|null $state
     */
    public function setState(?string $state): void
    {
        $this->state = $state;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $country
     */
    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    /**
     * @return string|null
     */
    public function getTimeZone(): ?string
    {
        return $this->timeZone;
    }

    /**
     * @param string|null $timeZone
     */
    public function setTimeZone(?string $timeZone): void
    {
        $this->timeZone = $timeZone;
    }

    /**
     * @return string|null
     */
    public function getCssBodyBackgroundColor(): ?string
    {
        return $this->cssBodyBackgroundColor;
    }

    /**
     * @param string|null $cssBodyBackgroundColor
     */
    public function setCssBodyBackgroundColor(?string $cssBodyBackgroundColor): void
    {
        $this->cssBodyBackgroundColor = $cssBodyBackgroundColor;
    }

    /**
     * @return string|null
     */
    public function getCssFontColor(): ?string
    {
        return $this->cssFontColor;
    }

    /**
     * @param string|null $cssFontColor
     */
    public function setCssFontColor(?string $cssFontColor): void
    {
        $this->cssFontColor = $cssFontColor;
    }

    /**
     * @return string|null
     */
    public function getCssLightFontColor(): ?string
    {
        return $this->cssLightFontColor;
    }

    /**
     * @param string|null $cssLightFontColor
     */
    public function setCssLightFontColor(?string $cssLightFontColor): void
    {
        $this->cssLightFontColor = $cssLightFontColor;
    }

    /**
     * @return string|null
     */
    public function getCssGreens(): ?string
    {
        return $this->cssGreens;
    }

    /**
     * @param string|null $cssGreens
     */
    public function setCssGreens(?string $cssGreens): void
    {
        $this->cssGreens = $cssGreens;
    }

    /**
     * @return string|null
     */
    public function getCssYellows(): ?string
    {
        return $this->cssYellows;
    }

    /**
     * @param string|null $cssYellows
     */
    public function setCssYellows(?string $cssYellows): void
    {
        $this->cssYellows = $cssYellows;
    }

    /**
     * @return string|null
     */
    public function getCssOranges(): ?string
    {
        return $this->cssOranges;
    }

    /**
     * @param string|null $cssOranges
     */
    public function setCssOranges(?string $cssOranges): void
    {
        $this->cssOranges = $cssOranges;
    }

    /**
     * @return string|null
     */
    public function getCssBlues(): ?string
    {
        return $this->cssBlues;
    }

    /**
     * @param string|null $cssBlues
     */
    public function setCssBlues(?string $cssBlues): void
    {
        $this->cssBlues = $cssBlues;
    }

    /**
     * @return string|null
     */
    public function getCssReds(): ?string
    {
        return $this->cssReds;
    }

    /**
     * @param string|null $cssReds
     */
    public function setCssReds(?string $cssReds): void
    {
        $this->cssReds = $cssReds;
    }

    /**
     * @return string|null
     */
    public function getCssBorderColor(): ?string
    {
        return $this->cssBorderColor;
    }

    /**
     * @param string|null $cssBorderColor
     */
    public function setCssBorderColor(?string $cssBorderColor): void
    {
        $this->cssBorderColor = $cssBorderColor;
    }

    /**
     * @return string|null
     */
    public function getCssGraphColor(): ?string
    {
        return $this->cssGraphColor;
    }

    /**
     * @param string|null $cssGraphColor
     */
    public function setCssGraphColor(?string $cssGraphColor): void
    {
        $this->cssGraphColor = $cssGraphColor;
    }

    /**
     * @return string|null
     */
    public function getCssLinkColor(): ?string
    {
        return $this->cssLinkColor;
    }

    /**
     * @param string|null $cssLinkColor
     */
    public function setCssLinkColor(?string $cssLinkColor): void
    {
        $this->cssLinkColor = $cssLinkColor;
    }

    /**
     * @return string|null
     */
    public function getCssNoData(): ?string
    {
        return $this->cssNoData;
    }

    /**
     * @param string|null $cssNoData
     */
    public function setCssNoData(?string $cssNoData): void
    {
        $this->cssNoData = $cssNoData;
    }

    /**
     * @return string|null
     */
    public function getFaviconLogo(): ?string
    {
        return $this->faviconLogo;
    }

    /**
     * @param string|null $faviconLogo
     */
    public function setFaviconLogo(?string $faviconLogo): void
    {
        $this->faviconLogo = $faviconLogo;
    }

    /**
     * @return string|null
     */
    public function getTransactionalLogo(): ?string
    {
        return $this->transactionalLogo;
    }

    /**
     * @param string|null $transactionalLogo
     */
    public function setTransactionalLogo(?string $transactionalLogo): void
    {
        $this->transactionalLogo = $transactionalLogo;
    }

    /**
     * @return string|null
     */
    public function getHeroCover(): ?string
    {
        return $this->heroCover;
    }

    /**
     * @param string|null $heroCover
     */
    public function setHeroCover(?string $heroCover): void
    {
        $this->heroCover = $heroCover;
    }

    /**
     * @return string|null
     */
    public function getEmailLogo(): ?string
    {
        return $this->emailLogo;
    }

    /**
     * @param string|null $emailLogo
     */
    public function setEmailLogo(?string $emailLogo): void
    {
        $this->emailLogo = $emailLogo;
    }

    /**
     * @return string|null
     */
    public function getTwitterLogo(): ?string
    {
        return $this->twitterLogo;
    }

    /**
     * @param string|null $twitterLogo
     */
    public function setTwitterLogo(?string $twitterLogo): void
    {
        $this->twitterLogo = $twitterLogo;
    }


}