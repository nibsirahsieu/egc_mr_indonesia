<?php 

namespace App\Controller\Admin\Inquiry;

use App\Common\BreadcrumbBuilder;
use App\Common\PageData;
use App\Controller\BaseController;
use App\QueryService\InquiryQueryService;
use App\View\InquiryView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\UnicodeString;

#[Route('/inquiries', name: 'app_admin_inquries_')]
final class ListController extends BaseController
{
    public function __construct(private InquiryQueryService $inquiryQueryService)
    {
    }
    
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(BreadcrumbBuilder $breadcrumbBuilder): Response
    {
        $breadcrumbBuilder->add('Inquiries');

        return $this->render('admin/inquiry/index.html.twig');
    }

    #[Route('/ajax-list', name: "ajax_list", options: ['expose' => true])]
    public function ajax(Request $request): Response
    {
        $aaData = [];
        $page = $request->query->getInt('page', 1);
        $length = $request->query->getInt('length', 10);
        $pageData = PageData::create($page, $length);

        $inquiries = $this->inquiryQueryService->all($pageData);
        
        foreach ($inquiries->data as $inquiry) {
            /** @var InquiryView $inquiry*/
            $aaData[] = [
                'DT_RowId' => $inquiry->id,
                'Name' => sprintf('%s %s', $inquiry->firstName, $inquiry->lastName),
                'NameWithTitle' => sprintf('<p class="tx-medium mg-b-0">%s %s</p><p class="tx-12 mg-b-0 tx-color-03">%s</p>', $inquiry->firstName, $inquiry->lastName, $inquiry->jobTitle),
                'CompanyName' => $inquiry->companyName,
                'CompanyWithEmail' => sprintf('<p class="tx-medium mg-b-0">%s</p><p class="tx-12 mg-b-0 tx-color-03">%s</p>', $inquiry->companyName, $inquiry->email),
                'JobTitle' => $inquiry->jobTitle,
                'Email' => $inquiry->email,
                'Country' => $inquiry->country,
                'PhoneNumber' => $inquiry->phoneNumber,
                //'Message' => (new UnicodeString($inquiry->message))->truncate(500, '...', false),
                'FromPage' => $inquiry->fromPage ?: 'N/A',
                'FullMessage' => $inquiry->message,
                'CreatedAt' => $inquiry->createdAt->format('M d, Y'),
                'Actions' => '<a href="javascript:;" class="btn btn-xs btn-secondary btn-detail">Detail</a>'
            ];
        }

        return $this->json([
            'data' => $aaData,
            'recordsTotal' => $inquiries->nbData ?: 0,
            'recordsFiltered' => $inquiries->nbData ?: 0
        ]);
    }
}
