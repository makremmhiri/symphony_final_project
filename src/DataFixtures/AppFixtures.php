<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Create admin user
        $admin = new User();
        $admin->setNomUser('Admin');
        $admin->setPrenomUser('System');
        $admin->setMail('admin@example.com');
        $admin->setTel('12345678');
        $admin->setMdp('admin123'); // Plain password
        
        $manager->persist($admin);

        // Create regular test user
        $user = new User();
        $user->setNomUser('Doe');
        $user->setPrenomUser('John');
        $user->setMail('john@example.com');
        $user->setTel('87654321');
        $user->setMdp('password123'); // Plain password
        
        $manager->persist($user);

        // Create a few more users
        $users = [
            ['Martin', 'Sophie', 'sophie@example.com', '11223344', 'sophie123'],
            ['Dubois', 'Pierre', 'pierre@example.com', '55667788', 'pierre123'],
            ['Bernard', 'Marie', 'marie@example.com', '99887766', 'marie123'],
            ['Moreau', 'Luc', 'luc@example.com', '44332211', 'luc123'],
            ['Lefebvre', 'Julie', 'julie@example.com', '66778899', 'julie123'],
        ];

        foreach ($users as $userData) {
            $user = new User();
            $user->setNomUser($userData[0]);
            $user->setPrenomUser($userData[1]);
            $user->setMail($userData[2]);
            $user->setTel($userData[3]);
            $user->setMdp($userData[4]); // Plain password
            
            $manager->persist($user);
        }

        $manager->flush();
    }
}