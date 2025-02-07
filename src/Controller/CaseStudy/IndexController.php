<?php 

namespace App\Controller\CaseStudy;

use App\QueryService\CaseStudyQueryService;
use App\QueryService\MetaPageQueryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    const MAX_POST = 12;

    public function __construct(private CaseStudyQueryService $queryService, private MetaPageQueryService $metaPageQueryService)
    {
    }

    #[Route('/case-studies', name: 'app_case_studies_index', methods: ['GET'])]
    public function index(): Response
    {
        $nbPost = $this->queryService->nbPublished();
        $metaPage = $this->metaPageQueryService->metaForPage('case-studies');
        
        return $this->render('caseStudy/index.html.twig', [
            'nbPost' => $nbPost,
            'metaPage' => $metaPage
        ]);
    }

    #[Route('/case-studies/load-more', name: 'app_case_studies_load_more', priority: 10, methods: ['GET'])]
    public function loadMore(Request $request): Response
    {
        $lastPublishedAt = null;
        $lastId = null;

        if ($request->query->get('last_published_at')) {
            $lastPublishedAt = new \DateTimeImmutable($request->query->get('last_published_at'));
            $lastId = $request->query->getInt('last_id');
        }

        $posts = $this->queryService->recentPublished(self::MAX_POST, $lastPublishedAt, $lastId);
        $nbPost = count($posts);
        $lastPost = $nbPost > 0 ? $posts[array_key_last($posts)] : null;

        return $this->json([
            'html' => $this->renderView('caseStudy/_data.html.twig', ['caseStudies' => $posts]),
            'lastPublishedAt' => $lastPost?->publishedAt?->format('Y-m-d') ?: '',
            'lastId' => $lastPost?->id ?: '',
            'nbData' => $nbPost
        ]);
    }
}
