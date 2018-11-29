<?php
namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\DeliveryModeRepository")
 */
class DeliveryMode
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=32)
     * @Assert\NotBlank(message="Un mode de livraison est obligatoire")
     */
    private $name;
    /**
     * @ORM\Column(type="integer")
     */
    private $Delay;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Command", mappedBy="deliveryMode")
     */
    private $commands;
    public function __construct()
    {
        $this->commands = new ArrayCollection();
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
    public function getDelay(): ?int
    {
        return $this->Delay;
    }
    public function setDelay(int $Delay): self
    {
        $this->Delay = $Delay;
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
            $command->setDeliveryMode($this);
        }
        return $this;
    }
    public function removeCommand(Command $command): self
    {
        if ($this->commands->contains($command)) {
            $this->commands->removeElement($command);
            // set the owning side to null (unless already changed)
            if ($command->getDeliveryMode() === $this) {
                $command->setDeliveryMode(null);
            }
        }
        return $this;
    }
}
