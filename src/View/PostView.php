<?php 

namespace App\View;

use App\Common\IdName;
use App\Entity\PostStatus;

final class PostView
{
    public function __construct(
        private int $id, private IdName $category, private string $title, private string $slug, private ?string $summary, private string $content,
        private ?string $metaTitle, private ?string $metaDescription, private ?string $author, private ?\DateTimeImmutable $publishedAt, 
        private array $sectors, private UploadView $headerImage, private ?UploadView $file, private ?UploadView $thumbnail, private PostStatus $status,
        private IdName $postType, private \DateTime $updatedAt
    )
    {   
    }

    /**
     * Get the value of id
     */ 
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the value of category
     */ 
    public function getCategory(): IdName
    {
        return $this->category;
    }

    /**
     * Get the value of title
     */ 
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get the value of slug
     */ 
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Get the value of summary
     */ 
    public function getSummary(): ?string
    {
        return $this->summary;
    }

    /**
     * Get the value of content
     */ 
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Get the value of metaTitle
     */ 
    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    /**
     * Get the value of metaDescription
     */ 
    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    /**
     * Get the value of author
     */ 
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * Get the value of publishedAt
     */ 
    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    /**
     * Get the value of headerImage
     */ 
    public function getHeaderImage(): UploadView
    {
        return $this->headerImage;
    }

    /**
     * Get the value of file
     */ 
    public function getFile(): ?UploadView
    {
        return $this->file;
    }

    /**
     * Get the value of cover
     */ 
    public function getThumbnail(): ?UploadView
    {
        return $this->thumbnail;
    }

    /**
     * Get the value of status
     */ 
    public function getStatus(): PostStatus
    {
        return $this->status;
    }

    /**
     * Get the value of sectors
     * @return array<IdName>
     */ 
    public function getSectors(): array
    {
        return $this->sectors;
    }

    /**
     * Get the value of postType
     */ 
    public function getPostType(): IdName
    {
        return $this->postType;
    }

    /**
     * Get the value of updatedAt
     */ 
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function getSectorIds(): array
    {
        $ids = [];
        foreach ($this->sectors as $sector) {
            $ids[] = $sector->id;
        }

        return $ids;
    }

    public function isPublished(): bool
    {
        return $this->status === PostStatus::PUBLISHED;
    }

    public function isWhitepaper(): bool
    {
        return $this->postType->name === 'Whitepaper';
    }
}
