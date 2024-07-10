<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: 'App\Repository\PlanningRepository')]
#[ORM\Table(name: 'PLANNING')]
class Planning
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $idPlanning;

    #[ORM\ManyToOne(targetEntity: 'App\Entity\Camping')]
    #[ORM\JoinColumn(name: 'idCamping', referencedColumnName: 'id_camping', nullable: false)]
    private $camping;

    #[ORM\Column(type: 'string', length: 100)]
    private $libelleActivity;

    #[ORM\Column(type: 'datetime')]
    private $dateDebut;

    #[ORM\Column(type: 'datetime')]
    private $dateFin;

    // Getters et Setters
    public function getCamping(): ?Camping
    {
        return $this->camping;
    }

    public function setCamping(?Camping $camping): self
{
    $this->camping = $camping;
    return $this;
}

    public function getIdPlanning(): ?int
    {
        return $this->idPlanning;
    }

    public function getLibelleActivity(): ?string
    {
        return $this->libelleActivity;
    }

    public function setLibelleActivity(string $libelleActivity): self
    {
        $this->libelleActivity = $libelleActivity;
        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;
        return $this;
    }

    // Autres méthodes nécessaires
}
