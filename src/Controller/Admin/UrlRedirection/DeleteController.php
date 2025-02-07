<?php 

namespace App\Controller\Admin\UrlRedirection;

use App\CommandService\RedirectUrlCommandService;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DeleteController extends BaseController
{
    public function __construct(private RedirectUrlCommandService $redirectUrlCommandService)
    {
    }

    #[Route('/url-redirections/{id}/delete', name: "app_admin_url_redirections_delete", methods: ['DELETE'], requirements: ['id' => '\d+'], options: ['expose' => true])]
    public function __invoke(int $id): Response
    {
        $this->redirectUrlCommandService->delete($id);

        return $this->json([
            'success' => true
        ]);
    }
}
