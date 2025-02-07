<?php 

namespace App\Controller\Admin\CaseStudy;

use App\Common\BreadcrumbBuilder;
use App\Controller\BaseController;
use App\QueryService\CaseStudyQueryService;
use App\Repository\SectorRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EditController extends BaseController
{
    public function __construct(private CaseStudyQueryService $caseStudyQueryService, private SectorRepository $sectorRepository)
    {
    }

    #[Route('/case-studies/{id}/edit', name: "app_admin_case_studies_edit", methods: ['GET'], requirements: ['id' => '\d+'])]
    public function __invoke(BreadcrumbBuilder $breadcrumbBuilder, int $id): Response
    {
        $caseStudy = $this->caseStudyQueryService->detail($id);
        $sectors = $this->sectorRepository->findBy([], ['name' => 'ASC']);

        $breadcrumbBuilder
            ->add('Case Studies', 'app_admin_case_studies_index')
            ->add('Edit');

        return $this->render('admin/caseStudy/edit.html.twig', [
            'case_study' => $caseStudy,
            'sectors' => $sectors
        ]);
    }
}
