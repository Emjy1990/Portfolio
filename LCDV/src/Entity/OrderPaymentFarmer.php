<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderPaymentFarmerRepository")
 */
class OrderPaymentFarmer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date()
     */
    private $date;
    /**
     * @ORM\Column(type="integer")
     * @Assert\Type(
     *     type="integer",
     *
     * )
     * @Assert\NotBlank(message="Le montant est obligatoire")
     * @Assert\GreaterThan(
     *     value = 49,
     *     message = "Le montant doit être supérieur ou égal à cinquante euros"
     * )
     */
    private $amount;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="orderPaymentFarmer")
     */
    private $user;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\StatusOrderPaymentFarmer", inversedBy="orderPaymentFarmers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\BankingCoordinate", inversedBy="orderPaymentFarmers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bankingCoordinate;
    public function __construct()
    {
        $this->date = new \DateTime();
    }
    public function getId()
    {
        return $this->id;
    }
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }
    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }
    public function getAmount(): ?int
    {
        return $this->amount;
    }
    public function setAmount(int $amount): self
    {
        $this->amount = $amount;
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
    public function getStatus(): ?StatusOrderPaymentFarmer
    {
        return $this->status;
    }
    public function setStatus(?StatusOrderPaymentFarmer $status): self
    {
        $this->status = $status;
        return $this;
    }
    public function getBankingCoordinate(): ?BankingCoordinate
    {
        return $this->bankingCoordinate;
    }
    public function setBankingCoordinate(?BankingCoordinate $bankingCoordinate): self
    {
        $this->bankingCoordinate = $bankingCoordinate;
        return $this;
    }

}
