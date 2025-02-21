<?php

namespace App\Entity;

use App\Repository\HeaderFooterScriptRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HeaderFooterScriptRepository::class)]
class HeaderFooterScript
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $headerScript = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $footerScript = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeaderScript(): ?string
    {
        return $this->headerScript;
    }

    public function setHeaderScript(?string $headerScript): static
    {
        $this->headerScript = $headerScript;

        return $this;
    }

    public function getFooterScript(): ?string
    {
        return $this->footerScript;
    }

    public function setFooterScript(?string $footerScript): static
    {
        $this->footerScript = $footerScript;

        return $this;
    }
}
