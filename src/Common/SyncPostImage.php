<?php 

namespace App\Common;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;

final class SyncPostImage
{
    public function __construct(private EntityManagerInterface $entityManager, private SyncContentImage $syncContentImage)
    {
    }

    public function proceed(int $postId): void
    {
        $post = $this->entityManager->getRepository(Post::class)->find($postId);
        if (!$post) {
            return;
        }

        $newContent = $this->syncContentImage->sync($post->getContent(), $post->getId());
        $post->setContent($newContent);

        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }
}
