<?php 

namespace App\Controller\Admin\CaseStudy;

use App\CommandService\CaseStudyCommandService;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DeleteController extends BaseController
{
    public function __construct(private CaseStudyCommandService $caseStudyCommandService)
    {
    }

    #[Route('/case-studies/{id}/delete', name: "app_admin_case_studies_delete", methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function __invoke(int $id): Response
    {
        $this->caseStudyCommandService->delete($id);

        return $this->json([
            'success' => true
        ]);
    }
}
