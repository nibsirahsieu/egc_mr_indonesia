<?php

namespace App\Common;

use App\Entity\FileUploaded;
use Oneup\UploaderBundle\Uploader\File\FileInterface;
use Oneup\UploaderBundle\Uploader\Naming\NamerInterface;

final class UploadNamer implements NamerInterface
{
    public function name(FileInterface $file): string
    {
        return sprintf('%s/%s.%s',
            date('Y/m/d'),
            uniqid(),
            $file->getExtension()
        );
    }

    public function nameFromEntity(FileUploaded $file): string
    {
        return sprintf('%s/%s',
            $file->getCreatedAt()->format('Y/m/d'),
            $file->getName()
        );
    }
}

