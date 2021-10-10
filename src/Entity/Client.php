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

    
    public function __construct()
    {
        $this->visits = new ArrayCollection();
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

   
}
