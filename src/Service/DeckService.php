<?php
/**
 * Created by PhpStorm.
 * User: prestasic10
 * Date: 28/11/2018
 * Time: 12:43
 */

namespace App\Service;


use App\Entity\Card;
use App\Entity\Deck;
use App\Entity\DeckCard;
use App\Exception\CardNotFoundException;
use App\Utils\CArtifactDeckDecoder;
use Doctrine\ORM\EntityManagerInterface;

class DeckService
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * DeckService constructor.
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param $deckCode
     * @return Deck
     * @throws CardNotFoundException
     */
    public function createDeckFromCode($deckCode)
    {
        $params = explode('/', $deckCode);
        $code = $params[count($params) - 1];

        $deckData = CArtifactDeckDecoder::ParseDeck($code);

        $deck = new Deck();
        $deck->setCode($deckCode);

        if (trim($deckData['name']) !== '') {
            $deck->setName($deckData['name']);
        }

        $this->manager->persist($deck);

        // On traite les cartes de type Héro
        foreach ($deckData['heroes'] as $heroData) {
            $card = $this->manager->getRepository(Card::class)
                ->findOneBy(['gameId' => $heroData['id']]);

            if ($card === null) {
                throw new CardNotFoundException(
                    sprintf('La carte ID: %s n\'existe pas en base de données', $heroData['id']),
                    404
                );
            }

            $deckCard = new DeckCard();
            $deckCard->setDeck($deck)
                ->setCard($card)
                ->setTurn($heroData['turn'])
                ->setCount(1);

            $deck->addDeckCard($deckCard);

            $this->manager->persist($deckCard);
        }

        // On traite les autres cartes
        foreach ($deckData['cards'] as $cardData) {
            $card = $this->manager->getRepository(Card::class)
                ->findOneBy(['gameId' => $cardData['id']]);             

            if ($card === null) {
                throw new CardNotFoundException(
                    sprintf('La carte ID: %s n\'existe pas en base de données', $cardData['id']),
                    404
                );
            }

            $deckCard = new DeckCard();
            $deckCard->setDeck($deck)
                ->setCard($card)
                ->setCount($cardData['count']);

            $deck->addDeckCard($deckCard);

            $this->manager->persist($deckCard);
        }

        $this->manager->flush();

        return $deck;
    }
}