<?php
namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank(message="Veuillez entrer un nom de produit")
     */
    private $name;
    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Le prix est obligatoire")
     * @Assert\GreaterThan(
     *     value = 0,
     *     message = "Le prix doit être supérieur à zéro")
     *
     */
    private $price;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez entrer une description du produit")
     */
    private $description;
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Veuillez entrer une quantité")
     * @Assert\GreaterThan(
     *     value = 0,
     *     message = "La quantité doit être supérieur à zéro")
     *
     */
    private $quantity;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="product")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Command", mappedBy="product", cascade={"persist"})
     */
    private $commandOrder;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;
    public function __construct()
    {
        $this->commandOrder = new ArrayCollection();
    }
    public function getId()
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
    public function getPrice(): ?float
    {
        return $this->price;
    }
    public function setPrice(float $price): self
    {
        $this->price = $price;
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
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }
    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
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
    public function getCategory(): ?Category
    {
        return $this->category;
    }
    public function setCategory(?Category $category): self
    {
        $this->category = $category;
        return $this;
    }
    /**
     * @return Collection|Command[]
     */
    public function getCommandOrder(): Collection
    {
        return $this->commandOrder;
    }
    public function addCommandOrder(Command $commandOrder): self
    {
        if (!$this->commandOrder->contains($commandOrder)) {
            $this->commandOrder[] = $commandOrder;
            $commandOrder->addProduct($this);
        }
        return $this;
    }
    public function removeCommandOrder(Command $commandOrder): self
    {
        if ($this->commandOrder->contains($commandOrder)) {
            $this->commandOrder->removeElement($commandOrder);
            $commandOrder->removeProduct($this);
        }
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
