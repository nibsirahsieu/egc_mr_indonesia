<?php 

namespace App\Request;

use PharIo\Manifest\Author;

final class PostRequest
{
    public function __construct(
        private string $title, private string $slug, private ?string $summary, private string $content,
        private ?string $metaTitle, private ?string $metaDescription, private ?int $headerImageId, private array $sectorIds,
        private int $typeId, private ?int $fileId, private ?\DateTimeImmutable $publishedAt, private ?string $author, 
        private ?int $thumbnailId, private ?int $status
    )
    {
        
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
     * Get the value of headerImageId
     */ 
    public function getHeaderImageId(): ?int
    {
        return $this->headerImageId;
    }

    /**
     * Get the value of typeId
     */ 
    public function getTypeId(): int
    {
        return $this->typeId;
    }

    /**
     * Get the value of publishedAt
     */ 
    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    /**
     * Get the value of fileId
     */ 
    public function getFileId(): ?int
    {
        return $this->fileId;
    }

    /**
     * Get the value of author
     */ 
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * Get the value of coverId
     */ 
    public function getThumbnailId(): ?int
    {
        return $this->thumbnailId;
    }

    /**
     * Get the value of sectorIds
     * @return array<int>
     */ 
    public function getSectorIds(): array
    {
        return $this->sectorIds;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }
}
