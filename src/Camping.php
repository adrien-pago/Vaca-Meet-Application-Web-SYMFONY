<?PHP
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CampingRepository")
 */
class Camping implements UserInterface
{
    // ... (autres propriétés comme $id, $nom, etc.)

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     */
    private $hashMdp;

    // ... (éventuellement d'autres propriétés)

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->hashMdp;
    }

    public function setPassword(string $hashMdp): self
    {
        $this->hashMdp = $hashMdp;
        return $this;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials()
    {
        // Ici, vous pouvez effacer les données sensibles temporairement stockées.
        // Si vous ne stockez pas de telles données, vous pouvez laisser cette méthode vide.
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
   // ... (implémentez les autres méthodes requises par UserInterface si nécessaire)
}

?>