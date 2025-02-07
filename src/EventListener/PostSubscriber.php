<?php 

namespace App\EventListener;

use App\CommandService\UploadCommandService;
use App\Common\SyncPostImage;
use App\Entity\UploadPurpose;
use App\Event\PostCreated;
use App\Event\PostDeleted;
use App\Event\PostUpdated;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class PostSubscriber implements EventSubscriberInterface
{
    public function __construct(private SyncPostImage $syncPostImage, private UploadCommandService $uploadCommandService)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PostCreated::class => 'onCreated',
            PostUpdated::class => 'onUpdated',
            PostDeleted::class => 'onDeleted'
        ];
    }

    public function onCreated(PostCreated $event): void
    {
        $this->syncPostImage->proceed($event->id);
    }

    public function onUpdated(PostUpdated $event): void
    {
        $this->syncPostImage->proceed($event->id);
    }

    public function onDeleted(PostDeleted $event): void
    {
        $purpose = UploadPurpose::POST;
        $this->uploadCommandService->clearUsedBy($event->id, $purpose->value);
    }
}
