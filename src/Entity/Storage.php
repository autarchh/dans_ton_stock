<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\StorageRepository;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

use App\Annotation\UserAware;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=StorageRepository::class)
 * @UserAware(userFieldName="user_id")
 */
class Storage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Veuillez entrer un nom pour le lieu de Stockage.")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $location;

    /**
     * @ORM\ManyToOne(targetEntity=StorageCategory::class, inversedBy="storage")
     * @ORM\JoinColumn(nullable=false)
     */
    private $storageCategory;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="storage", orphanRemoval=true)
     */
    private $products;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="storages")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

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

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getStorageCategory(): ?StorageCategory
    {
        return $this->storageCategory;
    }

    public function setStorageCategory(?StorageCategory $storageCategory): self
    {
        $this->storageCategory = $storageCategory;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setStorage($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getStorage() === $this) {
                $product->setStorage(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name . ' (' . $this->location . ')';
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
