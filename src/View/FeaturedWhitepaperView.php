<?php 

namespace App\View;

readonly class FeaturedWhitepaperView
{
    public function __construct(
        public int $id, 
        public string $slug, 
        public string $title, 
        public array $headerImage, 
        public array $thumbnail
    )
    {        
    }
}
