<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Unique;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\Length;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 */
#[
    ApiResource(),
    UniqueEntity('email'),
    UniqueEntity('codeUniq')
 ]
class Client
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[
        Groups(['read:Visit', 'write:Visit']),
        Length(min: 4)
      ]
    private $codeUniq;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[
        Groups(['write:Visit']),
        Assert\Email(message: 'The email {{ value }} is not a valid email.'),
        
      ]
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=Visit::class, mappedBy="client", orphanRemoval=true)
     */
    private $visits;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $designation;

    /**
     * @ORM\OneToMany(targetEntity=Goal::class, mappedBy="client", orphanRemoval=true, cascade={"persist"})
     */
    private $goals;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="clients")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $region;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $taxRegistrationNum;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $deadline;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $turnover;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isProspect;

    
    public function __construct()
    {
        $this->visits = new ArrayCollection();
        $this->goals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeUniq(): ?string
    {
        return $this->codeUniq;
    }

    public function setCodeUniq(string $codeUniq): self
    {
        $this->codeUniq = $codeUniq;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
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
            $visit->setClient($this);
        }

        return $this;
    }

    public function removeVisit(Visit $visit): self
    {
        if ($this->visits->removeElement($visit)) {
            // set the owning side to null (unless already changed)
            if ($visit->getClient() === $this) {
                $visit->setClient(null);
            }
        }

        return $this;
    }


    public function __toString(){
        return $this->getCodeUniq();
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }


    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(?string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    /**
     * @return Collection|Goals[]
     */
    public function getGoals(): Collection
    {
        return $this->goals;
    }

    public function addGoal(Goal $goal): self
    {
        if (!$this->goals->contains($goal)) {
            $this->goals[] = $goal;
            $goal->setClient($this);
        }

        return $this;
    }

    public function removeGoal(Goal $goal): self
    {
        if ($this->goals->removeElement($goal)) {
            // set the owning side to null (unless already changed)
            if ($goal->getClient() === $this) {
                $goal->setClient(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getTaxRegistrationNum(): ?string
    {
        return $this->taxRegistrationNum;
    }

    public function setTaxRegistrationNum(?string $taxRegistrationNum): self
    {
        $this->taxRegistrationNum = $taxRegistrationNum;

        return $this;
    }

    public function getDeadline(): ?int
    {
        return $this->deadline;
    }

    public function setDeadline(?int $deadline): self
    {
        $this->deadline = $deadline;

        return $this;
    }

    public function getTurnover(): ?float
    {
        return $this->turnover;
    }

    public function setTurnover(?float $turnover): self
    {
        $this->turnover = $turnover;

        return $this;
    }

    public function getIsProspect(): ?bool
    {
        return $this->isProspect;
    }

    public function setIsProspect(?bool $isProspect): self
    {
        $this->isProspect = $isProspect;

        return $this;
    }

   
}
