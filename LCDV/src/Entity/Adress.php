<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repository\AdressRepository")
 */
class Adress
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Un numéro est obligatoire")
     * @Assert\Type(
     *     type="integer",
     *     message="La valeur {{ value }} n'est pas valide{{ type }}."
     *)
     */
    private $number;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Une rue est obligatoire")
     *
     */
    private $street;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $building;
    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     */
    private $etage;
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Un code postal est obligatoire")
     * @Assert\Length(max="5", min="5", minMessage="Code postal invalide", maxMessage="Code postal invalide")
     *
     */
    private $cp;
    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank(message="Une ville est obligatoire")
     */
    private $city;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="adress")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Command", mappedBy="paymentAdress")
     */
    private $commands;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Command", mappedBy="deliveryAdress")
     */
     private $deliveryCommand;

    public function __construct()
    {
        $this->commands = new ArrayCollection();
        $this->deliveryAdress = new ArrayCollection();
    }
    public function getId()
    {
        return $this->id;
    }
    public function getNumber(): ?int
    {
        return $this->number;
    }
    public function setNumber(int $number): self
    {
        $this->number = $number;
        return $this;
    }
    public function getStreet(): ?string
    {
        return $this->street;
    }
    public function setStreet(string $street): self
    {
        $this->street = $street;
        return $this;
    }
    public function getBuilding(): ?string
    {
        return $this->building;
    }
    public function setBuilding(?string $building): self
    {
        $this->building = $building;
        return $this;
    }
    public function getEtage(): ?int
    {
        return $this->etage;
    }
    public function setEtage(?int $etage): self
    {
        $this->etage = $etage;
        return $this;
    }
    public function getCp(): ?int
    {
        return $this->cp;
    }
    public function setCp(int $cp): self
    {
        $this->cp = $cp;
        return $this;
    }
    public function getCity(): ?string
    {
        return $this->city;
    }
    public function setCity(string $city): self
    {
        $this->city = $city;
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
     * @return Collection|Command[]
     */
    public function getCommands(): Collection
    {
        return $this->commands;
    }
    public function addCommand(Command $command): self
    {
        if (!$this->commands->contains($command)) {
            $this->commands[] = $command;
            $command->setDeliveryAdress($this);
        }
        return $this;
    }
    public function removeCommand(Command $command): self
    {
        if ($this->commands->contains($command)) {
            $this->commands->removeElement($command);
            // set the owning side to null (unless already changed)
            if ($command->getDeliveryAdress() === $this) {
                $command->setDeliveryAdress(null);
            }
        }
        return $this;
    }

    /**
     * Set the value of Id
     *
     * @param mixed id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set the value of Commands
     *
     * @param mixed commands
     *
     * @return self
     */
    public function setCommands($commands)
    {
        $this->commands = $commands;

        return $this;
    }

    /**
     * Get the value of Delivery Command
     *
     * @return mixed
     */
    public function getDeliveryCommand()
    {
        return $this->deliveryCommand;
    }

    /**
     * Set the value of Delivery Command
     *
     * @param mixed deliveryCommand
     *
     * @return self
     */
    public function setDeliveryCommand($deliveryCommand)
    {
        $this->deliveryCommand = $deliveryCommand;

        return $this;
    }

}
