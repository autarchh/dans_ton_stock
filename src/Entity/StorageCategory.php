<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\StorageCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ApiResource()
 * @Broadcast()
 * @ORM\Entity(repositoryClass=StorageCategoryRepository::class)
 */
class StorageCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=Storage::class, mappedBy="storageCategory")
     */
    private $storage;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $color;

    public function __construct()
    {
        $this->storage = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Storage[]
     */
    public function getStorage(): Collection
    {
        return $this->storage;
    }

    public function addStorage(Storage $storage): self
    {
        if (!$this->storage->contains($storage)) {
            $this->storage[] = $storage;
            $storage->setStorageCategory($this);
        }

        return $this;
    }

    public function removeStorage(Storage $storage): self
    {
        if ($this->storage->removeElement($storage)) {
            // set the owning side to null (unless already changed)
            if ($storage->getStorageCategory() === $this) {
                $storage->setStorageCategory(null);
            }
        }

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }
}
