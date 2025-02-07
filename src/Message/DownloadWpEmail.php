<?php 

namespace App\Message;

use App\Common\AsyncEventInterface;

final class DownloadWpEmail implements AsyncEventInterface
{
    public function __construct(public int $id)
    {
    }
}
