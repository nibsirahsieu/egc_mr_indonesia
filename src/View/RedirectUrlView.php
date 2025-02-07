<?php 

namespace App\View;

readonly class RedirectUrlView
{
    public function __construct(public int $id, public string $oldUrl, public string $newUrl)
    {        
    }
}
