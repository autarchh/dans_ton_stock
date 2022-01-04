<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

/**
 * @Broadcast()
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="date_immutable")
     */
    private $addAt;

    /**
     * @ORM\Column(type="date_immutable")
     */
    private $bbDate;

    /**
     * @ORM\Column(type="float")
     */
    private $qtt;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $unit;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=Storage::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $storage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAddAt(): ?\DateTimeImmutable
    {
        return $this->addAt;
    }

    public function setAddAt(\DateTimeImmutable $addAt): self
    {
        $this->addAt = $addAt;

        return $this;
    }

    public function getBbDate(): ?\DateTimeImmutable
    {
        return $this->bbDate;
    }

    public function setBbDate(\DateTimeImmutable $bbDate): self
    {
        $this->bbDate = $bbDate;

        return $this;
    }

    public function getQtt(): ?float
    {
        return $this->qtt;
    }

    public function setQtt(float $qtt): self
    {
        $this->qtt = $qtt;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getStorage(): ?Storage
    {
        return $this->storage;
    }

    public function setStorage(?Storage $storage): self
    {
        $this->storage = $storage;

        return $this;
    }
}
