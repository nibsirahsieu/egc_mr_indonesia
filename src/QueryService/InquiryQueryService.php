<?php 

namespace App\QueryService;

use App\Common\PageData;
use App\Common\PaginateResult;
use App\Repository\InquiryRepository;
use App\View\InquiryView;

final class InquiryQueryService
{
    public function __construct(private InquiryRepository $inquiryRepository)
    {
    }

    public function all(?PageData $pageData): PaginateResult
    {
        $result = [];
        $rows = $this->inquiryRepository->listForAdmin($pageData);
        foreach ($rows as $row) {
            $result[] = new InquiryView(
                (int) $row['id'],
                $row['first_name'],
                $row['last_name'],
                $row['company_name'],
                $row['job_title'],
                $row['email'],
                $row['country'],
                $row['phone_number'],
                $row['message'],
                $row['from_page']
            );
        }

        $nbData = $rows->getReturn();

        return PaginateResult::create($result, $nbData ? (int) $nbData : null);
    }
}
