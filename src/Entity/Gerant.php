<?php

namespace App\Entity;

use App\Entity\Personne;
use App\Repository\GerantRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name:"Gerant")]
#[ORM\Entity(repositoryClass: GerantRepository::class)]
class Gerant extends Personne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
