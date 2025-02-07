<?php 

namespace App\Message;

use App\Common\AsyncEventInterface;

final class InquiryEmail implements AsyncEventInterface
{
    public function __construct(public int $inquiryId)
    {
    }
}
