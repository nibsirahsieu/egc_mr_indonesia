<?php 

namespace App\SearchFilter;

final class PostFilter
{
    public function __construct(public ?int $type, public ?string $title, public ?int $status)
    {
    }
}
