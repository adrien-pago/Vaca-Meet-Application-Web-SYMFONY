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
    private $idStructure;

    #[ORM\ManyToOne(targetEntity: 'App\Entity\Camping', inversedBy: 'structures')]
    #[ORM\JoinColumn(name: 'idCamping', referencedColumnName: 'id_camping', nullable: false)]
    private $camping;
    
    #[ORM\Column(type: 'string', length: 50)]
    private $libelle;

    #[ORM\Column(type: 'integer')]
    private $nbStructure;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private $etatStructure;

    // Getters and setters
    public function getIdStructure(): ?int
    {
        return $this->idStructure;
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
        return $this->nbStructure;
    }

    public function setNbStructure(int $nbStructure): self
    {
        $this->nbStructure = $nbStructure;

        return $this;
    }

    public function getEtatStructure(): ?string
    {
        return $this->etatStructure;
    }

    public function setEtatStructure(?string $etatStructure): self
    {
        $this->etatStructure = $etatStructure;

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
