<?php 

namespace App\Controller\Admin\CaseStudy;

use App\CommandService\CaseStudyCommandService;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/case-studies', name: 'app_admin_case_studies_')]
final class PublishController extends BaseController
{
    public function __construct(private CaseStudyCommandService $commandService)
    {
    }

    #[Route('/{id}/publish', name: "publish", methods: ['POST'], requirements: ['id' => '\d+'])]
    public function publishNow(int $id): Response
    {
        $this->commandService->publish($id, new \DateTimeImmutable());

        return $this->json([
            'success' => true
        ]);
    }

    #[Route('/{id}/republish', name: "republish", methods: ['POST'], requirements: ['id' => '\d+'])]
    public function republish(int $id): Response
    {
        $this->commandService->publish($id);

        return $this->json([
            'success' => true
        ]);
    }

    #[Route('/{id}/unpublish', name: "unpublish", methods: ['POST'], requirements: ['id' => '\d+'])]
    public function unPublish(int $id): Response
    {
        $this->commandService->unPublish($id);

        return $this->json([
            'success' => true
        ]);
    }
}
