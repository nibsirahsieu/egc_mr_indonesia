<?php 

namespace App\Message;

use App\Common\AsyncEventInterface;

final class SaveToOasis implements AsyncEventInterface
{
    public function __construct(public int $inquiryId)
    {
    }
}
