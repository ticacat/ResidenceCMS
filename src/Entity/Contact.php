<?php




namespace App\Entity;

use App\Entity\Traits\EntityIdTrait;
use App\Entity\Traits\EntityTimestampableTrait;
use App\Repository\CustomerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 */
class Contact
{


    use EntityIdTrait;
    use EntityTimestampableTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $fullname;


    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=false)
     */
    private $telefon;


    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=false, nullable=true)
     * @Assert\Email()
     */
    private $email;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $content;


    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $legal;


    /**
     * @ORM\Column(type="string", length=255, options={"default": "pending"})
     */
    private $state = 'unseen';



    /**
     * @return string
     */
    public function getFullname(): string
    {
        return $this->fullname;
    }

    /**
     * @param string $fullname
     */
    public function setFullname(string $fullname): void
    {
        $this->fullname = $fullname;
    }

    /**
     * @return string
     */
    public function getTelefon(): string
    {
        return $this->telefon;
    }

    /**
     * @param string $telefon
     */
    public function setTelefon(string $telefon): void
    {
        $this->telefon = $telefon;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     */
    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getLegal()
    {
        return $this->legal;
    }

    /**
     * @param mixed $legal
     */
    public function setLegal($legal): void
    {
        $this->legal = $legal;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }
}
