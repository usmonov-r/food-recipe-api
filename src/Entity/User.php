<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use App\Controller\UserCreateAction;
// #[UniqueEntity('email')]

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            security: "is_granted('ROLE_ADMIN') ||  object==user"
        ),
        new GetCollection(
            // security: "is_granted('ROLE_ADMIN')"
        ),
        new Delete(),
        new Post(
            uriTemplate: 'users/my',
            controller:  UserCreateAction:: class,
            name: 'User_Create'
        ),
        new Post(
            uriTemplate: 'users/auth',
            name: 'auth'
        ),
    ],
    paginationItemsPerPage: 10,
    normalizationContext: ['groups' => 'user:read'],
    denormalizationContext: ['groups'=> 'user:write'],
)]
#[ApiFilter(SearchFilter:: class, properties:[
    'id' => 'exact',
    'email' => 'partial',
])]
#[Groups(['user:read'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('recipe:read')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Email(message:"This {{ value }} is not a valid email")]
    #[Groups(['user:write', 'recipe:read'])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:write'])]
    private ?string $password = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    #[Groups(['user:write'])]
    private ?array $roles = ["ROLE_USER"];

    /**
     * @var Collection<int, Recipe>
     */
    #[ORM\OneToMany(targetEntity: Recipe::class, mappedBy: 'author')]
    #[Groups(['user:write'])]
    private Collection $recipes;

    /**
     * @var Collection<int, Recipe>
     */
    #[ORM\ManyToMany(targetEntity: Recipe::class)]
    #[Groups(['user:write'])]
    private Collection $favorites;

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
        $this->favorites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): static
    {   
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection<int, Recipe>
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): static
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes->add($recipe);
            $recipe->setAuthor($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): static
    {
        if ($this->recipes->removeElement($recipe)) {
            // set the owning side to null (unless already changed)
            if ($recipe->getAuthor() === $this) {
                $recipe->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Recipe>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Recipe $favorite): static
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites->add($favorite);
        }

        return $this;
    }

    public function removeFavorite(Recipe $favorite): static
    {
        $this->favorites->removeElement($favorite);

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
    
    public function eraseCredentials(): void
    {
    }
}
