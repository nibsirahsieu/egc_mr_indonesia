<?php 

namespace App\View;

use App\Common\IdName;

readonly class RecentPostView
{
    public function __construct(
        public int $id, public string $slug, public string $title, public IdName $category, public \DateTimeImmutable $publishedAt, 
        private string $headerImage, private ?string $headerHash, private ?string $thumbImage, private ?string $thumbHash
    )
    {
    }

    public function thumbnail(): array
    {
        if ($this->thumbImage) {
            return [
                'image' => $this->thumbImage,
                'hash' => $this->thumbHash
            ];
        }

        return [
            'image' => $this->headerImage,
            'hash' => $this->headerHash
        ];
    }
}
