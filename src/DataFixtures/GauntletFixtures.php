<?php

namespace App\DataFixtures;

use App\Entity\GauntletType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GauntletFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data = [
            'Expert - Constructed' => 'Expert - Construit',
            'Expert - Phantom Draft' => 'Expert - Fantôme Draft',
            'Expert - Keeper Draft' => 'Expert - Keeper Draft',
            'Casual - Constructed' => 'Occasionel - Construit',
            'Casual - Phantom Draft' => 'Occasionel - Fantôme Draft',
        ];

        foreach ($data as $en => $fr) {
            $gauntletType = new GauntletType();
            $gauntletType->setName($en);

            $manager->persist($gauntletType);
            $manager->flush();

            $gauntletType->setName($fr)
                ->setTranslatableLocale('fr');

            $manager->persist($gauntletType);
            $manager->flush();
        }
    }
}
