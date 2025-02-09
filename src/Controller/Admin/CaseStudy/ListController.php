<?php 

namespace App\Controller\Admin\CaseStudy;

use App\Common\BreadcrumbBuilder;
use App\Common\PageData;
use App\Controller\BaseController;
use App\QueryService\CaseStudyQueryService;
use App\View\CaseStudyListView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/case-studies')]
final class ListController extends BaseController
{
    public function __construct(private CaseStudyQueryService $caseStudyQueryService)
    {
    }

    #[Route('', name: "app_admin_case_studies_index", methods: ['GET'])]
    public function index(BreadcrumbBuilder $breadcrumbBuilder): Response
    {
        $breadcrumbBuilder->add('Case Studies');

        return $this->render('admin/caseStudy/index.html.twig');
    }

    #[Route('/ajax-list', name: "app_case_study_ajax_list", options: ['expose' => true])]
    public function ajax(Request $request): Response
    {
        $aaData = [];
        $page = $request->query->getInt('page', 1);
        $length = $request->query->getInt('length', 10);
        $search = $request->query->all('search');
        $pageData = PageData::create($page, $length);

        $caseStudies = $this->caseStudyQueryService->all($search['value'], $pageData);

        foreach ($caseStudies->data as $caseStudy) {
            /** @var CaseStudyListView $caseStudy*/
            $aaData[] = [
                'DT_RowId' => $caseStudy->id,
                'Title' => $caseStudy->title,
                'Slug' => $caseStudy->slug,
                'Client' => $caseStudy->client,
                'PublishedAt' => $caseStudy->publishedAt ? $caseStudy->publishedAt->format('M d, Y') : null,
                'Status' => $caseStudy->isDraft() ? '<span class="badge text-bg-secondary">Draft</span>' : '<span class="badge text-bg-success">Published</span>',
                'Actions' => $this->buildActions($caseStudy)
            ];
        }

        return $this->json([
            'data' => $aaData,
            'recordsTotal' => $caseStudies->nbData ?: 0,
            'recordsFiltered' => $caseStudies->nbData ?: 0
        ]);
    }

    private function buildActions(CaseStudyListView $caseStudy): string
    {
        $actions = sprintf('<a href="%s" class="btn btn-primary btn-xs">Edit</a>', $this->generateUrl('app_admin_case_studies_edit', ['id' => $caseStudy->id]));
        $actions .= sprintf('<a href="%s" class="btn btn-danger btn-xs btn-delete">Delete</a>', $this->generateUrl('app_admin_case_studies_delete', ['id' => $caseStudy->id]));
        if ($caseStudy->isDraft()) {
            if ($caseStudy->publishedAt) {
                $actions .= sprintf('<a href="%s" class="btn btn-warning btn-xs btn-publish-unpublish">Publish</a>', $this->generateUrl('app_admin_case_studies_republish', ['id' => $caseStudy->id]));
            } else {
                $actions .= sprintf('<a href="%s" class="btn btn-warning btn-xs btn-publish-unpublish">Publish now</a>', $this->generateUrl('app_admin_case_studies_publish', ['id' => $caseStudy->id]));
            }
            
        } else {
            $actions .= sprintf('<a href="%s" class="btn btn-warning btn-xs btn-publish-unpublish">Unpublish</a>', $this->generateUrl('app_admin_case_studies_unpublish', ['id' => $caseStudy->id]));
        }

        return '<div class="d-grid gap-1">' .  $actions . '</div>';
    }
}
