<?php 

namespace App\Controller\Admin\UrlRedirection;

use App\Common\BreadcrumbBuilder;
use App\Common\PageData;
use App\Controller\BaseController;
use App\QueryService\RedirectUrlQueryService;
use App\SearchFilter\RedirectUrlFilter;
use App\View\RedirectUrlView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/url-redirections', name: 'app_admin_url_redirections_')]
final class ListController extends BaseController
{
    public function __construct(private readonly RedirectUrlQueryService $redirectUrlQueryService)
    {
        
    }
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(BreadcrumbBuilder $breadcrumbBuilder): Response
    {
        $breadcrumbBuilder->add('Url Redirections');

        return $this->render('admin/urlRedirection/index.html.twig');
    }

    #[Route('/ajax-list', name: "ajax_list", options: ['expose' => true])]
    public function ajax(Request $request): Response
    {
        $aaData = [];
        $page = $request->query->getInt('page', 1);
        $length = $request->query->getInt('length', 10);
        $filters = $request->query->all('filters');

        $pageData = PageData::create($page, $length);

        $redirectUrls = $this->redirectUrlQueryService->all($pageData, new RedirectUrlFilter($filters['old_url'] ?? '', $filters['new_url'] ?? ''));

        foreach ($redirectUrls->data as $redirectUrl) {
            /** @var RedirectUrlView $redirectUrl*/
            $aaData[] = [
                'DT_RowId' => $redirectUrl->id,
                'OldUrl' => sprintf('<a href="%s" target="_blank">%s</a>', $redirectUrl->oldUrl, $redirectUrl->oldUrl),
                'NewUrl' => $redirectUrl->newUrl,
                'Actions' => $this->buildActions($redirectUrl)
            ];
        }

        return $this->json([
            'data' => $aaData,
            'recordsTotal' => $redirectUrls->nbData ?: 0,
            'recordsFiltered' => $redirectUrls->nbData ?: 0
        ]);
    }

    private function buildActions(RedirectUrlView $redirectUrl): string
    {
        return '<a href="javascript:;" class="btn btn-primary btn-icon btn-edit"><i data-feather="edit"></i></a><a href="javascript:;" class="btn btn-danger btn-icon btn-delete mg-l-2"><i data-feather="trash"></i></a>';
    }
}
