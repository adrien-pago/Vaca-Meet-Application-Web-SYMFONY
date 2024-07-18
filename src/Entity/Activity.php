<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: 'App\Repository\ActivityRepository')]
#[ORM\Table(name: 'ACTIVITY')]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id_activity;

    #[ORM\ManyToOne(targetEntity: 'App\Entity\Camping', inversedBy: 'activities')]
    #[ORM\JoinColumn(name: 'idCamping', referencedColumnName: 'id_camping', nullable: false)]
    private $camping;

    #[ORM\Column(type: 'string', length: 50)]
    private $libelle;

    // Getters et setters
    public function getIdActivity(): ?int
    {
        return $this->id_activity;
    }

    public function getCamping(): ?Camping
    {
        return $this->camping;
    }

    public function setCamping(?Camping $camping): self
    {
        $this->camping = $camping;
        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * Retourne le nom du camping associé.
     *
     * @return string|null
     */
    public function getNomCamping(): ?string
    {
        return $this->camping ? $this->camping->getNomCamping() : null;
    }
}
