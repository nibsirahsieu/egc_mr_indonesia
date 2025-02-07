<?php 

namespace App\QueryService;

use App\Common\PageData;
use App\Common\PaginateResult;
use App\Repository\RedirectUrlRepository;
use App\SearchFilter\RedirectUrlFilter;
use App\View\RedirectUrlView;

final class RedirectUrlQueryService
{
    public function __construct(private readonly RedirectUrlRepository $redirectUrlRepository)
    {
        
    }

    public function all(?PageData $pageData, RedirectUrlFilter $filter): PaginateResult
    {
        $result = [];
        $rows = $this->redirectUrlRepository->listForAdmin($pageData, $filter);
        foreach ($rows as $row) {
            $result[] = new RedirectUrlView(
                (int) $row['id'],
                $row['old_url'],
                $row['new_url']
            );
        }

        $nbData = $rows->getReturn();

        return PaginateResult::create($result, $nbData ? (int) $nbData : null);
    }
    
    public function getRedirectUrl(string $fromUrl): ?string
    {
        $parsedUrls = parse_url($fromUrl);
        $requestedUrl = sprintf('https://%s%s', str_replace('www.', '', $parsedUrls['host']), $parsedUrls['path']);

        return $this->redirectUrlRepository->getRedirectedUrl($requestedUrl);
    }
}
