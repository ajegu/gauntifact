<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserFixture constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $adminUser = new User();
        $adminUser->setEmail('admin@gmail.com')
            ->setPseudo('Admin')
            ->setRoles(['ROLE_ADMIN']);

        $adminUser->setPassword($this->passwordEncoder->encodePassword(
            $adminUser,
            '123456'
        ));
        $manager->persist($adminUser);

        $user = new User();
        $user->setEmail('jerho@live.fr')
            ->setPseudo('Jerho')
            ->setRoles(['ROLE_USER']);

        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            '123456'
        ));
        $manager->persist($user);

        $manager->flush();
    }
}
