<?php

namespace App\DataFixtures;

use App\Entity\Card;
use App\Entity\CardRarity;
use App\Entity\CardReferenceType;
use App\Entity\CardSet;
use App\Entity\CardSubType;
use App\Entity\CardType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CardFixtures extends Fixture
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $this->loadCardsSet();
        $this->loadCardsRarity();
        $this->loadCardsSubType();
        $this->loadCardsType();
        $this->loadCardsReferenceType();
        $this->loadCards();
    }

    /**
     * @param $locale
     * @return bool|string
     */
    private function getLocaleCode($locale)
    {
        switch ($locale) {
            case 'english':
                $locale = 'en';
                break;
            case 'french':
                $locale = 'fr';
                break;
            case 'default':
                $locale = 'en';
                break;
            default:
                $locale = false;
        }

        return $locale;
    }

    /**
     *
     */
    private function loadCards()
    {
        $files = [];
        $files[] = __DIR__ . '/../../tmp/card_set_1.E991EF727CDCD9C8194209A9576C76A2E2A1AFB5.json';
        $files[] = __DIR__ . '/../../tmp/card_set_0.5DFBBB9F063A1D842DAD7190C2ACDC0E56DF8895.json';

        foreach ($files as $file) {
            $handle = fopen($file, 'r');
            $text = '';
            while (!feof($handle)) {
                $text .= fgets($handle);

            }

            $data = json_decode($text, true);
            fclose($handle);


            $cardSet = $this->manager->getRepository(CardSet::class)
                ->findOneBy(['name' => $data['card_set']['set_info']['name']['english']]);

            foreach ($data['card_set']['card_list'] as $cardData) {
                $card = new Card();
                $card->setCardSet($cardSet)
                    ->setGameId($cardData['card_id']);

                $this->manager->persist($card);
                $this->manager->flush();

                if (isset($cardData['card_type'])) {
                    $cardType = $this->manager->getRepository(CardType::class)
                        ->findOneBy(['name' => $cardData['card_type']]);
                    $card->setType($cardType);
                }

                if (isset($cardData['card_name'])) {
                    foreach ($cardData['card_name'] as $locale => $name) {
                        if ($this->getLocaleCode($locale) !== false) {
                            $card->setName($name)
                                ->setTranslatableLocale($this->getLocaleCode($locale));
                            $this->manager->persist($card);
                            $this->manager->flush();
                        }
                    }
                }

                if (isset($cardData['card_text'])) {
                    foreach ($cardData['card_text'] as $locale => $text) {
                        if ($this->getLocaleCode($locale) !== false) {
                            $card->setText($text)
                                ->setTranslatableLocale($this->getLocaleCode($locale));
                            $this->manager->persist($card);
                            $this->manager->flush();
                        }
                    }
                }

                if (isset($cardData['mini_image']['default'])) {
                    $card->setMiniImage($cardData['mini_image']['default']);
                }

                if (isset($cardData['large_image'])) {
                    foreach ($cardData['large_image'] as $locale => $largeImage) {
                        if ($this->getLocaleCode($locale) !== false) {
                            $card->setLargeImage($largeImage)
                                ->setTranslatableLocale($this->getLocaleCode($locale));
                            $this->manager->persist($card);
                            $this->manager->flush();
                        }
                    }
                }

                if (isset($cardData['ingame_image']['default'])) {
                    $card->setInGameImage($cardData['ingame_image']['default']);
                }

                if (isset($cardData['illustrator'])) {
                    $card->setIllustrator($cardData['illustrator']);
                }

                // On gère la rareté de la carte
                if (isset($cardData['rarity'])) {
                    $carteRarity = $this->manager->getRepository(CardRarity::class)
                        ->findOneBy(['name' => $cardData['rarity']]);

                } else {
                    $carteRarity = $this->manager->getRepository(CardRarity::class)
                        ->findOneBy(['name' => 'Basic']);
                }
                $card->setRarity($carteRarity);

                if (isset($cardData['is_blue'])) {
                    $card->setBlue(true);
                }

                if (isset($cardData['attack'])) {
                    $card->setAttack($cardData['attack']);
                }

                if (isset($cardData['hit_points'])) {
                    $card->setHitPoints($cardData['hit_points']);
                }

                if (isset($cardData['mana_cost'])) {
                    $card->setManaCost($cardData['mana_cost']);
                }

                if (isset($cardData['is_crosslane'])) {
                    $card->setCrossLane(true);
                }

                if (isset($cardData['charges'])) {
                    $card->setCharges($cardData['charges']);
                }

                if (isset($cardData['is_black'])) {
                    $card->setBlack(true);
                }

                if (isset($cardData['is_green'])) {
                    $card->setGreen(true);
                }

                if (isset($cardData['is_red'])) {
                    $card->setRed(true);
                }

                if (isset($cardData['armor'])) {
                    $card->setArmor($cardData['armor']);
                }

                if (isset($cardData['sub_type'])) {
                    $cardSubType = $this->manager->getRepository(CardSubType::class)
                        ->findOneBy(['name' => $cardData['sub_type']]);
                    $card->setSubType($cardSubType);
                }

                if (isset($cardData['gold_cost'])) {
                    $card->setGoldCost($cardData['gold_cost']);
                }

                if (isset($cardData['is_quick'])) {
                    $card->setQuick(true);
                }

                $this->manager->persist($card);
                $this->manager->flush();
            }
        }
    }

    /**
     * Import des types de référence entre les cartes
     */
    private function loadCardsReferenceType()
    {
        $data = [
            'includes',
            'passive_ability',
            'references',
            'active_ability',
        ];

        foreach ($data as $name) {
            $cardReferenceType = new CardReferenceType();
            $cardReferenceType->setName($name);

            $this->manager->persist($cardReferenceType);
        }

        $this->manager->flush();
    }

    /**
     * Import des types de carte
     */
    private function loadCardsType()
    {
        $data = [
            'Hero' => 'Héro',
            'Passive Ability' => 'Passif',
            'Spell' => 'Sort',
            'Creep' => 'Creep',
            'Ability' => 'Capacité',
            'Improvement' => 'Amélioration',
            'Item' => 'Objet',
            'Stronghold' => 'Bastion',
            'Pathing' => 'Chemin'
        ];

        foreach ($data as $en => $fr) {
            $cardType = new CardType();
            $cardType->setName($en);

            $this->manager->persist($cardType);
            $this->manager->flush();

            $cardType->setName($fr)
                ->setTranslatableLocale('fr');

            $this->manager->persist($cardType);
            $this->manager->flush();
        }
    }

    /**
     * Import des sous-types de carte
     */
    private function loadCardsSubType()
    {
        $data = [
            'Accessory' => 'Accessoire',
            'Weapon' => 'Arme',
            'Armor' => 'Armure',
            'Creep' => 'Creep',
            'Consumable' => 'Consommable',
            'Deed' => 'Action'
        ];

        foreach ($data as $en => $fr) {
            $cardSubType = new CardSubType();
            $cardSubType->setName($en);

            $this->manager->persist($cardSubType);
            $this->manager->flush();

            $cardSubType->setName($fr)
                ->setTranslatableLocale('fr');

            $this->manager->persist($cardSubType);
            $this->manager->flush();
        }
    }

    /**
     * Import de la rareté des cartes
     */
    private function loadCardsRarity()
    {
        $data = [
            'Basic' => 'Basique',
            'Common' => 'Commune',
            'Rare' => 'Rare',
            'Uncommon' => 'Peu commune'
        ];

        foreach ($data as $en => $fr) {
            $cardRarity = new CardRarity();
            $cardRarity->setName($en);

            $this->manager->persist($cardRarity);
            $this->manager->flush();

            $cardRarity->setName($fr)
                ->setTranslatableLocale('fr');

            $this->manager->persist($cardRarity);
            $this->manager->flush();
        }
    }

    /**
     * Import des ensembles de cartes
     */
    private function loadCardsSet()
    {
        $data = [
            'Call to Arms' => 'Appel aux armes',
            'Base Set' => 'Ensemble de base'
        ];

        foreach ($data as $en => $fr) {
            $cardSet = new CardSet();
            $cardSet->setName($en);

            $this->manager->persist($cardSet);
            $this->manager->flush();

            $cardSet->setName($fr)
                ->setTranslatableLocale('fr');

            $this->manager->persist($cardSet);
            $this->manager->flush();
        }
    }
}
