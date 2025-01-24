<?php

namespace App\dto;


class ActivityDto
{

    private ?int $id = null;
    private ?string $name = null;
    private ?\DateTimeInterface $dateStartTime = null;

    private ?int $duration = null;

    private ?\DateTimeInterface $registrationDeadLine = null;
    private ?int $inscrits = null;

    private ?int $maxRegistration = null;

    private ?string $description = null;
    private ?string $state = null;
    private ?bool $incrit = null;
    private ?String $organisateur = null;
    private ?String $actions = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getDateStartTime(): ?\DateTimeInterface
    {
        return $this->dateStartTime;
    }

    public function setDateStartTime(?\DateTimeInterface $dateStartTime): void
    {
        $this->dateStartTime = $dateStartTime;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): void
    {
        $this->duration = $duration;
    }

    public function getRegistrationDeadLine(): ?\DateTimeInterface
    {
        return $this->registrationDeadLine;
    }

    public function setRegistrationDeadLine(?\DateTimeInterface $registrationDeadLine): void
    {
        $this->registrationDeadLine = $registrationDeadLine;
    }

    public function getInscrits(): ?int
    {
        return $this->inscrits;
    }

    public function setInscrits(?int $inscrits): void
    {
        $this->inscrits = $inscrits;
    }

    public function getMaxRegistration(): ?int
    {
        return $this->maxRegistration;
    }

    public function setMaxRegistration(?int $maxRegistration): void
    {
        $this->maxRegistration = $maxRegistration;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): void
    {
        $this->state = $state;
    }

    public function getIncrit(): ?bool
    {
        return $this->incrit;
    }

    public function setIncrit(?bool $incrit): void
    {
        $this->incrit = $incrit;
    }

    public function getOrganisateur(): ?string
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?string $organisateur): void
    {
        $this->organisateur = $organisateur;
    }

    public function getActions(): ?string
    {
        return $this->actions;
    }

    public function setActions(?string $actions): void
    {
        $this->actions = $actions;
    }

}
