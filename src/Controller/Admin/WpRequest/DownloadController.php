<?php 

namespace App\Controller\Admin\WpRequest;

use App\Controller\BaseController;
use App\QueryService\WpRequestQueryService;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Attribute\Route;

final class DownloadController extends BaseController
{
    public function __construct(private WpRequestQueryService $wpRequestQueryService)
    {
    }
    
    #[Route('/wp-requests/download', name: 'app_admin_wp_requests_download')]
    public function __invoke(): Response
    {
        $inquiries = $this->wpRequestQueryService->all();

        $response = new StreamedResponse(function () use($inquiries) {
            $writer = new Writer();
            $writer->openToBrowser('ID_wp_requests.xlsx'); // otomatis kirim header

            $writer->addRow(Row::fromValues(['Company Name', 'Country', 'First Name', 'Last Name', 'Title', 'Email']));
            
            foreach ($inquiries->data as $inquiry) {
                $writer->addRow(Row::fromValues([
                    $inquiry->companyName,
                    $inquiry->country,
                    $inquiry->firstName,
                    $inquiry->lastName,
                    $inquiry->jobTitle,
                    $inquiry->email
                ]));
            }

            $writer->close();
        });

        return $response;
    }
}
