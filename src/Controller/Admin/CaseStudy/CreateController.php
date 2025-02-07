<?php 

namespace App\Controller\Admin\CaseStudy;

use App\CommandService\CaseStudyCommandService;
use App\Controller\BaseController;
use App\Request\CaseStudyRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class CreateController extends BaseController
{
    public function __construct(private CaseStudyCommandService $commandService)
    {
    }

    #[Route('/case-studies/create', name: 'app_admin_case_studies_create', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload()] CaseStudyRequest $caseStudyRequest): Response
    {
        $this->commandService->create($caseStudyRequest);
        
        if (0 === $caseStudyRequest->status) {
            //redirect to preview page
        }

        $this->addFlash('success', 'Case Study has been added.');

        return $this->redirectToRoute('app_admin_case_studies_index');
    }
}
