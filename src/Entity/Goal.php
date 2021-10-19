<?php

namespace App\Entity;

use App\Repository\GoalRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GoalRepository::class)
 */
class Goal
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $nbVisits;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $period;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="goal")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Client $client;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $createdAt;

    /**
     *
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbVisits(): ?int
    {
        return $this->nbVisits;
    }

    public function setNbVisits(?int $nbVisits): self
    {
        $this->nbVisits = $nbVisits;

        return $this;
    }

    public function getPeriod(): ?int
    {
        return $this->period;
    }

    public function setPeriod(?int $period): self
    {
        $this->period = $period;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
