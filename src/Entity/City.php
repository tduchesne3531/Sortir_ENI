<?php

namespace App\Entity;

use App\Repository\CitieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CitieRepository::class)]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $zipCode = null;

    /**
     * @var Collection<int, Place>
     */
    #[ORM\OneToMany(targetEntity: Place::class, mappedBy: 'city')]
    private Collection $places;

    #[ORM\Column]
    private ?\DateTimeImmutable $CreatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'citiesCreated')]
    private ?Participant $UserCreation = null;

    public function __construct()
    {
        $this->places = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(?string $zipCode): void
    {
        $this->zipCode = $zipCode;
    }

    public function getPlaces(): Collection
    {
        return $this->places;
    }

    public function setPlaces(Collection $places): void
    {
        $this->places = $places;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $CreatedAt): void
    {
        $this->CreatedAt = $CreatedAt;
    }

    public function getUserCreation(): ?Participant
    {
        return $this->UserCreation;
    }

    public function setUserCreation(?Participant $UserCreation): void
    {
        $this->UserCreation = $UserCreation;
    }

}
