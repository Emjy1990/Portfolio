<?php
namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandRepository")
 */
class Command
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="datetime")
     */
    private $dateOpen;
    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAccepted;
    /**
     * @ORM\Column(type="datetime")
     */
    private $dateDelivery;
    /**
     * @ORM\Column(type="datetime")
     */
    private $dateClosed;
    /**
     * @ORM\Column(type="float")
     */
    private $price;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="command")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PaymentMode", inversedBy="commands")
     * @ORM\JoinColumn(nullable=false)
     */
    private $paymentMode;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DeliveryMode", inversedBy="commands")
     * @ORM\JoinColumn(nullable=false)
     */
    private $deliveryMode;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Statusorder", inversedBy="commands")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Adress", inversedBy="deliveryCommand")
     * @ORM\JoinColumn(nullable=false)
     */
    private $deliveryAdress;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Adress", inversedBy="commands")
     * @ORM\JoinColumn(nullable=false)
     */
    private $paymentAdress;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", inversedBy="commandOrder", cascade={"persist"})
     */
    private $product;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="commandfarmer")
     * @ORM\JoinColumn(nullable=false)
     */
    private $farmer;
    public function __construct()
    {
        $this->product = new ArrayCollection();
        $this->dateOpen = new \DateTime();
        $this->dateAccepted = new \DateTime();
        $this->dateDelivery = new \DateTime();
        $this->dateClosed = new \DateTime();
    }
    public function getId()
    {
        return $this->id;
    }
    public function getDateOpen(): ?\DateTimeInterface
    {
        return $this->dateOpen;
    }
    public function setDateOpen(\DateTimeInterface $dateOpen): self
    {
        $this->dateOpen = $dateOpen;
        return $this;
    }
    public function getDateAccepted(): ?\DateTimeInterface
    {
        return $this->dateAccepted;
    }
    public function setDateAccepted(\DateTimeInterface $dateAccepted): self
    {
        $this->dateAccepted = $dateAccepted;
        return $this;
    }
    public function getDateDelivery(): ?\DateTimeInterface
    {
        return $this->dateDelivery;
    }
    public function setDateDelivery(\DateTimeInterface $dateDelivery): self
    {
        $this->dateDelivery = $dateDelivery;
        return $this;
    }
    public function getDateClosed(): ?\DateTimeInterface
    {
        return $this->dateClosed;
    }
    public function setDateClosed(\DateTimeInterface $dateClosed): self
    {
        $this->dateClosed = $dateClosed;
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
    public function getUser(): ?User
    {
        return $this->user;
    }
    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
    public function getPaymentMode(): ?PaymentMode
    {
        return $this->paymentMode;
    }
    public function setPaymentMode(?PaymentMode $paymentMode): self
    {
        $this->paymentMode = $paymentMode;
        return $this;
    }
    public function getDeliveryMode(): ?DeliveryMode
    {
        return $this->deliveryMode;
    }
    public function setDeliveryMode(?DeliveryMode $deliveryMode): self
    {
        $this->deliveryMode = $deliveryMode;
        return $this;
    }
    public function getStatus(): ?Statusorder
    {
        return $this->status;
    }
    public function setStatus(?Statusorder $status): self
    {
        $this->status = $status;
        return $this;
    }
    public function getDeliveryAdress(): ?Adress
    {
        return $this->deliveryAdress;
    }
    public function setDeliveryAdress(?Adress $deliveryAdress): self
    {
        $this->deliveryAdress = $deliveryAdress;
        return $this;
    }
    public function getPaymentAdress(): ?Adress
    {
        return $this->paymentAdress;
    }
    public function setPaymentAdress(?Adress $paymentAdress): self
    {
        $this->paymentAdress = $paymentAdress;
        return $this;
    }
    /**
     * @return Collection|Product[]
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }
    public function addProduct(Product $product): self
    {
        if (!$this->product->contains($product)) {
            $this->product[] = $product;
        }
        return $this;
    }
    public function removeProduct(Product $product): self
    {
        if ($this->product->contains($product)) {
            $this->product->removeElement($product);
        }
        return $this;
    }
    public function getFarmer(): ?User
    {
        return $this->farmer;
    }
    public function setFarmer(?User $farmer): self
    {
        $this->farmer = $farmer;
        return $this;
    }
    public function __toString() {
        return 'Commande n°'.$this->id;
    }
}
