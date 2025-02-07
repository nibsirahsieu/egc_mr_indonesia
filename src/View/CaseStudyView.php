<?php 

namespace App\View;

use App\Entity\PostStatus;

readonly class CaseStudyView
{
    public function __construct(
        public int $id, public string $title, public string $slug, public string $client, public string $issue, 
        public string $solution, public string $approach, public string $recommendation, public string $engagementRoi,
        public ?string $metaTitle, public ?string $metaDescription, public ?UploadView $image, public iterable $sectors, 
        public PostStatus $status, public ?\DateTimeImmutable $publishedAt, public \DateTime $updatedAt
    )
    {        
    }

    public function isPublished(): bool
    {
        return $this->status === PostStatus::PUBLISHED;
    }

    public function getSectorIds(): array
    {
        $ids = [];
        foreach ($this->sectors as $sector) {
            $ids[] = $sector->id;
        }

        return $ids;
    }
}
