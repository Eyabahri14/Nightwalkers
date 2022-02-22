<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder){
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('ons@test.com');
        $user->setFirstname('ons');
        $user->setLastname('ons');
        $user->setRoles(['ROLE_SUPER_ADMIN']);
        $user->setPassword($this->passwordEncoder->encodePassword($user,'ons'));
        $manager->persist($user);
        $manager->flush();
    }
}
