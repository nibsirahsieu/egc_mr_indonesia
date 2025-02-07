<?php 

namespace App\Request;

use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

readonly class CaseStudyRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $title,

        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $slug,

        #[Assert\NotBlank]
        #[Assert\Length(max: 500)]
        public string $client,

        #[Assert\NotBlank]
        public string $issue,

        #[Assert\NotBlank]
        public string $solution,

        #[Assert\NotBlank]
        public string $approach,

        #[Assert\NotBlank]
        public string $recommendation,

        #[Assert\NotBlank]
        #[SerializedName('engagement_roi')]
        public string $engagementRoi,

        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        #[SerializedName('meta_title')]
        public string $metaTitle,

        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        #[SerializedName('meta_description')]
        public string $metaDescription,

        #[Assert\NotBlank]
        public int $status,

        #[Assert\Count(min: 1)]
        #[SerializedName('sector_ids')]
        public array $sectorIds,

        #[SerializedName('image_id')]
        public string $imageId,

        #[SerializedName('published_at')]
        public ?\DateTimeImmutable $publishedAt
    )
    {
    }
}
