<?php

namespace App\Common;

use League\Flysystem\FilesystemOperator;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class Base64ImageToUrlConverter
{
    public function __construct(
        private FilesystemOperator $publicUploadsFilesystem,
        #[Autowire('%app.base-upload-directory%')]
        private string $baseUploadDirectory
    )
    {
    }

    public function toUrl(string $content, ?string $class = null): string
    {
        libxml_use_internal_errors(true);

        $doc = new \DOMDocument();
        $doc->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NODEFDTD);
        $images = $doc->getElementsByTagName('img');

        /** @var \DOMElement $image */
        foreach ($images as $image) {
            $b64Value = $image->getAttribute('src');
            if (!str_contains($b64Value, 'data:image/')) {
                continue;
            }
            $imageParts = explode(";base64,", $b64Value);
            $imageTypeAux = explode("image/", $imageParts[0]);
            $imageType = $imageTypeAux[1];
            $imageBase64 = base64_decode($imageParts[1]);

            $filename = sprintf('%s.%s', uniqid(), $imageType);
            $filePath = sprintf('%s/%s', date('Y/m/d'), $filename);

            $stream = fopen('php://temp', 'r+');
            fwrite($stream, $imageBase64);
            rewind($stream);

            $this->publicUploadsFilesystem->writeStream($filePath, $stream);
            $assetPath = $this->baseUploadDirectory . DIRECTORY_SEPARATOR . $filePath;

            $image->setAttribute('src', $assetPath);
            if ($class) {
                //$image->setAttribute('class', 'w-100 lazyload');
                $image->setAttribute('class', $class);
            }
        }

        return $doc->saveHTML();
    }
}

