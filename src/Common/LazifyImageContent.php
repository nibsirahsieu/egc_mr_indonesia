<?php 

namespace App\Common;

use App\Repository\FileUploadedRepository;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class LazifyImageContent
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator, 
        private CacheManager $cacheManager,
        private FileUploadedRepository $fileUploadedRepository
    )
    {
    }

    public function proceed(string $content): string
    {
        libxml_use_internal_errors(true);

        $doc = new \DOMDocument();
        $doc->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
        $images = $doc->getElementsByTagName('img');

        if (count($images) === 0) {
            return $content;
        }

        /** @var \DOMElement $image */
        foreach ($images as $image) {
            $lazyLoadAttr = $image->getAttribute('data-lazy-load');
            if ('' === $lazyLoadAttr || !filter_var($lazyLoadAttr, FILTER_VALIDATE_BOOLEAN)) {
                continue;
            }

            if (!$fileId = $image->getAttribute('data-file-id')) {
                continue;
            }

            if (!$fileUploaded = $this->fileUploadedRepository->find((int) $fileId)) {
                continue;
            }

            $srcSets = [];
            $src = $fileUploaded->getRelativePath();
            
            //add class lazy to image
            $image->setAttribute('class', 'lazy img-fluid');
            $image->setAttribute('data-sizes', 'auto');
            $image->setAttribute('width', '1024');
            $image->setAttribute('height', '575');
            
            foreach ($this->getFilters() as $filter => $width) {
                $srcSets[] = sprintf('%s %s', $this->cacheManager->getBrowserPath($src, $filter), $width);
            }

            $image->setAttribute('data-srcset', implode(', ', $srcSets));
            
            if ($fileUploaded->getHash()) {
                $image->setAttribute('src', $this->urlGenerator->generate('app_upload_files_from_hash', ['hash' => $fileUploaded->getHash()]));
            } else {
                $image->setAttribute('src', 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==');
            }

            //$this->appendImageToPicture($doc, $image, $src);
        }

        return $doc->saveHTML();
    }

    private function appendImageToPicture(\DOMDocument $doc, \DOMElement $image, string $src)
    {
        $picture = $doc->createElement('picture');

        $source = $doc->createElement('source');
        $source->setAttribute('type', 'webp');
     
        $srcSets = [];
        foreach ($this->getFilters() as $filter => $width) {
            $srcSets[] = sprintf('%s %s', $this->cacheManager->getBrowserPath($src, $filter .'_webp'), $width);
        }

        $source->setAttribute('data-srcset', implode(', ', $srcSets));
        $source->setAttribute('data-sizes', 'auto');

        $picture->appendChild($source);
        $picture->appendChild($image->cloneNode());
        $image->parentNode->replaceChild($picture, $image);
    }

    private function getFilters(): array
    {
        //see liip_imagine.yaml in the config/packages directory
        return [
            'post_lg' => '1024w',
            'post_md' => '810w',
            'post_sm' => '420w'
        ];
    }
}
