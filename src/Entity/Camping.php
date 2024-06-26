<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CampingRepository")
 * @ORM\Table(name="CAMPING")
 */
class Camping implements UserInterface, PasswordAuthenticatedUserInterface
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
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="integer")
     */
    private $siret;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $map;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $mdpVacancier;
 
    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @ORM\OneToMany(targetEntity=Structure::class, mappedBy="camping", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $structures;

    // Ajoutez vos constructeurs, getters et setters ici

    // Méthodes de l'interface UserInterface
    public function getId(): ?int
    {
        return $this->id;
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

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials(): void
    {
        // Si vous stockez des données temporaires et sensibles, nettoyez-les ici
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getSiret(): ?int
    {
        return $this->siret;
    }

    public function setSiret(int $siret): self
    {
        $this->siret = $siret;

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


    public function getMdpVacancier(): ?string
    {
        return $this->mdpVacancier;
    }

    public function setMdpVacancier(string $mdpVacancier): self
    {
        $this->mdpVacancier = $mdpVacancier;

        return $this;
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
