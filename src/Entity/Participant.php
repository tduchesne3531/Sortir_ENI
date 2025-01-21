<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipantRepository::class)]
class Participant extends User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\ManyToOne(inversedBy: 'participants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Site $site = null;

    /**
     * @var Collection<int, Sortie>
     */
    #[ORM\OneToMany(targetEntity: Sortie::class, mappedBy: 'manager', orphanRemoval: true)]
    private Collection $sortiesManaged;

    /**
     * @var Collection<int, Sortie>
     */
    #[ORM\ManyToMany(targetEntity: Sortie::class, mappedBy: 'participants')]
    private Collection $sorties;

    #[ORM\Column]
    private ?\DateTimeImmutable $CreatedAt = null;

    /**
     * @var Collection<int, City>
     */
    #[ORM\OneToMany(targetEntity: City::class, mappedBy: 'UserCreation')]
    private Collection $citiesCreated;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'participantsCreated')]
    private ?self $UserCreation = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'UserCreation')]
    private Collection $participantsCreated;

    public function __construct()
    {
        $this->sortiesManaged = new ArrayCollection();
        $this->sorties = new ArrayCollection();
        $this->citiesCreated = new ArrayCollection();
        $this->participantsCreated = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): void
    {
        $this->firstname = $firstname;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): void
    {
        $this->active = $active;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): void
    {
        $this->site = $site;
    }

    public function getSortiesManaged(): Collection
    {
        return $this->sortiesManaged;
    }

    public function setSortiesManaged(Collection $sortiesManaged): void
    {
        $this->sortiesManaged = $sortiesManaged;
    }

    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function setSorties(Collection $sorties): void
    {
        $this->sorties = $sorties;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $CreatedAt): void
    {
        $this->CreatedAt = $CreatedAt;
    }

    public function getCitiesCreated(): Collection
    {
        return $this->citiesCreated;
    }

    public function setCitiesCreated(Collection $citiesCreated): void
    {
        $this->citiesCreated = $citiesCreated;
    }

    public function getUserCreation(): ?Participant
    {
        return $this->UserCreation;
    }

    public function setUserCreation(?Participant $UserCreation): void
    {
        $this->UserCreation = $UserCreation;
    }

    public function getParticipantsCreated(): Collection
    {
        return $this->participantsCreated;
    }

    public function setParticipantsCreated(Collection $participantsCreated): void
    {
        $this->participantsCreated = $participantsCreated;
    }

}
