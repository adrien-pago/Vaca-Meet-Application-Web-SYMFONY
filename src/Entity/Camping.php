<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CampingRepository")
 */
class Camping implements PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomCamping;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=14, unique=true)
     */
    private $siret;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $rgpdAccepted;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

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

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): self
    {
        $this->siret = $siret;

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

    public function getRgpdAccepted(): ?bool
    {
        return $this->rgpdAccepted;
    }

    public function setRgpdAccepted(bool $rgpdAccepted): self
    {
        $this->rgpdAccepted = $rgpdAccepted;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    // Implémentation des méthodes de l'interface PasswordAuthenticatedUserInterface

    public function eraseCredentials()
    {
        // Si vous stockez des données sensibles temporairement, effacez-les ici
    }
}
