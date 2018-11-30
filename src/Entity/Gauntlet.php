<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GauntletRepository")
 */
class Gauntlet
{
    const STATUS_CURRENT = 'CURRENT';
    const STATUS_FINISH = 'FINISH';
    const STATUS_CONCEDED = 'CONCEDED';


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Timestampable(on="create")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Gedmo\Timestampable(on="update")
     */
    private $updated;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="gauntlets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GauntletType", inversedBy="gauntlets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Deck", inversedBy="gauntlets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $deck;

    /**
     * @ORM\Column(type="integer")
     */
    private $number;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Game", mappedBy="gauntlet", orphanRemoval=true)
     */
    private $games;

    public function __construct()
    {
        $this->status = self::STATUS_CURRENT;
        $this->games = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(?\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

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

    public function getType(): ?GauntletType
    {
        return $this->type;
    }

    public function setType(?GauntletType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDeck(): ?Deck
    {
        return $this->deck;
    }

    public function setDeck(?Deck $deck): self
    {
        $this->deck = $deck;

        return $this;
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
            $game->setGauntlet($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->contains($game)) {
            $this->games->removeElement($game);
            // set the owning side to null (unless already changed)
            if ($game->getGauntlet() === $this) {
                $game->setGauntlet(null);
            }
        }

        return $this;
    }

    /**
     * @param bool $includeDraw
     * @return Game[]
     */
    public function getGamesLost($includeDraw = true)
    {
        $games = [];
        foreach ($this->getGames() as $game) {
            if ($game->getStatus() === Game::STATUS_LOSE || ($includeDraw && $game->getStatus() === Game::STATUS_DRAW)) {
                $games[] = $game;
            }
        }

        return $games;
    }

    /**
     * @return Game[]
     */
    public function getGamesWon()
    {
        $games = [];
        foreach ($this->getGames() as $game) {
            if ($game->getStatus() === Game::STATUS_WIN) {
                $games[] = $game;
            }
        }

        return $games;
    }

    /**
     * @return bool
     */
    public function isPossibleToAddGame()
    {
        $addGame = true;
        if (count($this->getGames()) === 7 || count($this->getGamesWon()) === 5 || count($this->getGamesLost()) === 2) {
            $addGame = false;
        }

        return $addGame;
    }

    /**
     * @return bool
     */
    public function isLock()
    {
       return $this->getStatus() !== self::STATUS_CURRENT;
    }
}
