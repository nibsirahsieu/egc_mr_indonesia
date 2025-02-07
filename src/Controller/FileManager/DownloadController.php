<?php 

namespace App\Controller\FileManager;

use App\Common\BlurhashService;
use App\Common\StreamDownload;
use App\Common\UploadNamer;
use App\Controller\BaseController;
use App\Entity\FileUploaded;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\CacheInterface;

final class DownloadController extends BaseController
{
    public function __construct(
        private StreamDownload $streamDownload,
        private BlurhashService $blurhashService,
        private CacheInterface $blurhashCache
    )
    {
    }

    #[Route('/upload-files/{id}/download', name: 'app_upload_files_download', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function default(FileUploaded $fileUploaded, Request $request): Response
    {
        $disposition = $request->query->get('disposition', 'inline');

        $uploadNamer = new UploadNamer();
        $name = $uploadNamer->nameFromEntity($fileUploaded);

        return $this->streamDownload->getResponse($name, $fileUploaded->getMimeType(), $fileUploaded->getOriginalName(), $disposition);
    }

    #[Route('/upload-files/{hash}/from-hash', name: 'app_upload_files_from_hash', methods: ['GET'])]
    public function fromHash(string $hash): Response
    {
        $width = 32;
        $height = 32;

        $cacheKey = sprintf('%s_%d_%d', urlencode($hash), $width, $height);
        $content = $this->blurhashCache->get($cacheKey, function() use ($hash, $width, $height): string {
            return $this->blurhashService->hashToImage($hash, $width, $height);
        });

        return new Response($content, 200, ['Content-Type' => 'image/png']);
    }
}
