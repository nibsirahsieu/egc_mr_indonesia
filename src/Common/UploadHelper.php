<?php 

namespace App\Common;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class UploadHelper
{
    public function __construct(
        #[Autowire('%app.base-upload-url%')]
        private string $publicAssetBaseUrl,
        #[Autowire('%app.public-upload-directory%')]
        private string $publicUploadDirectory,
    )
    {
    }

    public function getPublicUrl(string $path): string
    {
        return $this->publicAssetBaseUrl.'/'.$path;
    }

    public function getPublicPath(string $path): string
    {
        return $this->publicUploadDirectory . DIRECTORY_SEPARATOR . $path;
    }

    public function getRelativeUrl(string $path): string
    {
        return '/' . basename($this->publicAssetBaseUrl) . '/' . $path;
    }
}
