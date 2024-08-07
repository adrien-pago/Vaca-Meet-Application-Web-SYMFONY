<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups; // Importez Groups depuis le Serializer

#[ORM\Entity(repositoryClass: 'App\Repository\CampingRepository')]
#[ORM\Table(name: 'CAMPING')]
class Camping implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id_camping;

    #[ORM\Column(type: 'string', length: 50)]
    private $nom_camping;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private $email;

    #[ORM\Column(type: 'integer')]
    private $siret;

    #[ORM\Column(type: 'blob', nullable: true)]
    private $map;

    #[ORM\Column(type: 'string', length: 1000)]
    private $password;

    #[ORM\Column(type: 'string', length: 1000, nullable: true)]
    private $mdp_vacancier;

    #[ORM\Column(type: 'boolean')]
    private $is_verified = false;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\OneToMany(targetEntity: 'App\Entity\Structure', mappedBy: 'camping', orphanRemoval: true, cascade: ['persist', 'remove'])]
    private $structures;

    #[ORM\OneToMany(targetEntity: 'App\Entity\Activity', mappedBy: 'camping', orphanRemoval: true, cascade: ['persist', 'remove'])]
    private $activities;

    #[ORM\OneToMany(targetEntity: 'App\Entity\Planning', mappedBy: 'camping', orphanRemoval: true, cascade: ['persist', 'remove'])]
    private $planning;

    public function __construct()
    {
        $this->structures = new ArrayCollection();
        $this->activities = new ArrayCollection();
        $this->planning = new ArrayCollection();
    }

    public function getIdCamping(): ?int
    {
        return $this->id_camping;
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
        // Clean up temporary, sensitive data
    }

    public function isVerified(): bool
    {
        return $this->is_verified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->is_verified = $isVerified;
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
        return $this->mdp_vacancier;
    }

    public function setMdpVacancier(string $mdpVacancier): self
    {
        $this->mdp_vacancier = $mdpVacancier;
        return $this;
    }

    public function getNomCamping(): ?string
    {
        return $this->nom_camping;
    }

    public function setNomCamping(string $nomCamping): self
    {
        $this->nom_camping = $nomCamping;
        return $this;
    }

    /**
     * @return Collection|Structure[]
     */
    public function getStructures(): Collection
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
        if ($this->structures->removeElement($structure)) {
            if ($structure->getCamping() === $this) {
                $structure->setCamping(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|Activity[]
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): self
    {
        if (!$this->activities->contains($activity)) {
            $this->activities[] = $activity;
            $activity->setCamping($this);
        }
        return $this;
    }

    public function removeActivity(Activity $activity): self
    {
        if ($this->activities->removeElement($activity)) {
            if ($activity->getCamping() === $this) {
                $activity->setCamping(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|Planning[]
     */
    #[Groups(['camping'])] // Définir le groupe de sérialisation pour cette propriété
    public function getPlanning(): Collection
    {
        return $this->planning;
    }

    public function addPlanning(Planning $planning): self
    {
        if (!$this->planning->contains($planning)) {
            $this->planning[] = $planning;
            $planning->setCamping($this);
        }
        return $this;
    }

    public function removePlanning(Planning $planning): self
    {
        if ($this->planning->removeElement($planning)) {
            if ($planning->getCamping() === $this) {
                $planning->setCamping(null);
            }
        }
        return $this;
    }
}
