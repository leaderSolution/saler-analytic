<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\VisitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * @ORM\Entity(repositoryClass=VisitRepository::class)
 */
#[ApiResource(
    normalizationContext: ['groups' => ['read:collection']],
    denormalizationContext: ['groups' => ['write:Visit']],
    itemOperations: [
        'put',
        'delete',
        'get' => [
            'normalization_context' => [ 'groups' => ['read:collection', 'read:item', 'read:Visit']]
        ]
        ],
)]
class Visit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['read:collection'])]
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[
        Groups(['read:collection', 'write:Visit']),
        Length(min: 5)
     ]
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Groups(['read:collection', 'write:Visit'])]
    private $startTime;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Groups(['read:collection', 'write:Visit'])]
    private $endTime;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="visits", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    #[
        Groups(['read:item', 'write:Visit']),
        Valid()
     ]
    private $client;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

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
}
