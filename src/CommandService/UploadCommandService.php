<?php 

namespace App\CommandService;

use App\Common\CoverFileGenerator;
use App\Common\UploadHelper;
use App\Common\UploadNamer;
use App\Entity\FileUploaded;
use App\Mapper\UploadMapper;
use App\Message\CreateBlurhash;
use App\Request\UploadRequest;
use App\View\UploadView;
use Doctrine\ORM\EntityManagerInterface;
use Liip\ImagineBundle\Message\WarmupCache;
use Symfony\Component\Clock\DatePoint;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\MessageBusInterface;

final class UploadCommandService
{
    public function __construct(
        private EntityManagerInterface $em, 
        private CoverFileGenerator $coverFileGenerator, 
        private UploadMapper $mapper,
        private UploadHelper $uploadHelper,
        private MessageBusInterface $messageBus
    )
    {
    }

    public function create(UploadRequest $request): UploadView
    {
        $upload = new FileUploaded();
        $upload->setCreatedAt(new \DateTime());
        $upload->setName($request->getName());
        $upload->setOriginalName($request->getOriginalName());
        $upload->setFileSize($request->getSize());
        $upload->setMimeType($request->getMimeType());
        $upload->setExtension($request->getExtension());
        $upload->setExpiredAt(new DatePoint('+1 year', reference: new \DateTimeImmutable()));
        $upload->setPurpose($request->getPurpose());
        $upload->setRelativePath((new UploadNamer())->nameFromEntity($upload));

        $this->em->persist($upload);
        $this->em->flush();

        return $this->mapper->toView($upload);
    }

    public function delete(int $id): void
    {
        $file = $this->em->getRepository(FileUploaded::class)->find($id);
        if (!$file) {
            throw new NotFoundHttpException("File not found");
        }

        $relativePath = $file->getRelativePath();

        $this->em->remove($file);
        $this->em->flush();
        
        $absolutePath = $this->uploadHelper->getPublicPath($relativePath);
        @unlink($absolutePath);
    }

    public function generateCover(int $id): UploadView
    {
        $file = $this->em->getRepository(FileUploaded::class)->find($id);
        if (!$file) {
            throw new NotFoundHttpException("File not found");
        }

        $coverFile = $this->coverFileGenerator->generate($this->uploadHelper->getPublicPath($file->getRelativePath()));

        $coverView = $this->create(new UploadRequest(
            $coverFile->getBasename(),
            $coverFile->getBasename(),
            $coverFile->getMimeType(),
            $coverFile->getSize(),
            $coverFile->getExtension(),
            $file->getPurpose()
        ));

        $this->messageBus->dispatch(new CreateBlurhash($coverView->getId()));
        $this->messageBus->dispatch(new WarmupCache($coverFile->getPathname(), ['thumb_sm']));
        
        return $coverView;
    }

    public function clearUsedBy(int $usedById, int $purpose): int
    {
        return $this->em->getRepository(FileUploaded::class)->clearUsedBy($usedById, $purpose);
    }
}
