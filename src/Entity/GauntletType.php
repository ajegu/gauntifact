<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GauntletTypeRepository")
 */
class GauntletType implements Translatable
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
     * @ORM\OneToMany(targetEntity="App\Entity\Gauntlet", mappedBy="type", orphanRemoval=true)
     */
    private $gauntlets;

    public function __construct()
    {
        $this->gauntlets = new ArrayCollection();
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
     * @return GauntletType
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param $locale
     * @return GauntletType
     */
    public function setTranslatableLocale($locale): self
    {
        $this->locale = $locale;

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
            $gauntlet->setType($this);
        }

        return $this;
    }

    public function removeGauntlet(Gauntlet $gauntlet): self
    {
        if ($this->gauntlets->contains($gauntlet)) {
            $this->gauntlets->removeElement($gauntlet);
            // set the owning side to null (unless already changed)
            if ($gauntlet->getType() === $this) {
                $gauntlet->setType(null);
            }
        }

        return $this;
    }
}
