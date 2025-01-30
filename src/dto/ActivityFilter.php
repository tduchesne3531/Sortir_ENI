<?php

namespace App\dto;

use App\Entity\Participant;
use App\Entity\Site;
use DateTime;

class ActivityFilter {
    private ?Site $site = null;
    private ?String $search = null;
    private ?dateTime $startDate = null;
    private ?dateTime $endDate = null;
    private bool $organizer = false;
    private bool $registered = false;
    private bool $notRegistered = false;
    private bool $past = false;
    private bool $archived = false;

    /**
     * @JsonIgnore
     */
    private ?Participant $user = null;

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): void
    {
        $this->site = $site;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function setSearch(?string $search): void
    {
        $this->search = $search;
    }

    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getOrganizer(): ?bool
    {
        return $this->organizer;
    }

    public function setOrganizer(?bool $organizer): void
    {
        $this->organizer = $organizer;
    }

    public function getRegistered(): ?bool
    {
        return $this->registered;
    }

    public function setRegistered(?bool $registered): void
    {
        $this->registered = $registered;
    }

    public function getNotRegistered(): ?bool
    {
        return $this->notRegistered;
    }

    public function setNotRegistered(?bool $notRegistered): void
    {
        $this->notRegistered = $notRegistered;
    }

    public function getPast(): ?bool
    {
        return $this->past;
    }

    public function setPast(?bool $past): void
    {
        $this->past = $past;
    }

    public function getArchived(): ?bool
    {
        return $this->archived;
    }

    public function setArchived(?bool $archived): void
    {
        $this->archived = $archived;
    }

    public function getUser(): ?Participant
    {
        return $this->user;
    }

    public function setUser(?Participant $user): void
    {
        $this->user = $user;
    }




}