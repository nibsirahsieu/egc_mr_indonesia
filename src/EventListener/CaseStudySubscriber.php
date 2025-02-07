<?php 

namespace App\EventListener;

use App\CommandService\UploadCommandService;
use App\Common\SyncCaseStudyImage;
use App\Entity\UploadPurpose;
use App\Event\CaseStudyCreated;
use App\Event\CaseStudyDeleted;
use App\Event\CaseStudyUpdated;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class CaseStudySubscriber implements EventSubscriberInterface
{
    public function __construct(private SyncCaseStudyImage $syncCaseStudyImage, private UploadCommandService $uploadCommandService)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CaseStudyCreated::class => 'onCreated',
            CaseStudyUpdated::class => 'onUpdated',
            CaseStudyDeleted::class => 'onDeleted'
        ];
    }

    public function onCreated(CaseStudyCreated $event): void
    {
        $this->syncCaseStudyImage->proceed($event->id);
    }

    public function onUpdated(CaseStudyUpdated $event): void
    {
        $this->syncCaseStudyImage->proceed($event->id);
    }

    public function onDeleted(CaseStudyDeleted $event): void
    {
        $purpose = UploadPurpose::CASE_STUDY;
        $this->uploadCommandService->clearUsedBy($event->id, $purpose->value);
    }
}
