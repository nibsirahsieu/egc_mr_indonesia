<?php

namespace App\Common;

final class IdName
{
    /** @var string|int */
    public $id;
    
    public $name;

    public static function create($id, string $name): static
    {
        return new self($id, $name);
    }

    private function __construct($id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}