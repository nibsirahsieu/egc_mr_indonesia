<?php

namespace App\EventListener;

use App\CommandService\UploadCommandService;
use App\Common\UploadHelper;
use App\Message\CreateBlurhash;
use App\Request\UploadRequest;
use Liip\ImagineBundle\Message\WarmupCache;
use Oneup\UploaderBundle\Event\PostUploadEvent;
use Oneup\UploaderBundle\Event\PreUploadEvent;
use Oneup\UploaderBundle\UploadEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class OneUploaderListener implements EventSubscriberInterface
{
    public function __construct(
        private UploadCommandService $commandService, 
        private UrlGeneratorInterface $urlGenerator,
        private MessageBusInterface $messageBus,
        private UploadHelper $uploadHelper
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UploadEvents::preUpload('uploads') => 'onPreUpload',
            UploadEvents::postUpload('uploads') => 'onPostUpload'
        ];
    }

    public function onPreUpload(PreUploadEvent $event): void
    {
        $file = $event->getFile();
        $response = $event->getResponse();

        $response['original_name'] =  $file instanceof UploadedFile ? $file->getClientOriginalName() : $file->getFilename();
    }

    public function onPostUpload(PostUploadEvent $event): void
    {
        $file = $event->getFile();
        $request = $event->getRequest();

        $view = $this->commandService->create(new UploadRequest(
            $file->getBasename(),
            $event->getResponse()['original_name'] ?? $file->getBasename(),
            $file->getMimeType(),
            $file->getSize(),
            $file->getExtension(),
            $request->query->getInt('purpose')
        ));

        $response = $event->getResponse();
        $response['id'] = $view->getId();
        $response['name'] = $view->getName();
        $response['original_name'] = $view->getOriginalName();
        $response['url'] = $this->uploadHelper->getPublicUrl($view->getRelativePath());
        $response['relative_url'] = $this->uploadHelper->getRelativeUrl($view->getRelativePath());

        $request = $event->getRequest();
        
        if ($imageFilter = $request->query->get('filter')) {
            $this->messageBus->dispatch(new WarmupCache($file->getPathname(), explode(',', $imageFilter)));
        }
        if ($request->query->getBoolean('blurhash', false)) {
            $this->messageBus->dispatch(new CreateBlurhash($view->getId()));
        }
    }
}

