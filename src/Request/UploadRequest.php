<?php

namespace App\Request;

final class UploadRequest
{
    public function __construct(private string $name, private string $originalName, private string $mimeType, private int $size, private string $extension, private ?int $purpose)
    {
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
     * Get the value of mimeType
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * Get the value of size
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Get the value of extension
     */ 
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * Get the value of purpose
     */ 
    public function getPurpose(): ?int
    {
        return $this->purpose;
    }
}

