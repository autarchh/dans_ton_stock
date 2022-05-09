<?php

namespace App\Entity;

use DateInterval;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Symfony\Component\Serializer\Annotation\Groups;

use ApiPlatform\Core\Annotation\ApiResource;


/**
 * @ApiResource(
 *          normalizationContext={"groups"={"article:read"}},
 *          denormalizationContext={"groups"={"article:write"}})
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups("article:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Groups({"article:read", "article:write"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=20)
     * 
     * @Groups({"article:read", "article:write"})
     */
    private $nutriscore;

    /**
     * @ORM\Column(type="date")
     * 
     * @Groups("article:read")
     */
    private $addAt;

    /**
     * @ORM\Column(type="date", nullable=true)
     * 
     * @Groups({"article:read", "article:write"})
     */
    private $bbDate;

    /**
     * @ORM\Column(type="float")
     * 
     * @Groups({"article:read", "article:write"})
     */
    private $qtt;

    /**
     * @ORM\Column(type="string", length=50)
     * 
     * @Groups({"article:read", "article:write"})
     */
    private $unit;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     * 
     * @Groups({"article:read", "article:write"})
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=Storage::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     * 
     * @Groups({"article:read", "article:write"})
     */
    private $storage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @Groups({"article:read", "article:write"})
     */
    private $img;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @Groups({"article:read", "article:write"})
     */
    private $imgThumb;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     * 
     * @Groups("article:read")
     */
    private $slug;


    public function __construct($data = null)
    {   
        $this->addAt = new \DateTime();
        $bbdate = new \DateTime();
        $this->bbDate = $bbdate->add(new DateInterval('P7D'));     
          
        if($data) {
            $this->name = $data['product_name_fr'] ?? $data['product_name'];
            $this->nutriscore = $data['nutriscore_grade'] ?? 'c';
            $this->img = $data['image_front_url'];
            $this->imgThumb = $data['image_front_thumb_url'];
            
            $quantity = $data['quantity'];
            $qtt_exploded = explode(' ', $quantity);
            $qtt = str_replace(',', '.', $qtt_exploded[0]);
            $this->qtt = floatval($qtt);
            
            $unit = $qtt_exploded[1];
                switch ($unit) {
                    case 'kg': $this->unit = 'Kilogramme'; break;
                    case 'g': $this->unit = 'Gramme'; break;
                    case 'cl': $this->unit = 'Centilitre'; break;
                    case 'ml': $this->unit = 'Mililitre'; break;
                    case 'l': $this->unit = 'Litre'; break;
                    default:
                        $this->unit = 'Unité';
                        break;
                }
        }
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

    public function getNutriscore(): ?string
    {
        return $this->nutriscore;
    }

    public function setNutriscore(string $nutriscore): self
    {
        $this->nutriscore = $nutriscore;

        return $this;
    }

    public function getAddAt(): ?\DateTimeInterface
    {
        return $this->addAt;
    }

    public function setAddAt(\DateTimeInterface $addAt): self
    {
        $this->addAt = $addAt;

        return $this;
    }

    public function getBbDate(): ?\DateTimeInterface
    {
        return $this->bbDate;
    }

    public function setBbDate(\DateTimeInterface $bbDate): self
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

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getImgThumb(): ?string
    {
        return $this->imgThumb;
    }

    public function setImgThumb(?string $imgThumb): self
    {
        $this->imgThumb = $imgThumb;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

}
