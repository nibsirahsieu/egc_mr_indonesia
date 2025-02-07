<?php 

namespace App\CommandService;

use App\Entity\Post;
use App\Entity\PostStatus;
use App\Event\PostCreated;
use App\Event\PostDeleted;
use App\Event\PostUpdated;
use App\Mapper\PostMapper;
use App\Request\PostRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class PostCommandService
{
    public function __construct(private EntityManagerInterface $em, private PostMapper $mapper, private EventDispatcherInterface $eventDispatcher)
    {
    }

    public function create(PostRequest $request): void
    {
        $post = new Post();
        $this->mapper->fromRequest($request, $post);
        $this->em->persist($post);
        $this->em->flush();

        $headerImage = $post->getHeaderImage();
        $headerImage->setUsedBy($post->getId());
        $this->em->persist($headerImage);

        if ($file = $post->getFile()) {
            $file->setUsedBy($post->getId());
            $this->em->persist($file);
        }
        if ($thumbnail = $post->getThumbnail()) {
            $thumbnail->setUsedBy($post->getId());
            $this->em->persist($thumbnail);
        }
        $this->em->flush();

        $this->eventDispatcher->dispatch(new PostCreated($post->getId()));
    }

    public function update(int $id, PostRequest $request): void
    {
        $post = $this->findById($id);
        $prevHeaderImage = $post->getHeaderImage();
        $prevFile = $post->getFile();
        $prevThumbnail = $post->getThumbnail();

        $this->mapper->fromRequest($request, $post);
        if ($prevHeaderImage !== $post->getHeaderImage()) {
            $prevHeaderImage->setUsedBy(null);
            $this->em->persist($prevHeaderImage);
        }
        if ($prevFile && $prevFile !== $post->getFile()) {
            $prevFile->setUsedBy(null);
            $this->em->persist($prevFile);
        }
        if ($prevThumbnail && $prevThumbnail !== $post->getThumbnail()) {
            $prevThumbnail->setUsedBy(null);
            $this->em->persist($prevThumbnail);
        }

        $this->em->persist($post);
        $this->em->flush();

        $this->eventDispatcher->dispatch(new PostUpdated($post->getId()));
    }

    public function delete(int $id): void
    {
        $post = $this->findById($id);
        $this->em->remove($post);
        $this->em->flush();

        $this->eventDispatcher->dispatch(new PostDeleted($id));
    }

    public function publish(int $id, ?\DateTimeImmutable $publishedDate = null): void
    {
        $post = $this->findById($id);
        if ($publishedDate) {
            $post->setPublishedAt($publishedDate);
        }
        
        $post->setStatus(PostStatus::PUBLISHED);

        $this->em->persist($post);
        $this->em->flush();
    }

    public function unPublish(int $id): void
    {
        $post = $this->findById($id);
        $post->setStatus(PostStatus::DRAFT);
        
        $this->em->persist($post);
        $this->em->flush();
    }

    private function findById(int $id): Post
    {
        $post = $this->em->getRepository(Post::class)->find($id);
        if (!$post) {
            throw new NotFoundHttpException("No post found for id ". $id);
        }

        return $post;
    }
}
