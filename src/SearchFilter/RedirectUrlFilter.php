<?php 

namespace App\SearchFilter;

final class RedirectUrlFilter
{
    public function __construct(public ?string $oldUrl, public ?string $newUrl)
    {
    }
}
