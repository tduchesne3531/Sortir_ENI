<?php

namespace App\Entity;

use App\Entity\Traits\WhoAndWhenTrait;
use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipantRepository::class)]
class Participant extends User
{
    use WhoAndWhenTrait;


    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;


    #[ORM\ManyToOne(inversedBy: 'participants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Site $site = null;

    /**
     * @var Collection<int, Activity>
     */
    #[ORM\OneToMany(targetEntity: Activity::class, mappedBy: 'manager', orphanRemoval: true)]
    private Collection $sortiesManaged;

    /**
     * @var Collection<int, Activity>
     */
    #[ORM\ManyToMany(targetEntity: Activity::class, mappedBy: 'participants')]
    private Collection $sorties;

    #[ORM\Column]
    private ?bool $isActive = null;


    public function __construct()
    {
        $this->sortiesManaged = new ArrayCollection();
        $this->sorties = new ArrayCollection();

        // Initialization created fields by WhoAndWhenTrait
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTime();
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

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

}
