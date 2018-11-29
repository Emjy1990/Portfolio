<?php
namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\BankingCoordinateRepository")
 */
class BankingCoordinate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=32)
     * @Assert\NotBlank(message="Un nom de banque est obligatoire")
     */
    private $name;
    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank(message="Une compte est obligatoire")
     */
    private $account;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bankingCoordinate")
     */
    private $user;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderPaymentFarmer", mappedBy="bankingCoordinate")
     */
    private $orderPaymentFarmers;
    public function __construct()
    {
        $this->orderPaymentFarmers = new ArrayCollection();
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
    public function getAccount(): ?string
    {
        return $this->account;
    }
    public function setAccount(string $account): self
    {
        $this->account = $account;
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
    /**
     * @return Collection|OrderPaymentFarmer[]
     */
    public function getOrderPaymentFarmers(): Collection
    {
        return $this->orderPaymentFarmers;
    }
    public function addOrderPaymentFarmer(OrderPaymentFarmer $orderPaymentFarmer): self
    {
        if (!$this->orderPaymentFarmers->contains($orderPaymentFarmer)) {
            $this->orderPaymentFarmers[] = $orderPaymentFarmer;
            $orderPaymentFarmer->setBankingCoordinate($this);
        }
        return $this;
    }
    public function removeOrderPaymentFarmer(OrderPaymentFarmer $orderPaymentFarmer): self
    {
        if ($this->orderPaymentFarmers->contains($orderPaymentFarmer)) {
            $this->orderPaymentFarmers->removeElement($orderPaymentFarmer);
            // set the owning side to null (unless already changed)
            if ($orderPaymentFarmer->getBankingCoordinate() === $this) {
                $orderPaymentFarmer->setBankingCoordinate(null);
            }
        }
        return $this;
    }
}
