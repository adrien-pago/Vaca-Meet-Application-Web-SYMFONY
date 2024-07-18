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
    private $id_planning;

    #[ORM\ManyToOne(targetEntity: 'App\Entity\Camping', inversedBy: 'planning')]
    #[ORM\JoinColumn(name: 'idCamping', referencedColumnName: 'id_camping', nullable: false)]
    private $camping;

    #[ORM\Column(type: 'string', length: 100)]
    private $libelle_activity;

    #[ORM\Column(type: 'datetime')]
    private $date_debut;

    #[ORM\Column(type: 'datetime')]
    private $date_fin;

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
        return $this->id_planning;
    }

    public function getLibelleActivity(): ?string
    {
        return $this->libelle_activity;
    }

    public function setLibelleActivity(string $libelleActivity): self
    {
        $this->libelle_activity = $libelleActivity;
        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->date_debut = $dateDebut;
        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->date_fin = $dateFin;
        return $this;
    }

    // Autres méthodes nécessaires
}
