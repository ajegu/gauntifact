<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CardReferenceRepository")
 */
class CardReference
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $count;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CardReferenceType", inversedBy="cardReferences")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Card")
     * @ORM\JoinColumn(nullable=false)
     */
    private $card;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getType(): ?CardReferenceType
    {
        return $this->type;
    }

    public function setType(?CardReferenceType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCard(): ?Card
    {
        return $this->card;
    }

    public function setCard(?Card $card): self
    {
        $this->card = $card;

        return $this;
    }
}
