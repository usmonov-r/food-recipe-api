<?php

namespace App\Controller;

use App\Entity\Recipe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Component\Recipe\RecipeFactory;

class  RecipeCreateAction extends AbstractController{

    public function __construct(
        private  EntityManagerInterface $entityManager,
        private readonly RecipeFactory $recipeFactory,
        
    ){}

    public function  __invoke(Recipe $data): Recipe{

        $recipe = $this->recipeFactory->create(
            $data->getTitle(), $data->getDescription(), 
            $data->getIngredients(), $data->getCreatedAt(), 
            $data->getCategories());
        
        $this->entityManager->persist($recipe);
        $this->entityManager->flush();
        
        return $recipe;

    }
}