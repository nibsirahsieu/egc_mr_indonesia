<?php 

namespace App\Controller\Admin\MetaSection;

use App\Common\BreadcrumbBuilder;
use App\Controller\BaseController;
use App\QueryService\MetaPageQueryService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/meta-sections', name: 'app_admin_meta_sections_')]
final class ListController extends BaseController
{
    public function __construct(private readonly MetaPageQueryService $queryService)
    {
        
    }
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(BreadcrumbBuilder $breadcrumbBuilder): Response
    {
        $breadcrumbBuilder->add('Meta Section');

        return $this->render('admin/metaSection/index.html.twig');
    }

    #[Route('/ajax-list', name: "ajax_list", options: ['expose' => true])]
    public function ajax(): Response
    {
        $aaData = [];
        $metaSections = $this->queryService->all();

        foreach ($metaSections as $metaSection) {
            $aaData[] = [
                'DT_RowId' => $metaSection->getId(),
                'Page' => $metaSection->getName(),
                'MetaTitle' => $metaSection->getMetaTitle(),
                'MetaDescription' => $metaSection->getMetaDescription(),
                'Actions' => $this->buildActions()
            ];
        }

        return $this->json([
            'data' => $aaData,
            'recordsTotal' => count($metaSections),
            'recordsFiltered' => count($metaSections)
        ]);
    }

    private function buildActions(): string
    {
        return '<a href="javascript:;" class="btn btn-primary btn-icon btn-edit"><i data-feather="edit"></i></a>';
    }
}
