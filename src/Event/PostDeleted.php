<?php 

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class PostDeleted extends Event
{
    public function __construct(public int $id)
    {
    }
}
