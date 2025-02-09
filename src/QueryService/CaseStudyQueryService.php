<?php 

namespace App\QueryService;

use App\Common\PageData;
use App\Common\PaginateResult;
use App\Entity\PostStatus;
use App\Mapper\CaseStudyMapper;
use App\Repository\CaseStudyRepository;
use App\View\CaseStudyListView;
use App\View\CaseStudyView;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CaseStudyQueryService
{
    public function __construct(private CaseStudyRepository $caseStudyRepository, private CaseStudyMapper $mapper)
    {
    }

    public function all(?string $title, ?PageData $pageData = null): PaginateResult
    {
        $result = [];
        $rows = $this->caseStudyRepository->listForAdmin($title, $pageData);
        foreach ($rows as $row) {
            $result[] = new CaseStudyListView(
                (int) $row['id'],
                $row['title'],
                $row['slug'],
                $row['client'],
                $row['published_at'] ? new \DateTimeImmutable($row['published_at']) : null,
                PostStatus::tryFrom($row['status'])
            );
        }

        $nbData = $rows->getReturn();

        return PaginateResult::create($result, $nbData ? (int) $nbData : null);
    }

    public function detail(int|string $idOrSlug): CaseStudyView
    {
        if (is_int($idOrSlug)) {
            $caseStudy = $this->caseStudyRepository->find($idOrSlug);
        } else {
            $caseStudy = $this->caseStudyRepository->findOneBySlug($idOrSlug);
        }

        if (!$caseStudy) {
            throw new NotFoundHttpException("Post not found");
        }

        return $this->mapper->toView($caseStudy);
    }

    public function slugExists(string $slug, ?int $excludeId): bool
    {
        return $this->caseStudyRepository->slugExists($slug, $excludeId);
    }

    public function recentPublished(int $limit = 10, ?\DateTimeImmutable $lastPublishedAt = null, ?int $lastId = null): array
    {
        return $this->caseStudyRepository->recentPublished($limit, $lastPublishedAt, $lastId);
    }

    public function nbPublished(): int
    {
        return $this->caseStudyRepository->nbPublished();
    }
}
