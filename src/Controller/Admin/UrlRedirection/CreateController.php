<?php 

namespace App\Controller\Admin\UrlRedirection;

use App\CommandService\RedirectUrlCommandService;
use App\Controller\BaseController;
use App\Request\RedirectUrlRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class CreateController extends BaseController
{
    public function __construct(private RedirectUrlCommandService $redirectUrlCommandService)
    {
    }

    #[Route('/url-redirections/create', name: "app_admin_url_redirections_create", methods: ['POST'], options: ['expose' => true])]
    public function __invoke(#[MapRequestPayload()] RedirectUrlRequest $redirectUrlRequest): Response
    {
        $this->redirectUrlCommandService->create($redirectUrlRequest);

        return $this->json([
            'success' => true
        ]);
    }
}
