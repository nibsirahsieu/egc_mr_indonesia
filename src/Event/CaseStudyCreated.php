<?php 

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class CaseStudyCreated extends Event
{
    public function __construct(public int $id)
    {
    }
}
