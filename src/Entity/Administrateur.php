<?php

namespace App\Entity;

use App\Entity\Personne;
use App\Repository\AdministrateurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name:"Administrateur")]
#[ORM\Entity(repositoryClass: AdministrateurRepository::class)]
class Administrateur extends Personne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->nomUtilisateur;
    }
}
