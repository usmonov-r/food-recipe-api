<?php

namespace App\Controller;

use App\Entity\User;
use App\Component\User\UserFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class UserCreateAction extends AbstractController{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private readonly UserFactory $userFactory,
    ){}

    public function __invoke(User $data): User{
        $user = $this->userFactory->create(
            $data->getEmail(),
            $data->getPassword(),
            $data->getRoles(),
            $data->getRecipes(),
            $data->getFavorites(),
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }
}
