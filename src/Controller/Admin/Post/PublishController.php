<?php 

namespace App\Controller\Admin\Post;

use App\CommandService\PostCommandService;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/insights', name: 'app_admin_insights_')]
final class PublishController extends BaseController
{
    public function __construct(private PostCommandService $postCommandService)
    {
    }

    #[Route('/insights/{id}/publish', name: "publish", methods: ['POST'], requirements: ['id' => '\d+'])]
    public function publishNow(int $id): Response
    {
        $this->postCommandService->publish($id, new \DateTimeImmutable());

        return $this->json([
            'success' => true
        ]);
    }

    #[Route('/insights/{id}/republish', name: "republish", methods: ['POST'], requirements: ['id' => '\d+'])]
    public function republish(int $id): Response
    {
        $this->postCommandService->publish($id);

        return $this->json([
            'success' => true
        ]);
    }

    #[Route('/insights/{id}/unpublish', name: "unpublish", methods: ['POST'], requirements: ['id' => '\d+'])]
    public function unPublish(int $id): Response
    {
        $this->postCommandService->unPublish($id);

        return $this->json([
            'success' => true
        ]);
    }
}
