<?php 

namespace App\Controller\Whitepaper;

use App\QueryService\PostQueryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    const MAX_POST = 12;

    public function __construct(private PostQueryService $queryService)
    {
    }
    
    #[Route('/whitepapers', name: 'app_whitepapers_index', methods: ['GET'])]
    public function index(): Response
    {
        $nbPost = $this->queryService->nbWhitepapers();
        
        return $this->render('whitepaper/index.html.twig', [
            'nbPost' => $nbPost
        ]);
    }

    #[Route('/whitepapers/load-more', name: 'app_whitepapers_load_more', priority: 10, methods: ['GET'])]
    public function loadMore(Request $request): Response
    {
        $lastPublishedAt = null;
        $lastId = null;

        if ($request->query->get('last_published_at')) {
            $lastPublishedAt = new \DateTimeImmutable($request->query->get('last_published_at'));
            $lastId = $request->query->getInt('last_id');
        }

        $posts = iterator_to_array($this->queryService->recentWhitepapers(self::MAX_POST, $lastPublishedAt, $lastId));
        $nbPost = count($posts);
        $lastPost = $nbPost > 0 ? $posts[array_key_last($posts)] : null;

        return $this->json([
            'html' => $this->renderView('whitepaper/_data.html.twig', ['posts' => $posts]),
            'lastPublishedAt' => $lastPost?->publishedAt?->format('Y-m-d') ?: '',
            'lastId' => $lastPost?->id ?: '',
            'nbData' => $nbPost
        ]);
    }
}
