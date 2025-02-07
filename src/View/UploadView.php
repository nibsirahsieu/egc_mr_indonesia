<?php 

namespace App\View;

final class UploadView
{
    public function __construct(private int $id, private string $name, private string $originalName, private int $size, private string $mimeType, private string $relativePath, private ?string $hash)
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
     * Get the value of name
     */ 
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the value of originalName
     */ 
    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    /**
     * Get the value of size
     */ 
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Get the value of mimeType
     */ 
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * Get the value of relativePath
     */ 
    public function getRelativePath(): string
    {
        return $this->relativePath;
    }

    /**
     * Get the value of hash
     */ 
    public function getHash(): ?string
    {
        return $this->hash;
    }
}