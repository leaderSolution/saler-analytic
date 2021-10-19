<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\VisitRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\CommercialAddVisitController;
use App\Controller\CommercialEditVisitController;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=VisitRepository::class)
 */
#[ApiResource(
    paginationItemsPerPage: 4,
    paginationMaximumItemsPerPage: 4,
    normalizationContext: ['groups' => ['read:collection']],
    denormalizationContext: ['groups' => ['write:Visit']],
    collectionOperations: [
        'add' => [
            'method' => 'POST',
            'path' => '/visits/commercial',
            'controller' => CommercialAddVisitController::class
        ]
    ],
    itemOperations: [
        'put',
        'patch' => [
            'method' => 'PATCH',
            'path' => '/visits/commercial/edit/{id}',
            'controller' => CommercialEditVisitController::class,
        ],
        'delete',
        'get' => [
            'normalization_context' => [ 'groups' => ['read:collection', 'read:item', 'read:Visit']]
        ],
    ],
),
ApiFilter(SearchFilter::class, properties:['id' => 'exact', 'title' => 'partial'])    
    ]
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

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="visits")
     */
    #[Groups(['read:item', 'read:collection'])]
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $backgroundColor;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $borderColor;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $textColor;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $allDay;

    /**
     * @ORM\ManyToOne(targetEntity=Address::class, inversedBy="visits", cascade={"persist"})
     */
    #[ Valid() ]
    private $address;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $active;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": false})
     */
    private $isDone;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
    }
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    public function setBackgroundColor(?string $backgroundColor): self
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    public function getBorderColor(): ?string
    {
        return $this->borderColor;
    }

    public function setBorderColor(?string $borderColor): self
    {
        $this->borderColor = $borderColor;

        return $this;
    }

    public function getTextColor(): ?string
    {
        return $this->textColor;
    }

    public function setTextColor(?string $textColor): self
    {
        $this->textColor = $textColor;

        return $this;
    }

    public function getAllDay(): ?bool
    {
        return $this->allDay;
    }

    public function setAllDay(?bool $allDay): self
    {
        $this->allDay = $allDay;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getIsDone(): ?bool
    {
        return $this->isDone;
    }

    public function setIsDone(?bool $isDone): self
    {
        $this->isDone = $isDone;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
