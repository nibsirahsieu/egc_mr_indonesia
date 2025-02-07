<?php 

namespace App\Common;

use Imagick;
use Oneup\UploaderBundle\Uploader\File\FileInterface;
use Oneup\UploaderBundle\Uploader\File\FilesystemFile;
use Oneup\UploaderBundle\Uploader\Storage\StorageInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class CoverFileGenerator
{
    private $resolution = 144;
    private $layerMethod = Imagick::LAYERMETHOD_FLATTEN;
    private $outputFormat = 'jpg';
    
    public function __construct(private StorageInterface $publicLocalStorage, private UploadNamer $uploadNamer)
    {
    }

    public function generate(string $filePath, ?int $pageNumber = null, ?int $thumbnailWidth = null, ?string $outputFormat = null, ?int $resolution = null): FileInterface
    {
        $imageResolution = $resolution ?: $this->resolution;
        $imageOutputFormat = $outputFormat ?: $this->outputFormat;
        $pageNo = $pageNumber ? $pageNumber - 1 : 0;

        $imagick = new Imagick();
        $imagick->setResolution($imageResolution, $imageResolution);
        $imagick->readImage(sprintf('%s[%d]', $filePath, $pageNo));
        $imagick = $imagick->mergeImageLayers($this->layerMethod);
        if ($thumbnailWidth) {
            $imagick->thumbnailImage($thumbnailWidth, 0);
        }

        $imagick->setFormat($imageOutputFormat);

        $tempFile = tempnam(sys_get_temp_dir(), 'cover_file');
        file_put_contents($tempFile, $imagick);

        $uploadedFile = new UploadedFile($tempFile, uniqid('', true).'.'.$imageOutputFormat, null, null, true);
        $fileSystemFile = new FilesystemFile($uploadedFile);
        $fileName = $this->uploadNamer->name($fileSystemFile);

        return $this->publicLocalStorage->upload($fileSystemFile, $fileName);
    }
}
