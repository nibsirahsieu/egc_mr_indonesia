<?php 

namespace App\MessageHandler;

use App\Common\BlurhashService;
use App\Common\UploadHelper;
use App\Common\UploadNamer;
use App\Entity\FileUploaded;
use App\Message\CreateBlurhash;
use Doctrine\ORM\EntityManagerInterface;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
final class BlurhashHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CacheManager $cacheManager,
        private BlurhashService $blurhashService,
        private UploadNamer $uploadNamer,
        private UploadHelper $uploadHelper
    )
    {
    }

    public function __invoke(CreateBlurhash $message): void
    {
        /** @var FileUploaded */
        $fileUploaded = $this->entityManager->getRepository(FileUploaded::class)->find($message->fileId);
        if (null === $fileUploaded) return;

        $thumbImage = $this->createThumbnail($fileUploaded);
        $hash = $this->blurhashService->hashFromFile($thumbImage);

        $fileUploaded->setHash($hash);

        $this->entityManager->persist($fileUploaded);
        $this->entityManager->flush();

        @unlink($thumbImage);
    }

    private function createThumbnail(FileUploaded $fileUploaded): string
    {
        $name = $this->uploadNamer->nameFromEntity($fileUploaded);
        $absolutePath = $this->uploadHelper->getPublicPath($name);
        $targetPath = dirname($absolutePath) . DIRECTORY_SEPARATOR . uniqid() . '.' . $fileUploaded->getExtension();

        $imagine = new Imagine();
        $size = new Box(200, 200);
        $imagine
            ->open($absolutePath)
            ->thumbnail($size)
            ->save($targetPath);

        return $targetPath;
    }
}
