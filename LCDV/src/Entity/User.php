<?php
namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Role;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * @ORM\Table(name="app_users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank(message="Un pseudo est obligatoire pour pouvoir vous inscrire")
     * * @Assert\Length(
     *      min = 2,
     *      max = 25,
     *      minMessage = "Your username must be at least {{ limit }} characters long",
     *      maxMessage = "Your username cannot be longer than {{ limit }} characters")
     */
    private $username;
    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 2,
     *      max = 64,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters")
     */
    private $firstname;
    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 2,
     *      max = 64,
     *      minMessage = "Your lastname must be at least {{ limit }} characters long",
     *      maxMessage = "Your lastname cannot be longer than {{ limit }} characters")
     */
    private $lastname;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     * @Assert\NotBlank()
     */
    private $email;
    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank()
     */
    private $password;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please, upload the product brochure as a jpeg file.")
     * @Assert\File(mimeTypes={ "image/jpeg" })
     */
    private $image;
    /**
     * @ORM\Column(type="string", length=35)
     */
    private $phone;
    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     */
    private $date;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="user", orphanRemoval=true)
     */
    private $product;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Adress", mappedBy="user", orphanRemoval=true)
     */
    private $adress;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Command", mappedBy="user")
     */
    private $command;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderPaymentFarmer", mappedBy="user", orphanRemoval=true)
     */
    private $orderPaymentFarmer;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BankingCoordinate", mappedBy="user",orphanRemoval=true)
     */
    private $bankingCoordinate;
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $money;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Command", mappedBy="farmer")
     */
    private $commandfarmer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Solde;
    public function __construct()
    {
        $this->product = new ArrayCollection();
        $this->adress = new ArrayCollection();
        $this->command = new ArrayCollection();
        $this->orderPaymentFarmer = new ArrayCollection();
        $this->bankingCoordinate = new ArrayCollection();
        $this->date = new \DateTime();
        $this->commandfarmer = new ArrayCollection();
    }
    public function getId()
    {
        return $this->id;
    }
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }
    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }
    public function getLastname(): ?string
    {
        return $this->lastname;
    }
    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }
    public function getSalt()
   {
      // you *may* need a real salt depending on your encoder
      // see section on salt below
      return null;
   }
    public function getPassword(): ?string
    {
        return $this->password;
    }
    public function eraseCredentials()
   {
   }
   /** @see \Serializable::serialize() */
   public function serialize()
   {
      return serialize(array(
         $this->id,
         $this->username,
         $this->password,
         // see section on salt below
         // $this->salt,
      ));
   }
   /** @see \Serializable::unserialize() */
   public function unserialize($serialized)
   {
      list (
         $this->id,
         $this->username,
         $this->password,
         // see section on salt below
         // $this->salt
         ) = unserialize($serialized, array('allowed_classes' => false));
      }
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }
    public function getPhone(): ?string
    {
        return $this->phone;
    }
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
        return $this;
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
    public function getRoles()
      {
         return array($this->getRole()->getCode());
      }
    public function getRole(): ?Role
    {
        return $this->role;
    }
    public function setRole(?Role $role): self
    {
        $this->role = $role;
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
            $product->setUser($this);
        }
        return $this;
    }
    public function removeProduct(Product $product): self
    {
        if ($this->product->contains($product)) {
            $this->product->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getUser() === $this) {
                $product->setUser(null);
            }
        }
        return $this;
    }
    /**
     * @return Collection|Adress[]
     */
    public function getAdress(): Collection
    {
        return $this->adress;
    }
    public function addAdress(Adress $adress): self
    {
        if (!$this->adress->contains($adress)) {
            $this->adress[] = $adress;
            $adress->setUser($this);
        }
        return $this;
    }
    public function removeAdress(Adress $adress): self
    {
        if ($this->adress->contains($adress)) {
            $this->adress->removeElement($adress);
            // set the owning side to null (unless already changed)
            if ($adress->getUser() === $this) {
                $adress->setUser(null);
            }
        }
        return $this;
    }
    /**
     * @return Collection|Command[]
     */
    public function getCommand(): Collection
    {
        return $this->command;
    }
    public function addCommand(Command $command): self
    {
        if (!$this->command->contains($command)) {
            $this->command[] = $command;
            $command->setUser($this);
        }
        return $this;
    }
    public function removeCommand(Command $command): self
    {
        if ($this->command->contains($command)) {
            $this->command->removeElement($command);
            // set the owning side to null (unless already changed)
            if ($command->getUser() === $this) {
                $command->setUser(null);
            }
        }
        return $this;
    }
    /**
     * @return Collection|OrderPaymentFarmer[]
     */
    public function getOrderPaymentFarmer(): Collection
    {
        return $this->orderPaymentFarmer;
    }
    public function addOrderPaymentFarmer(OrderPaymentFarmer $orderPaymentFarmer): self
    {
        if (!$this->orderPaymentFarmer->contains($orderPaymentFarmer)) {
            $this->orderPaymentFarmer[] = $orderPaymentFarmer;
            $orderPaymentFarmer->setUser($this);
        }
        return $this;
    }
    public function removeOrderPaymentFarmer(OrderPaymentFarmer $orderPaymentFarmer): self
    {
        if ($this->orderPaymentFarmer->contains($orderPaymentFarmer)) {
            $this->orderPaymentFarmer->removeElement($orderPaymentFarmer);
            // set the owning side to null (unless already changed)
            if ($orderPaymentFarmer->getUser() === $this) {
                $orderPaymentFarmer->setUser(null);
            }
        }
        return $this;
    }
    /**
     * @return Collection|BankingCoordinate[]
     */
    public function getBankingCoordinate(): Collection
    {
        return $this->bankingCoordinate;
    }
    public function addBankingCoordinate(BankingCoordinate $bankingCoordinate): self
    {
        if (!$this->bankingCoordinate->contains($bankingCoordinate)) {
            $this->bankingCoordinate[] = $bankingCoordinate;
            $bankingCoordinate->setUser($this);
        }
        return $this;
    }
    public function removeBankingCoordinate(BankingCoordinate $bankingCoordinate): self
    {
        if ($this->bankingCoordinate->contains($bankingCoordinate)) {
            $this->bankingCoordinate->removeElement($bankingCoordinate);
            // set the owning side to null (unless already changed)
            if ($bankingCoordinate->getUser() === $this) {
                $bankingCoordinate->setUser(null);
            }
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
    public function getUsername(): ?string
    {
        return $this->username;
    }
    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }
    public function getMoney(): ?float
    {
        return $this->money;
    }
    public function setMoney(?float $money): self
    {
        $this->money = $money;
        return $this;
    }
    /**
     * @return Collection|Command[]
     */
    public function getCommandfarmer(): Collection
    {
        return $this->commandfarmer;
    }
    public function addCommandfarmer(Command $commandfarmer): self
    {
        if (!$this->commandfarmer->contains($commandfarmer)) {
            $this->commandfarmer[] = $commandfarmer;
            $commandfarmer->setFarmer($this);
        }
        return $this;
    }
    public function removeCommandfarmer(Command $commandfarmer): self
    {
        if ($this->commandfarmer->contains($commandfarmer)) {
            $this->commandfarmer->removeElement($commandfarmer);
            // set the owning side to null (unless already changed)
            if ($commandfarmer->getFarmer() === $this) {
                $commandfarmer->setFarmer(null);
            }
        }
        return $this;
    }

    public function __toString() {
    return $this->username;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getSolde(): ?int
    {
        return $this->Solde;
    }

    public function setSolde(?int $Solde): self
    {
        $this->Solde = $Solde;

        return $this;
    }
}
