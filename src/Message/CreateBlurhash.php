<?php 

namespace App\Message;

use App\Common\AsyncEventInterface;

final class CreateBlurhash implements AsyncEventInterface
{
    public function __construct(public int $fileId)
    {
    }
}
