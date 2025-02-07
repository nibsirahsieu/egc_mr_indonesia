<?php 

namespace App\Controller\Admin\CaseStudy;

use App\Common\BreadcrumbBuilder;
use App\Controller\BaseController;
use App\Repository\SectorRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class NewController extends BaseController
{
    public function __construct(private SectorRepository $sectorRepository)
    {
    }

    #[Route('/case-studies/new', name: "app_admin_case_studies_new", methods: ['GET'])]
    public function __invoke(BreadcrumbBuilder $breadcrumbBuilder): Response
    {
        $sectors = $this->sectorRepository->findBy([], ['name' => 'ASC']);

        $breadcrumbBuilder
            ->add('Case Studies', 'app_admin_case_studies_index')
            ->add('New');

        return $this->render('admin/caseStudy/new.html.twig', ['sectors' => $sectors]);
    }
}
