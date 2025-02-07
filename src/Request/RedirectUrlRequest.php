<?php 

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

readonly class RedirectUrlRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $oldUrl,

        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $newUrl
    )
    {
    }
}
