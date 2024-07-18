<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: 'App\Repository\StructureRepository')]
#[ORM\Table(name: 'STRUCTURE')]
class Structure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id_structure;

    #[ORM\ManyToOne(targetEntity: 'App\Entity\Camping', inversedBy: 'structures')]
    #[ORM\JoinColumn(name: 'idCamping', referencedColumnName: 'id_camping', nullable: false)]
    private $camping;

    #[ORM\Column(type: 'string', length: 50)]
    private $libelle;

    #[ORM\Column(type: 'integer')]
    private $nb_structure;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private $etat_structure;

    // Getters and setters
    public function getIdStructure(): ?int
    {
        return $this->id_structure;
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

    public function getNbStructure(): ?int
    {
        return $this->nb_structure;
    }

    public function setNbStructure(int $nbStructure): self
    {
        $this->nb_structure = $nbStructure;

        return $this;
    }

    public function getEtatStructure(): ?string
    {
        return $this->etat_structure;
    }

    public function setEtatStructure(?string $etatStructure): self
    {
        $this->etat_structure = $etatStructure;

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
