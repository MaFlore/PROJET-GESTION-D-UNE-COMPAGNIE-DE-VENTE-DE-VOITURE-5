<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class Sauvegarde
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id;

    #[ORM\Column(type: 'integer')]
    protected $creerPar;

    #[ORM\Column(type: 'date')]
    protected $creerLe;

    #[ORM\Column(type: 'integer', nullable: true)]
    protected $modifierPar;

    #[ORM\Column(type: 'date', nullable: true)]
    protected $modifierLe;

    #[ORM\Column(type: 'boolean')]
    protected $enable = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreerPar(): ?int
    {
        return $this->creerPar;
    }

    public function setCreerPar(int $creerPar): self
    {
        $this->creerPar = $creerPar;

        return $this;
    }

    public function getCreerLe(): ?\DateTimeInterface
    {
        return $this->creerLe;
    }

    public function setCreerLe(\DateTimeInterface $creerLe): self
    {
        $this->creerLe = $creerLe;

        return $this;
    }

    public function getModifierPar(): ?int
    {
        return $this->modifierPar;
    }

    public function setModifierPar(?int $modifierPar): self
    {
        $this->modifierPar = $modifierPar;

        return $this;
    }

    public function getModifierLe(): ?\DateTimeInterface
    {
        return $this->modifierLe;
    }

    public function setModifierLe(?\DateTimeInterface $modifierLe): self
    {
        $this->modifierLe = $modifierLe;

        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }
}
