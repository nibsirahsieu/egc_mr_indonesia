<?php 

namespace App\Controller\Insight;

use App\QueryService\PostQueryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DetailController extends AbstractController
{
    public function __construct(private PostQueryService $postQueryService)
    {
    }

    #[Route('/insights/{slug}', name: 'app_insights_show', methods: ['GET'])]
    public function __invoke(string $slug): Response
    {
        $post = $this->postQueryService->detail($slug);
        if ($post->isWhitepaper()) {
            return $this->redirectToRoute('app_whitepapers_show', ['slug' => $slug], RedirectResponse::HTTP_MOVED_PERMANENTLY);
        }

        $otherPosts = $this->postQueryService->otherPosts(6, $post->getId());

        return $this->render('insight/detail.html.twig', [
            'post' => $post,
            'other_posts' => iterator_to_array($otherPosts)
        ]);
    }   
}
