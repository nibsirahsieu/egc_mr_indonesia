<?php 

namespace App\View;

use App\Entity\PostStatus;

readonly class CaseStudyListView
{
    public function __construct(
        public int $id, public string $title, public string $slug, public string $client, public ?\DateTimeImmutable $publishedAt, public PostStatus $status
    )
    {
    }

    public function isDraft(): bool
    {
        return $this->status === PostStatus::DRAFT;
    }
}
