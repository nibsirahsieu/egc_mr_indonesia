<?php 

namespace App\Controller\Admin\Post;

use App\Controller\BaseController;
use App\QueryService\PostQueryService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CheckSlugController extends BaseController
{
    public function __construct(private PostQueryService $postQueryService)
    {
    }

    #[Route('/insights/check-slug', name: "app_admin_insights_check_slug", methods: ['POST'], options: ['expose' => true])]
    public function __invoke(Request $request): Response
    {
        $slug = $request->request->get('slug');
        $excludeId = $request->request->get('excludeId');

        $isSlugExists = $this->postQueryService->slugExists($slug, $excludeId ? (int) $excludeId : null);

        return $this->json([
            'slug_exists' => $isSlugExists
        ]);
    }
}
