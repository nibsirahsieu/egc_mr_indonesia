<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\UniqueConstraint(name: 'post_slug_unique', fields: ['slug'])]
#[ORM\Index(name: 'post_published_at_id_idx', columns: ['published_at', 'id'])]
class Post
{
    use TimestampableEntity;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 700, nullable: true)]
    private ?string $summary = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $publishedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $metaTitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $metaDescription = null;

    /**
     * @var Collection<int, Sector>
     */
    #[ORM\ManyToMany(targetEntity: Sector::class)]
    private Collection $sectors;

    #[ORM\ManyToOne]
    private ?FileUploaded $headerImage = null;

    #[ORM\Column(enumType: PostStatus::class, type: Types::INTEGER)]
    private ?PostStatus $status = null;

    #[ORM\ManyToOne]
    private ?PostType $type = null;

    #[ORM\ManyToOne]
    private ?FileUploaded $file = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $author = null;

    #[ORM\ManyToOne]
    private ?FileUploaded $thumbnail = null;

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

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
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

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): static
    {
        $this->summary = $summary;

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

    public function getHeaderImage(): ?FileUploaded
    {
        return $this->headerImage;
    }

    public function setHeaderImage(?FileUploaded $headerImage): static
    {
        $this->headerImage = $headerImage;

        return $this;
    }

    public function getStatus(): ?PostStatus
    {
        return $this->status;
    }

    public function setStatus(PostStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getType(): ?PostType
    {
        return $this->type;
    }

    public function setType(?PostType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getFile(): ?FileUploaded
    {
        return $this->file;
    }

    public function setFile(?FileUploaded $file): static
    {
        $this->file = $file;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getThumbnail(): ?FileUploaded
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?FileUploaded $thumbnail): static
    {
        $this->thumbnail = $thumbnail;

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
