<?php 

namespace App\View;

use App\Entity\PostStatus;

final class PostListView
{
    public function __construct(private int $id, private string $category, private string $title, private string $slug, private ?string $author, private ?\DateTimeImmutable $publishedAt, private PostStatus $status)
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
     * Get the value of type
     */ 
    public function getCategory(): string
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
     * Get the value of publishedAt
     */ 
    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    /**
     * Get the value of status
     */ 
    public function getStatus(): PostStatus
    {
        return $this->status;
    }

    /**
     * Get the value of slug
     */ 
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Get the value of author
     */ 
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function isDraft(): bool
    {
        return $this->status === PostStatus::DRAFT;
    }
}
