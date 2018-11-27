<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CardSubTypeRepository")
 */
class CardSubType implements Translatable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     *
     * @Gedmo\Translatable
     */
    private $name;

    /**
     * @Gedmo\Locale
     */
    private $locale;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Card", mappedBy="subType")
     */
    private $cards;

    public function __construct()
    {
        $this->cards = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return CardSubType
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param $locale
     * @return CardSubType
     */
    public function setTranslatableLocale($locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return Collection|Card[]
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(Card $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->cards[] = $card;
            $card->setSubType($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->cards->contains($card)) {
            $this->cards->removeElement($card);
            // set the owning side to null (unless already changed)
            if ($card->getSubType() === $this) {
                $card->setSubType(null);
            }
        }

        return $this;
    }
}
