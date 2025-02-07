<?php

namespace App\Entity;

use App\Repository\CaseStudyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: CaseStudyRepository::class)]
#[ORM\UniqueConstraint(name: 'case_study_slug_unique', fields: ['slug'])]
#[ORM\Index(name: 'case_study_published_at_id_idx', columns: ['published_at', 'id'])]
class CaseStudy
{
    use TimestampableEntity;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $client = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $issue = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $solution = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $approach = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $recommendation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $engagementRoi = null;

    #[ORM\ManyToOne]
    private ?FileUploaded $image = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $publishedAt = null;

    #[ORM\Column(nullable: true, enumType: PostStatus::class)]
    private ?PostStatus $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $metaTitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $metaDescription = null;

    /**
     * @var Collection<int, Sector>
     */
    #[ORM\ManyToMany(targetEntity: Sector::class)]
    private Collection $sectors;

    public function __construct()
    {
        $this->sectors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getClient(): ?string
    {
        return $this->client;
    }

    public function setClient(?string $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getIssue(): ?string
    {
        return $this->issue;
    }

    public function setIssue(?string $issue): static
    {
        $this->issue = $issue;

        return $this;
    }

    public function getSolution(): ?string
    {
        return $this->solution;
    }

    public function setSolution(?string $solution): static
    {
        $this->solution = $solution;

        return $this;
    }

    public function getApproach(): ?string
    {
        return $this->approach;
    }

    public function setApproach(?string $approach): static
    {
        $this->approach = $approach;

        return $this;
    }

    public function getRecommendation(): ?string
    {
        return $this->recommendation;
    }

    public function setRecommendation(?string $recommendation): static
    {
        $this->recommendation = $recommendation;

        return $this;
    }

    public function getEngagementRoi(): ?string
    {
        return $this->engagementRoi;
    }

    public function setEngagementRoi(?string $engagementRoi): static
    {
        $this->engagementRoi = $engagementRoi;

        return $this;
    }

    public function getImage(): ?FileUploaded
    {
        return $this->image;
    }

    public function setImage(?FileUploaded $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeImmutable $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getStatus(): ?PostStatus
    {
        return $this->status;
    }

    public function setStatus(?PostStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    public function setMetaTitle(?string $metaTitle): static
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(?string $metaDescription): static
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * @return Collection<int, Sector>
     */
    public function getSectors(): Collection
    {
        return $this->sectors;
    }

    public function addSector(Sector $sector): static
    {
        if (!$this->sectors->contains($sector)) {
            $this->sectors->add($sector);
        }

        return $this;
    }

    public function removeSector(Sector $sector): static
    {
        $this->sectors->removeElement($sector);

        return $this;
    }

    /**
     * sync sectors
     *
     * @param array|Collection<int, Sector> $newSectors
     * @return static
     */
    public function syncSectors($newSectors): static
    {
        if (!($newSectors instanceof Collection)) {
            $newSectors = new ArrayCollection($newSectors);
        }

        foreach ($this->getSectors() as $sector) {
            if (!$newSectors->contains($sector)) {
                $this->removeSector($sector);
            }
        }

        foreach ($newSectors as $sector) {
            $this->addSector($sector);
        }

        return $this;
    }
}
