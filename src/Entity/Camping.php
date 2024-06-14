<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity]
#[ORM\Table(name: "camping")]
class Camping implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private $id;

    #[ORM\Column(type: "string", length: 255)]
    private $nomCamping;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private $email;

    #[ORM\Column(type: "string", length: 14, unique: true)]
    private $siret;

    #[ORM\Column(type: "string", length: 255)]
    private $password;

    #[ORM\Column(type: "boolean")]
    private $rgpdAccepted;

    #[ORM\Column(type: "boolean")]
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

    // Implémentez la méthode getRoles() requise par UserInterface
    public function getRoles(): array
    {
        // En tant que rôle de base, vous pouvez retourner ['ROLE_USER']
        // Adaptez cela selon vos besoins
        return ['ROLE_USER'];
    }

    // Implémentez la méthode eraseCredentials() requise par UserInterface
    public function eraseCredentials()
    {
        // Effacez ici toute donnée sensible temporaire
    }

    // Implémentez la méthode getPassword() requise par PasswordAuthenticatedUserInterface
    public function getPassword(): ?string
    {
        return $this->password;
    }

    // Implémentez la méthode getUserIdentifier() requise par UserInterface
    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}
