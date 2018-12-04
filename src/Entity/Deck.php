<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeckRepository")
 */
class Deck
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DeckCard", mappedBy="deck", orphanRemoval=true)
     */
    private $deckCards;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Gauntlet", mappedBy="deck")
     */
    private $gauntlets;

    /**
     * @ORM\Column(type="text")
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Game", mappedBy="opposingDeck")
     */
    private $games;

    public function __construct()
    {
        $this->deckCards = new ArrayCollection();
        $this->gauntlets = new ArrayCollection();
        $this->games = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|DeckCard[]
     */
    public function getDeckCards(): Collection
    {
        return $this->deckCards;
    }

    public function addDeckCard(DeckCard $deckCard): self
    {
        if (!$this->deckCards->contains($deckCard)) {
            $this->deckCards[] = $deckCard;
            $deckCard->setDeck($this);
        }

        return $this;
    }

    public function removeDeckCard(DeckCard $deckCard): self
    {
        if ($this->deckCards->contains($deckCard)) {
            $this->deckCards->removeElement($deckCard);
            // set the owning side to null (unless already changed)
            if ($deckCard->getDeck() === $this) {
                $deckCard->setDeck(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Gauntlet[]
     */
    public function getGauntlets(): Collection
    {
        return $this->gauntlets;
    }

    public function addGauntlet(Gauntlet $gauntlet): self
    {
        if (!$this->gauntlets->contains($gauntlet)) {
            $this->gauntlets[] = $gauntlet;
            $gauntlet->setDeck($this);
        }

        return $this;
    }

    public function removeGauntlet(Gauntlet $gauntlet): self
    {
        if ($this->gauntlets->contains($gauntlet)) {
            $this->gauntlets->removeElement($gauntlet);
            // set the owning side to null (unless already changed)
            if ($gauntlet->getDeck() === $this) {
                $gauntlet->setDeck(null);
            }
        }

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->setOpposingDeck($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->contains($game)) {
            $this->games->removeElement($game);
            // set the owning side to null (unless already changed)
            if ($game->getOpposingDeck() === $this) {
                $game->setOpposingDeck(null);
            }
        }

        return $this;
    }
}
