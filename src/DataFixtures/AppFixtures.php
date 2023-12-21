<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $user = new User();
        $user->setEmail('nico13sanna@gmail.com')
            ->setFirstname('Nicolas')
            ->setLastname('SANNA')
            ->setPseudo('Nico13')
            ->setPassword(password_hash('Altarius@abc@123', PASSWORD_DEFAULT))
            ->setCreatedAt(new \DateTimeImmutable())
            ->setIsVerified(true)
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);

        $manager->flush();
    }
}
