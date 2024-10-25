<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Delete;
use ApiPlarform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\RecipeCreateAction;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[ApiResource(
    operations:[
        new Get(),
        new GetCollection(),
        new Delete(),
        new Post(
            uriTemplate: 'food-recipe',
            controller: RecipeCreateAction :: class,
            name: 'custom-food-recipe',
        ), 
    ],
    paginationItemsPerPage: 20,
    normalizationContext:['groups' => 'recipe:read'],       
    denormalizationContext:['groups' => 'recipe:write']
    )]
#[Groups(['recipe:read'])]
#[ApiFilter(SearchFilter:: class, properties: [
    'id' => 'exact',
    'title' => 'start',

])]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['recipe:write', 'user:read'])]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['recipe:write', 'user:read'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    #[Groups(['recipe:write'])]
    private ?array $ingredients = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: true)]  // Foreign key can be nullable
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[Groups(['recipe:write'])]
    private ?User $author = null; // ???

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['recipe:write'])]
    private ?MediaObject $image = null;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getIngredients(): ?array
    {
        return $this->ingredients;
    }

    public function setIngredients(?array $ingredients): static
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }


    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;
        return $this;
    }
   

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getImage(): ?MediaObject
    {
        return $this->image;
    }

    public function setImage(?MediaObject $image): static
    {
        $this->image = $image;

        return $this;
    }
}
