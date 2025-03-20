<?php 

namespace App\QueryService;

use App\Common\PageData;
use App\Common\PaginateResult;
use App\Entity\DownloadWhitepaperRequest;
use App\Repository\DownloadWhitepaperRequestRepository;
use App\View\WpRequestView;

final class WpRequestQueryService
{
    public function __construct(private DownloadWhitepaperRequestRepository $reqpository)
    {
    }

    public function all(?PageData $pageData): PaginateResult
    {
        $result = [];
        $rows = $this->reqpository->listForAdmin($pageData);
        foreach ($rows as $row) {
            $result[] = new WpRequestView(
                (int) $row['id'],
                $row['first_name'],
                $row['last_name'],
                $row['company_name'],
                $row['job_title'],
                $row['email'],
                $row['country'],
                $row['phone_number'],
                $row['message'],
                (bool) $row['downloaded'],
                $row['title'],
                new \DateTimeImmutable($row['created_at'])
            );
        }

        $nbData = $rows->getReturn();

        return PaginateResult::create($result, $nbData ? (int) $nbData : null);
    }

    public function findByEmailAndWhitepaperId(string $email, int $whitepaperId): ?DownloadWhitepaperRequest
    {
        return $this->reqpository->findByEmailAndWhitepaperId($email, $whitepaperId);
    }
}
