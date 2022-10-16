<?php

namespace App\Entity;

use App\Repository\PersonneRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InheritanceType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: PersonneRepository::class)]
#[InheritanceType('JOINED')]
#[UniqueEntity(fields:"nomUtilisateur", message:"Ce nom d'utilisateur existe déjà dans la base de données")]

class Personne extends Sauvegarde implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id;

    #[ORM\Column(type: 'string', length: 255)]
    protected $nom;

    #[ORM\Column(type: 'string', length: 255)]
    protected $prenom;

    #[ORM\Column(type: 'string', length: 255)]
    protected $telephone;

    #[ORM\Column(type: 'string', length: 255)]
    protected $nomUtilisateur;

    #[ORM\Column(type: 'string', length: 255)]
    protected $password;

    #[ORM\Column(type: 'json')]
    protected $roles = [];


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getNomUtilisateur(): ?string
    {
        return $this->nomUtilisateur;
    }

    public function setNomUtilisateur(string $nomUtilisateur): self
    {
        $this->nomUtilisateur = $nomUtilisateur;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored in a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return string[]
     */
    public function getRoles(): array{

        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = ' ';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;

    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {

    }

    /**
     * Returns the identifier for this user (e.g. its username or email address).
     */
    public function getUserIdentifier(): int
    {
        return $this->id;
    }   

    public function __toString()
    {
        return $this->nomUtilisateur;
    }
    
}
