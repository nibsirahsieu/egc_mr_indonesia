<?php 

declare(strict_types=1);

namespace App\Common;

final class PageData
{
    public $page;
    public $length;

    static public function create(int $page, int $length): static
    {
        return new self($page, $length);
    }

    public function getOffset(): int
    {
        return ($this->page - 1) * $this->length;
    }

    private function __construct(int $page, int $length)
    {
        $this->page = $page;
        $this->length = $length;
    }
}