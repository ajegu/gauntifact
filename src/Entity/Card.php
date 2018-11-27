<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CardRepository")
 */
class Card implements Translatable
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
    private $gameId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CardType", inversedBy="cards")
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     *
     * @Gedmo\Translatable
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Gedmo\Translatable
     */
    private $text;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     */
    private $miniImage;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Gedmo\Translatable
     */
    private $largeImage;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     */
    private $inGameImage;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $illustrator;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CardRarity", inversedBy="cards")
     */
    private $rarity;

    /**
     * @ORM\Column(type="integer")
     */
    private $attack;

    /**
     * @ORM\Column(type="integer")
     */
    private $hitPoints;

    /**
     * @ORM\Column(type="integer")
     */
    private $manaCost;

    /**
     * @ORM\Column(type="integer")
     */
    private $goldCost;

    /**
     * @ORM\Column(type="integer")
     */
    private $armor;

    /**
     * @ORM\Column(type="boolean")
     */
    private $blue;

    /**
     * @ORM\Column(type="boolean")
     */
    private $black;

    /**
     * @ORM\Column(type="boolean")
     */
    private $green;

    /**
     * @ORM\Column(type="boolean")
     */
    private $red;

    /**
     * @ORM\Column(type="integer")
     */
    private $charges;

    /**
     * @ORM\Column(type="boolean")
     */
    private $crossLane;

    /**
     * @ORM\Column(type="boolean")
     */
    private $quick;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CardSubType", inversedBy="cards")
     */
    private $subType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CardSet", inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cardSet;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\CardReference")
     */
    private $refs;

    /**
     * @Gedmo\Locale
     */
    private $locale;

    public function __construct()
    {
        $this->attack = 0;
        $this->hitPoints = 0;
        $this->manaCost = 0;
        $this->goldCost = 0;
        $this->armor = 0;
        $this->blue = false;
        $this->black = false;
        $this->green = false;
        $this->red = false;
        $this->charges = 0;
        $this->crossLane = false;
        $this->quick = false;
        $this->refs = new ArrayCollection();
    }

    /**
     * @param $locale
     * @return CardSet
     */
    public function setTranslatableLocale($locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getGameId(): ?int
    {
        return $this->gameId;
    }

    /**
     * @param int $gameId
     * @return Card
     */
    public function setGameId(int $gameId): self
    {
        $this->gameId = $gameId;

        return $this;
    }

    /**
     * @return CardType|null
     */
    public function getType(): ?CardType
    {
        return $this->type;
    }

    /**
     * @param CardType|null $type
     * @return Card
     */
    public function setType(?CardType $type): self
    {
        $this->type = $type;

        return $this;
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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getMiniImage(): ?string
    {
        return $this->miniImage;
    }

    public function setMiniImage(?string $miniImage): self
    {
        $this->miniImage = $miniImage;

        return $this;
    }

    public function getLargeImage(): ?string
    {
        return $this->largeImage;
    }

    public function setLargeImage(?string $largeImage): self
    {
        $this->largeImage = $largeImage;

        return $this;
    }

    public function getInGameImage(): ?string
    {
        return $this->inGameImage;
    }

    public function setInGameImage(?string $inGameImage): self
    {
        $this->inGameImage = $inGameImage;

        return $this;
    }

    public function getIllustrator(): ?string
    {
        return $this->illustrator;
    }

    public function setIllustrator(?string $illustrator): self
    {
        $this->illustrator = $illustrator;

        return $this;
    }

    public function getRarity(): ?CardRarity
    {
        return $this->rarity;
    }

    public function setRarity(?CardRarity $rarity): self
    {
        $this->rarity = $rarity;

        return $this;
    }

    public function getAttack(): ?int
    {
        return $this->attack;
    }

    public function setAttack(?int $attack): self
    {
        $this->attack = $attack;

        return $this;
    }

    public function getHitPoints(): ?int
    {
        return $this->hitPoints;
    }

    public function setHitPoints(?int $hitPoints): self
    {
        $this->hitPoints = $hitPoints;

        return $this;
    }

    public function getManaCost(): ?int
    {
        return $this->manaCost;
    }

    public function setManaCost(?int $manaCost): self
    {
        $this->manaCost = $manaCost;

        return $this;
    }

    public function getGoldCost(): ?int
    {
        return $this->goldCost;
    }

    public function setGoldCost(?int $goldCost): self
    {
        $this->goldCost = $goldCost;

        return $this;
    }

    public function getArmor(): ?int
    {
        return $this->armor;
    }

    public function setArmor(?int $armor): self
    {
        $this->armor = $armor;

        return $this;
    }

    public function getBlue(): ?bool
    {
        return $this->blue;
    }

    public function setBlue(bool $blue): self
    {
        $this->blue = $blue;

        return $this;
    }

    public function getBlack(): ?bool
    {
        return $this->black;
    }

    public function setBlack(bool $black): self
    {
        $this->black = $black;

        return $this;
    }

    public function getGreen(): ?bool
    {
        return $this->green;
    }

    public function setGreen(bool $green): self
    {
        $this->green = $green;

        return $this;
    }

    public function getRed(): ?bool
    {
        return $this->red;
    }

    public function setRed(bool $red): self
    {
        $this->red = $red;

        return $this;
    }

    public function getCharges(): ?int
    {
        return $this->charges;
    }

    public function setCharges(?int $charges): self
    {
        $this->charges = $charges;

        return $this;
    }

    public function getCrossLane(): ?bool
    {
        return $this->crossLane;
    }

    public function setCrossLane(bool $crossLane): self
    {
        $this->crossLane = $crossLane;

        return $this;
    }

    public function getQuick(): ?bool
    {
        return $this->quick;
    }

    public function setQuick(bool $quick): self
    {
        $this->quick = $quick;

        return $this;
    }

    public function getSubType(): ?CardSubType
    {
        return $this->subType;
    }

    public function setSubType(?CardSubType $subType): self
    {
        $this->subType = $subType;

        return $this;
    }

    public function getCardSet(): ?CardSet
    {
        return $this->cardSet;
    }

    public function setCardSet(?CardSet $cardSet): self
    {
        $this->cardSet = $cardSet;

        return $this;
    }

    /**
     * @return Collection|CardReference[]
     */
    public function getRefs(): Collection
    {
        return $this->refs;
    }

    public function addRef(CardReference $ref): self
    {
        if (!$this->refs->contains($ref)) {
            $this->refs[] = $ref;
        }

        return $this;
    }

    public function removeRef(CardReference $ref): self
    {
        if ($this->refs->contains($ref)) {
            $this->refs->removeElement($ref);
        }

        return $this;
    }
}
