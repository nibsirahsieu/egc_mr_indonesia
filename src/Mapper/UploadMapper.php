<?php 

namespace App\Mapper;

use App\Entity\FileUploaded;
use App\View\UploadView;

final class UploadMapper
{
    public function toView(FileUploaded $fileUploaded): UploadView
    {
        return new UploadView(
            $fileUploaded->getId(),
            $fileUploaded->getName(),
            $fileUploaded->getOriginalName(),
            $fileUploaded->getFileSize(),
            $fileUploaded->getMimeType(),
            $fileUploaded->getRelativePath(),
            $fileUploaded->getHash()
        );
    }
}
