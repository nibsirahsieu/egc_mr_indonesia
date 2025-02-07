<?php

declare(strict_types=1);

namespace App\Common;

final class PaginateResult
{
    public $data;
    public $nbData;

    static public function create(array $data, ?int $nbData = null): static
    {
        return new self($data, $nbData);
    }

    private function __construct(array $data, ?int $nbData = null)
    {
        $this->data = $data;
        $this->nbData = $nbData;
    }

    public function setNbData(int $nbData)
    {
        $this->nbData = $nbData;
    }
}
