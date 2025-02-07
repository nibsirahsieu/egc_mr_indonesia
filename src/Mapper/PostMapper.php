<?php 

namespace App\Mapper;

use App\Common\IdName;
use App\Entity\FileUploaded;
use App\Entity\Post;
use App\Entity\PostStatus;
use App\Entity\PostType;
use App\Entity\Sector;
use App\Request\PostRequest;
use App\View\PostView;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class PostMapper
{
    public function __construct(private EntityManagerInterface $entityManager, private UploadMapper $uploadMapper, private HtmlSanitizerInterface $appPostSanitizer)
    {
    }

    public function fromRequest(PostRequest $request, Post $post)
    {
        $postType = $this->entityManager->getRepository(PostType::class)->find($request->getTypeId());
        if (!$postType) {
            throw new NotFoundHttpException("Unknown type");
        }
        $headerImage = $request->getHeaderImageId() ? $this->entityManager->getRepository(FileUploaded::class)->find($request->getHeaderImageId()) : null;
        $file = $request->getFileId() ? $this->entityManager->getRepository(FileUploaded::class)->find($request->getFileId()) : null;
        $thumbnail = $request->getThumbnailId() ? $this->entityManager->getRepository(FileUploaded::class)->find($request->getThumbnailId()) : null;

        $sanitizedContent = $this->appPostSanitizer->sanitizeFor('body', $request->getContent());
        $post->setTitle($request->getTitle());
        $post->setSlug($request->getSlug());
        $post->setMetaTitle($request->getMetaTitle());
        $post->setMetaDescription($request->getMetaDescription());
        $post->setContent($sanitizedContent);
        $post->setSummary($request->getSummary());
        $post->setAuthor($request->getAuthor());
        $post->setPublishedAt($request->getPublishedAt());
        $post->setType($postType);
        
        if ($headerImage) {
            $headerImage->setUsedBy($post->getId());
            $post->setHeaderImage($headerImage);
        }
        if ($file) {
            $file->setUsedBy($post->getId());
            $post->setFile($file);
        }
        if ($thumbnail) {
            $thumbnail->setUsedBy($post->getId());
            $post->setThumbnail($thumbnail);
        }
        $post->setStatus(PostStatus::tryFrom($request->getStatus()));

        $requestedSectors = count($request->getSectorIds()) > 0 ? $this->entityManager->getRepository(Sector::class)->findBy(['id' => $request->getSectorIds()]) : [];
        $post->syncSectors($requestedSectors);
    }

    public function toView(Post $post): PostView
    {
        $headerImage = $this->uploadMapper->toView($post->getHeaderImage());
        $file = $post->getFile() ? $this->uploadMapper->toView($post->getFile()) : null;
        $thumbnail = $post->getThumbnail() ? $this->uploadMapper->toView($post->getThumbnail()) : null;
        $sectors = $post->getSectors()->map(function(Sector $sector) {
            return IdName::create($sector->getId(), $sector->getName());
        });

        return new PostView(
            $post->getId(),
            IdName::create($post->getType()->getId(), $post->getType()->getName()),
            $post->getTitle(),
            $post->getSlug(),
            $post->getSummary(),
            $post->getContent(),
            $post->getMetaTitle(),
            $post->getMetaDescription(),
            $post->getAuthor(),
            $post->getPublishedAt(),
            $sectors->toArray(),
            $headerImage,
            $file,
            $thumbnail,
            $post->getStatus(),
            IdName::create($post->getType()->getId(), $post->getType()->getName()),
            $post->getUpdatedAt()
        );
    }
}
