<?php 

namespace App\View;

readonly class RecentCaseStudyView
{
    public function __construct(public int $id, public string $slug, public string $title, public \DateTimeImmutable $publishedAt, private string $headerImage, private ?string $hash)
    {
    }

    public function thumbnail(): array
    {
        return [
            'image' => $this->headerImage,
            'hash' => $this->hash ?? ''
        ];
    }
}
