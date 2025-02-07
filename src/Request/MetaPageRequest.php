<?php 

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

readonly class MetaPageRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $metaTitle,

        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $metaDescription
    )
    {
    }
}
