<?php 

namespace App\Controller\CaseStudy;

use App\QueryService\CaseStudyQueryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DetailController extends AbstractController
{
    public function __construct(private CaseStudyQueryService $queryService)
    {
    }

    #[Route('/case-studies/{slug}', name: 'app_case_studies_show', methods: ['GET'])]
    public function __invoke(string $slug): Response
    {
        $caseStudy = $this->queryService->detail($slug);

        return $this->render('caseStudy/detail.html.twig', [
            'caseStudy' => $caseStudy
        ]);
    }   
}
