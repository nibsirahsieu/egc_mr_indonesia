<?php 

namespace App\QueryService;

use App\Common\PageData;
use App\Common\PaginateResult;
use App\Entity\PostStatus;
use App\Mapper\PostMapper;
use App\Repository\PostRepository;
use App\SearchFilter\PostFilter;
use App\View\FeaturedWhitepaperView;
use App\View\PostListView;
use App\View\PostView;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class PostQueryService
{
    public function __construct(private PostRepository $postRepository, private PostMapper $mapper)
    {
    }

    public function all(PostFilter $filter, ?PageData $pageData = null): PaginateResult
    {
        $result = [];
        $rows = $this->postRepository->listForAdmin($filter, $pageData);
        foreach ($rows as $row) {
            $result[] = new PostListView(
                (int) $row['id'],
                $row['category'],
                $row['title'],
                $row['slug'],
                $row['author'],
                $row['published_at'] ? new \DateTimeImmutable($row['published_at']) : null,
                PostStatus::tryFrom($row['status'])
            );
        }

        $nbData = $rows->getReturn();

        return PaginateResult::create($result, $nbData ? (int) $nbData : null);
    }

    public function detail(int|string $idOrSlug): PostView
    {
        $post = is_int($idOrSlug) ? $this->postRepository->find($idOrSlug) : $this->postRepository->findOneBySlug($idOrSlug);
        if (!$post) {
            throw new NotFoundHttpException("Post not found");
        }

        return $this->mapper->toView($post);
    }

    public function slugExists(string $slug, ?int $excludeId): bool
    {
        return $this->postRepository->slugExists($slug, $excludeId);
    }

    public function recentPosts(int $limit = 10, ?\DateTimeImmutable $latestPublishedAt = null, ?int $latestId = null): iterable
    {
        return $this->postRepository->recentPosts($limit, $latestPublishedAt, $latestId);
    }

    public function recentWhitepapers(int $limit = 10, ?\DateTimeImmutable $latestPublishedAt = null, ?int $latestId = null): iterable
    {
        return $this->postRepository->recentWhitepapers($limit, $latestPublishedAt, $latestId);
    }

    public function otherPosts(int $limit, int $excludeId): iterable
    {
        return $this->postRepository->otherPosts($limit, $excludeId);
    }

    public function nbPosts(): int
    {
        return $this->postRepository->nbPosts();
    }

    public function nbWhitepapers(): int
    {
        return $this->postRepository->nbWhitepapers();
    }

    public function featuredWhitepaper(string $slug): FeaturedWhitepaperView
    {
        $array = $this->postRepository->featuredWhitepaper($slug);

        return new FeaturedWhitepaperView(
            (int) $array['id'],
            $array['slug'],
            $array['title'],
            [
                'url' => $array['h_relative_path'],
                'hash' => $array['h_hash']
            ],
            [
                'url' => $array['t_relative_path'],
                'hash' => $array['t_hash']
            ]
        );
    }
}
