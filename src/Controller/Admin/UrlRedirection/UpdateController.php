<?php 

namespace App\Controller\Admin\UrlRedirection;

use App\CommandService\RedirectUrlCommandService;
use App\Controller\BaseController;
use App\Request\RedirectUrlRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class UpdateController extends BaseController
{
    public function __construct(private RedirectUrlCommandService $redirectUrlCommandService)
    {
    }

    #[Route('/url-redirections/{id}/update', name: "app_admin_url_redirections_update", methods: ['POST'], requirements: ['id' => '\d+'], options: ['expose' => true])]
    public function __invoke(int $id, #[MapRequestPayload()] RedirectUrlRequest $redirectUrlRequest): Response
    {
        $this->redirectUrlCommandService->update($id, $redirectUrlRequest);

        return $this->json([
            'success' => true
        ]);
    }
}
