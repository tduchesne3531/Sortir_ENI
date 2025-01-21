<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipantRepository::class)]
class Participant
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
     * @var Collection<int, Citie>
     */
    #[ORM\OneToMany(targetEntity: Citie::class, mappedBy: 'UserCreation')]
    private Collection $citiesCreated;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'participantsCreated')]
    private ?self $UserCreation = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'UserCreation')]
    private Collection $participantsCreated;

    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function setSorties(Collection $sorties): void
    {
        $this->sorties = $sorties;
    }

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

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): static
    {
        $this->site = $site;

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSortiesManaged(): Collection
    {
        return $this->sortiesManaged;
    }

    public function addSortiesManaged(Sortie $sortie): static
    {
        if (!$this->sortiesManaged->contains($sortie)) {
            $this->sortiesManaged->add($sortie);
            $sortie->setCreatedUser($this);
        }

        return $this;
    }

    public function removeSortiesManaged(Sortie $sortie): static
    {
        if ($this->sortiesManaged->removeElement($sortie)) {
            // set the owning side to null (unless already changed)
            if ($sortie->getCreatedUser() === $this) {
                $sortie->setCreatedUser(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeImmutable $CreatedAt): static
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Citie>
     */
    public function getCitiesCreated(): Collection
    {
        return $this->citiesCreated;
    }

    public function addCitiesCreated(Citie $citiesCreated): static
    {
        if (!$this->citiesCreated->contains($citiesCreated)) {
            $this->citiesCreated->add($citiesCreated);
            $citiesCreated->setUserCreation($this);
        }

        return $this;
    }

    public function removeCitiesCreated(Citie $citiesCreated): static
    {
        if ($this->citiesCreated->removeElement($citiesCreated)) {
            // set the owning side to null (unless already changed)
            if ($citiesCreated->getUserCreation() === $this) {
                $citiesCreated->setUserCreation(null);
            }
        }

        return $this;
    }

    public function getUserCreation(): ?self
    {
        return $this->UserCreation;
    }

    public function setUserCreation(?self $UserCreation): static
    {
        $this->UserCreation = $UserCreation;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getParticipantsCreated(): Collection
    {
        return $this->participantsCreated;
    }

    public function addParticipantsCreated(self $participantsCreated): static
    {
        if (!$this->participantsCreated->contains($participantsCreated)) {
            $this->participantsCreated->add($participantsCreated);
            $participantsCreated->setUserCreation($this);
        }

        return $this;
    }

    public function removeParticipantsCreated(self $participantsCreated): static
    {
        if ($this->participantsCreated->removeElement($participantsCreated)) {
            // set the owning side to null (unless already changed)
            if ($participantsCreated->getUserCreation() === $this) {
                $participantsCreated->setUserCreation(null);
            }
        }

        return $this;
    }
}
