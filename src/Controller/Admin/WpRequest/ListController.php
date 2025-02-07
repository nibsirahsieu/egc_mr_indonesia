<?php 

namespace App\Controller\Admin\WpRequest;

use App\Common\BreadcrumbBuilder;
use App\Common\PageData;
use App\Controller\BaseController;
use App\QueryService\WpRequestQueryService;
use App\View\WpRequestView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\UnicodeString;

#[Route('/wp-requests', name: 'app_admin_wp_requests_')]
final class ListController extends BaseController
{
    public function __construct(private WpRequestQueryService $wpRequestQueryService)
    {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(BreadcrumbBuilder $breadcrumbBuilder): Response
    {
        $breadcrumbBuilder->add('Download Whitepaper Request');

        return $this->render('admin/wpRequest/index.html.twig');
    }

    #[Route('/ajax-list', name: "ajax_list", options: ['expose' => true])]
    public function ajax(Request $request): Response
    {
        $aaData = [];
        $page = $request->query->getInt('page', 1);
        $length = $request->query->getInt('length', 10);
        $pageData = PageData::create($page, $length);

        $wpRequests = $this->wpRequestQueryService->all($pageData);
        
        foreach ($wpRequests->data as $wpRequest) {
            /** @var WpRequestView $wpRequest*/
            $aaData[] = [
                'DT_RowId' => $wpRequest->id,
                'FirstName' => $wpRequest->firstName,
                'LastName' => $wpRequest->lastName,
                'Name' => sprintf('%s %s', $wpRequest->firstName, $wpRequest->lastName),
                'CompanyName' => $wpRequest->companyName,
                'JobTitle' => $wpRequest->jobTitle,
                'Email' => $wpRequest->email,
                'Country' => $wpRequest->country,
                'PhoneNumber' => $wpRequest->phoneNumber,
                'Message' => (new UnicodeString($wpRequest->message))->truncate(500, '...', false),
                'FullMessage' => $wpRequest->message,
                'Downloaded' => $wpRequest->downloaded,
                'Whitepaper' => $wpRequest->whitepaper,
                'Actions' => '<a href="javascript:;" class="btn btn-xs btn-secondary btn-detail">Detail</a>'
            ];
        }

        return $this->json([
            'data' => $aaData,
            'recordsTotal' => $wpRequests->nbData ?: 0,
            'recordsFiltered' => $wpRequests->nbData ?: 0
        ]);
    }
}
