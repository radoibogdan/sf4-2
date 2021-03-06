<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;
    /**
     * L'autowiring est disponibles dans le constructeur des fixtures 
     * Dans la majorité des classes, on peut récupérer des services par autowiring uniquement dans le constructeur
     * php bin/console debug:autowiring
     * UserPasswordEncoderInterface - le service qui permet de hasher les mdp
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // Création de 10 utilisateurs classiques
        // pas besoin de setRole car par défaut c'est ROLE_USER
        for ($i = 0; $i < 10; $i++) {
            $user = new User();

            // Création du hash du mot de passe
            $hash = $this->passwordEncoder->encodePassword($user, 'user' . $i);

            $user
                ->setEmail('user' . $i . '@mail.org')
                ->setPassword($hash)
            ;
            $manager->persist($user);
        }

        // Création de 3 administrateurs
        for ($i = 0; $i < 3; $i++) {
            $admin = new User();

            // Création du hash du mot de passe
            $hash = $this->passwordEncoder->encodePassword($admin, 'admin' . $i);

            $admin
                ->setEmail('admin' . $i . '@mail.org')
                ->setPassword($hash)
                ->setRoles(['ROLE_ADMIN'])
            ;
            $manager->persist($admin);
        }

        $manager->flush();

    }
}
