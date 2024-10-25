<?php

namespace App\Component\Recipe;

use App\Entity\Recipe;
use DateTime;
use DateTimeImmutable;
use App\Entity\Category;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Clock\DatePoint;
use DateTimeZone;

class RecipeFactory{

    
    public function create(
        string $title,
        string $description,
        array $ingredients, 
        ?DateTimeImmutable $createdAt, 
        Collection $categories, 
         ): Recipe{
        
        $recipe = new Recipe();
        $recipe->setTitle($title);
        $recipe->setDescription($description);
        $recipe->setIngredients($ingredients);
        $recipe->setCreatedAt(new DatePoint(timezone: new DateTimeZone("Asia/Seoul")));
        foreach ($categories as $category) {
            $recipe->addCategory($category); // Each element must be a Category instance
        }
        
        return  $recipe;
    }
}
