<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use App\Controller\ProfileController;
use ApiPlatform\Core\Action\NotFoundAction;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * @ORM\Table(name="`users`")
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
#[  UniqueEntity('email'),
    ApiResource(
    collectionOperations:[
        'profile' => [
            'pagination_enabled' => false,
            'path' => '/profile',
            'method' => 'get',
            'controller' => ProfileController::class,
            'read' => false,
            'security' => 'is_granted("ROLE_USER")'
        ]
    ],
    itemOperations:[
        'get' => [
            'controller' => NotFoundAction::class,
            'openapi_context' => ['summary' => 'hidden'],
            'read' => false,
            'output' => false
        ]
    ],
    normalizationContext: ['groups' => ['read:User']],

)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['read:User'])]
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    #[Groups(['read:User']),
      Assert\Email(message: 'The email {{ value }} is not a valid email.'),
    ]
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    #[Groups(['read:User'])]
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Visit::class, mappedBy="user")
     */
    private $visits;

    /**
     * @var string
     */
    private $fullName;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;
    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cin;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActive;

    /**
     * @ORM\ManyToOne(targetEntity=Address::class, inversedBy="visits", cascade={"persist"})
     */
    #[ Valid() ]
    private $address;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbVisitsDay;


    /**
     * @ORM\OneToMany(targetEntity=Client::class, mappedBy="user")
     */
    private $clients;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $turnoverTarget;

    /**
     * @ORM\OneToMany(targetEntity=Leave::class, mappedBy="user", orphanRemoval=true)
     */
    private $leaves;

    public function __construct()
    {
        $this->visits = new ArrayCollection();
        $this->clients = new ArrayCollection();
        $this->leaves = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Visit[]
     */
    public function getVisits(): Collection
    {
        return $this->visits;
    }

    public function addVisit(Visit $visit): self
    {
        if (!$this->visits->contains($visit)) {
            $this->visits[] = $visit;
            $visit->setUser($this);
        }

        return $this;
    }

    public function removeVisit(Visit $visit): self
    {
        if ($this->visits->removeElement($visit)) {
            // set the owning side to null (unless already changed)
            if ($visit->getUser() === $this) {
                $visit->setUser(null);
            }
        }

        return $this;
    }

    public function getFullName(): ?string
    {
        return  $this->getFirstname().' '.$this->getLastname();
    }

    public function setFullName(): self
    {
        $this->fullName = $this->getFirstname().' '.$this->getLastname();

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(?string $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Address|null
     */
    public function getAddress(): ?Address
    {
        return $this->address;
    }

    /**
     * @param Address|null $address
     * @return User
     */
    public function setAddress(?Address $address): self
    {
        $this->address = $address;
        return $this;
    }

    public function getNbVisitsDay(): ?int
    {
        return $this->nbVisitsDay;
    }

    public function setNbVisitsDay(?int $nbVisitsDay): self
    {
        $this->nbVisitsDay = $nbVisitsDay;

        return $this;
    }

    /**
     * @return Collection|Client[]
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients[] = $client;
            $client->setUser($this);
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getUser() === $this) {
                $client->setUser(null);
            }
        }

        return $this;
    }

    public function getTurnoverTarget(): ?float
    {
        return $this->turnoverTarget;
    }

    public function setTurnoverTarget(?float $turnoverTarget): self
    {
        $this->turnoverTarget = $turnoverTarget;

        return $this;
    }

    /**
     * @return Collection|Leave[]
     */
    public function getLeaves(): Collection
    {
        return $this->leaves;
    }

    public function addLeaf(Leave $leaf): self
    {
        if (!$this->leaves->contains($leaf)) {
            $this->leaves[] = $leaf;
            $leaf->setUser($this);
        }

        return $this;
    }

    public function removeLeaf(Leave $leaf): self
    {
        if ($this->leaves->removeElement($leaf)) {
            // set the owning side to null (unless already changed)
            if ($leaf->getUser() === $this) {
                $leaf->setUser(null);
            }
        }

        return $this;
    }

}
