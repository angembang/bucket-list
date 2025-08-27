<?php

namespace App\Entity;

use App\Repository\WishRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: WishRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Wish
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: "the field title is required")]
    #[Assert\Length(
        min: 2,
        max: 250,
        minMessage: "the field name can't be less than 2 characters",
        maxMessage: "the field name can't be more than 255 characters"
    )]
    #[ORM\Column(length: 250)]
    private ?string $title = null;

    #[Assert\NotBlank(message: "the description name is required")]
    #[Assert\Length(
        min: 2,
        max: 500,
        minMessage: "the field description can't be less than 2 characters",
        maxMessage: "the field description can't be more than 500 characters"
    )]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private bool $isPublished;

    #[Assert\LessThan(propertyPath: "dateUpdated")]
    #[ORM\Column]
    private ?\DateTime $dateCreated = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateUpdated = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $wishImage = null;

    #[ORM\ManyToOne(inversedBy: 'wishes')]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'wishes')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isPublished(): bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): static
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getDateCreated(): ?\DateTime
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTime $dateCreated): static
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getDateUpdated(): ?\DateTime
    {
        return $this->dateUpdated;
    }

    public function setDateUpdated(?\DateTime $dateUpdated): static
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    #[ORM\PrePersist]
    public function insertIsPublisedData(){
        $this->setIsPublished(true);
        $this->setDateCreated(new \DateTime());
        $this->setDateUpdated = null;
    }

    #[ORM\PreUpdate]
    public function updateDate(){
        $this->setDateUpdated(new \DateTime());
    }

    public function getWishImage(): ?string
    {
        return $this->wishImage;
    }

    public function setWishImage(?string $wishImage): static
    {
        $this->wishImage = $wishImage;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
