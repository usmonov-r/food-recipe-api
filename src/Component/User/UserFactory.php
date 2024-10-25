<?php

namespace App\Component\User;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Recipe;
use Doctrine\Common\Collections\ArrayCollection;
class  UserFactory{

    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ){}

    public function  create(
        string $email, 
        string $password,
        array $roles,
        ArrayCollection $recipes,
        ArrayCollection $favorites,
        ): User{
            $user = new User();
            $hashedPassword = $this->passwordHasher->hashPassword($user, $password);

            $user->setEmail($email);
            $user->setPassword($hashedPassword);
            $user->setRoles($roles);

            foreach($recipes as $recipe) {
                $user->addRecipes($recipe);
            }
            foreach($recipes as $recipe) {
                $user->addRecipes($recipe);
            }
            return $user;
        }
}
