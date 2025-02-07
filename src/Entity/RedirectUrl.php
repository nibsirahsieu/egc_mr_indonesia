<?php

namespace App\Entity;

use App\Repository\RedirectUrlRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: RedirectUrlRepository::class)]
class RedirectUrl
{
    use TimestampableEntity;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 500, unique: true)]
    private ?string $oldUrl = null;

    #[ORM\Column(length: 500)]
    private ?string $newUrl = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOldUrl(): ?string
    {
        return $this->oldUrl;
    }

    public function setOldUrl(string $oldUrl): static
    {
        $this->oldUrl = $oldUrl;

        return $this;
    }

    public function getNewUrl(): ?string
    {
        return $this->newUrl;
    }

    public function setNewUrl(string $newUrl): static
    {
        $this->newUrl = $newUrl;

        return $this;
    }
}
