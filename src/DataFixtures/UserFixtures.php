<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $usersData=[
            ['id'=>1, 'name'=>'kevin', 'roles'=>['ROLE_USER'], 'avatar'=>'img/banniere.jpg', 'email'=>'kevin@hotmail.com', 'password'=>'kevin12345'],
            ['id'=>2, 'name'=>'julie', 'roles'=>['ROLE_ADMIN'], 'avatar'=>'img/banniere2.jpg', 'email'=>'julie@hotmail.com', 'password'=>'julie12345'],
            
        ];


        foreach($usersData as $userData){
            $user = new User();
            $user->setName($userData['name']);
            $user->setRoles($userData['roles']);
            $user->setEmail($userData['email']);
            $user->setAvatar($userData['avatar']);
            $password = $this->hasher->hashPassword($user, $userData['password']);
            $user->setPassword($password);

            $manager->persist($user);
        }

        

        $manager->flush();
    }
}
