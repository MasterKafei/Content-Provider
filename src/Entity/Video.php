<?php

namespace App\Entity;

use App\Repository\VideoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VideoRepository::class)]
class Video
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?string $name;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTime $creationDateTime;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTime $lastUpdateDateTime;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private array $games = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getCreationDateTime(): ?\DateTime
    {
        return $this->creationDateTime;
    }

    public function setCreationDateTime(?\DateTime $creationDateTime): self
    {
        $this->creationDateTime = $creationDateTime;
        return $this;
    }

    public function getGames(): array
    {
        return $this->games;
    }

    public function setGames(array $games): self
    {
        $this->games = $games;
        return $this;
    }

    public function getLastUpdateDateTime(): ?\DateTime
    {
        return $this->lastUpdateDateTime;
    }

    public function setLastUpdateDateTime(?\DateTime $lastUpdateDateTime): self
    {
        $this->lastUpdateDateTime = $lastUpdateDateTime;
        return $this;
    }
}
