<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CardReferenceTypeRepository")
 */
class CardReferenceType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CardReference", mappedBy="type", orphanRemoval=true)
     */
    private $cardReferences;

    public function __construct()
    {
        $this->cardReferences = new ArrayCollection();
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

    /**
     * @return Collection|CardReference[]
     */
    public function getCardReferences(): Collection
    {
        return $this->cardReferences;
    }

    public function addCardReference(CardReference $cardReference): self
    {
        if (!$this->cardReferences->contains($cardReference)) {
            $this->cardReferences[] = $cardReference;
            $cardReference->setType($this);
        }

        return $this;
    }

    public function removeCardReference(CardReference $cardReference): self
    {
        if ($this->cardReferences->contains($cardReference)) {
            $this->cardReferences->removeElement($cardReference);
            // set the owning side to null (unless already changed)
            if ($cardReference->getType() === $this) {
                $cardReference->setType(null);
            }
        }

        return $this;
    }
}
