<?php

namespace App\Entity;

use App\Repository\UserDetailsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserDetailsRepository::class)]
class UserDetails implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $First_name = null;

    #[ORM\Column(length: 255)]
    private ?string $Last_name = null;

    #[ORM\Column(length: 255)]
    private ?string $phoneNumber = null;

    #[ORM\OneToMany(mappedBy: 'creator_id', targetEntity: Products::class, orphanRemoval: true)]
    private Collection $creator;

    public function __construct()
    {
        $this->creator = new ArrayCollection();
    }

//    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: Products::class, orphanRemoval: true)]
//    private Collection $products;

//    public function __construct()
//    {
//        $this->products = new ArrayCollection();
//    }

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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->First_name;
    }

    public function setFirstName(string $First_name): static
    {
        $this->First_name = $First_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->Last_name;
    }

    public function setLastName(string $Last_name): static
    {
        $this->Last_name = $Last_name;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return Collection<int, Products>
     */
//    public function getProducts(): Collection
//    {
//        return $this->products;
//    }
//
//    public function addProduct(Products $product): static
//    {
//        if (!$this->products->contains($product)) {
//            $this->products->add($product);
//            $product->setCreator($this);
//        }
//
//        return $this;
//    }
//
//    public function removeProduct(Products $product): static
//    {
//        if ($this->products->removeElement($product)) {
//            // set the owning side to null (unless already changed)
//            if ($product->getCreator() === $this) {
//                $product->setCreator(null);
//            }
//        }
//
//        return $this;
//    }

/**
 * @return Collection<int, Products>
 */
public function getCreator(): Collection
{
    return $this->creator;
}

public function addCreator(Products $creator): static
{
    if (!$this->creator->contains($creator)) {
        $this->creator->add($creator);
        $creator->setCreatorId($this);
    }

    return $this;
}

public function removeCreator(Products $creator): static
{
    if ($this->creator->removeElement($creator)) {
        // set the owning side to null (unless already changed)
        if ($creator->getCreatorId() === $this) {
            $creator->setCreatorId(null);
        }
    }

    return $this;
}
}
