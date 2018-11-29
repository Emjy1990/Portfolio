<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StatusOrderPaymentFarmerRepository")
 */
class StatusOrderPaymentFarmer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderPaymentFarmer", mappedBy="status")
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
            $orderPaymentFarmer->setStatus($this);
        }

        return $this;
    }

    public function removeOrderPaymentFarmer(OrderPaymentFarmer $orderPaymentFarmer): self
    {
        if ($this->orderPaymentFarmers->contains($orderPaymentFarmer)) {
            $this->orderPaymentFarmers->removeElement($orderPaymentFarmer);
            // set the owning side to null (unless already changed)
            if ($orderPaymentFarmer->getStatus() === $this) {
                $orderPaymentFarmer->setStatus(null);
            }
        }

        return $this;
    }
}
