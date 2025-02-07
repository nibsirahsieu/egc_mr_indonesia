<?php 

namespace App\EventListener;

use App\QueryService\RedirectUrlQueryService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsEventListener()]
final class PageNotFoundListener
{
    public function __construct(private readonly RedirectUrlQueryService $redirectUrlQueryService)
    {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if ($exception instanceof NotFoundHttpException) {
            if ($redirectUrl = $this->redirectUrlQueryService->getRedirectUrl($event->getRequest()->getUri())) {
                $event->setResponse(new RedirectResponse($redirectUrl, RedirectResponse::HTTP_MOVED_PERMANENTLY));
            }
        }
    }
}
