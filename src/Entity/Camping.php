<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CampingRepository")
 * @ORM\Table(name="CAMPING")
 */
class Camping
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nomCamping;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $email;

    /**
     * @ORM\Column(type="integer")
     */
    private $numSiret;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $map;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $password;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tokenConfirm;

    /**
     * @ORM\Column(type="integer")
     */
    private $topUserConfirmed;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Structure", mappedBy="camping")
     */
    private $structures;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $mdpVacancier;

    // Ajouter ici vos constructeurs, getters et setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCamping(): ?string
    {
        return $this->nomCamping;
    }

    public function setNomCamping(string $nomCamping): self
    {
        $this->nomCamping = $nomCamping;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getNumSiret(): ?int
    {
        return $this->numSiret;
    }

    public function setNumSiret(int $numSiret): self
    {
        $this->numSiret = $numSiret;

        return $this;
    }

    public function getMap()
    {
        return $this->map;
    }

    public function setMap($map): self
    {
        $this->map = $map;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getTokenConfirm(): ?int
    {
        return $this->tokenConfirm;
    }

    public function setTokenConfirm(?int $tokenConfirm): self
    {
        $this->tokenConfirm = $tokenConfirm;

        return $this;
    }

    public function getTopUserConfirmed(): ?int
    {
        return $this->topUserConfirmed;
    }

    public function setTopUserConfirmed(int $topUserConfirmed): self
    {
        $this->topUserConfirmed = $topUserConfirmed;

        return $this;
    }

    public function getMdpVacancier(): ?string
    {
        return $this->mdpVacancier;
    }

    public function setMdpVacancier(string $mdpVacancier): self
    {
        $this->mdpVacancier = $mdpVacancier;

        return $this;
    }

    /**
     * @return Collection|Structure[]
     */
    public function getStructures()
    {
        return $this->structures;
    }

    public function addStructure(Structure $structure): self
    {
        if (!$this->structures->contains($structure)) {
            $this->structures[] = $structure;
            $structure->setCamping($this);
        }

        return $this;
    }

    public function removeStructure(Structure $structure): self
    {
        if ($this->structures->contains($structure)) {
            $this->structures->removeElement($structure);
            // set the owning side to null (unless already changed)
            if ($structure->getCamping() === $this) {
                $structure->setCamping(null);
            }
        }

        return $this;
    }
}
