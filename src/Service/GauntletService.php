<?php
/**
 * Created by PhpStorm.
 * User: prestasic10
 * Date: 28/11/2018
 * Time: 14:16
 */

namespace App\Service;


use App\Entity\Gauntlet;
use App\Exception\GauntletNotNullException;
use Doctrine\ORM\EntityManagerInterface;

class GauntletService
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
     * @param Gauntlet $gauntlet
     * @throws GauntletNotNullException
     */
    public function create(Gauntlet $gauntlet)
    {
        if ($gauntlet->getUser() === null) {
            throw new GauntletNotNullException("L'utilisateur ne peut pas être null");
        }

        if ($gauntlet->getDeck() === null) {
            throw new GauntletNotNullException("Le deck ne peut pas être null");
        }

        // On calcule le numéro de l'affrontement
        $gauntlets = $this->manager->getRepository(Gauntlet::class)
            ->findBy([
                'user' => $gauntlet->getUser()
            ]);

        $number = count($gauntlets) + 1;
        $gauntlet->setNumber($number);

        $this->manager->persist($gauntlet);
        $this->manager->flush();
    }
}