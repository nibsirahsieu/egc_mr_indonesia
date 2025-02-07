<?php 

namespace App\Controller\Admin\Post;

use App\CommandService\PostCommandService;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DeleteController extends BaseController
{
    public function __construct(private PostCommandService $postCommandService)
    {
    }

    #[Route('/insights/{id}/delete', name: "app_admin_insights_delete", methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function __invoke(int $id): Response
    {
        $this->postCommandService->delete($id);

        return $this->json([
            'success' => true
        ]);
    }
}
