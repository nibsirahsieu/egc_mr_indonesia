<?php 

namespace App\Controller\Admin\Post;

use App\CommandService\PostCommandService;
use App\Controller\BaseController;
use App\Request\PostRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CreateController extends BaseController
{
    public function __construct(private PostCommandService $postCommandService)
    {
    }

    #[Route('/insights/create', name: "app_admin_insights_create", methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        $data = $request->request->all();
        $this->postCommandService->create(new PostRequest(
            $data['title'],
            $data['slug'],
            $data['summary'] ?? null,
            $data['content'],
            $data['metaTitle'] ?? null,
            $data['metaDescription'] ?? null,
            $data['headerImageId'] ? (int) $data['headerImageId'] : null,
            $data['sectorIds'] ?? [],
            (int) $data['typeId'],
            $data['fileId'] ? (int) $data['fileId'] : null,
            $data['publishedAt'] ? new \DateTimeImmutable($data['publishedAt']) : null,
            $data['author'] ?? null,
            $data['thumbnailId'] ? (int) $data['thumbnailId'] : null,
            (int) $data['status']
        ));

        if (0 === (int) $data['status']) {
            //redirect to preview page
        }

        $this->addFlash('success', 'Post has been added.');
        
        return $this->redirectToRoute('app_admin_insights_index');
    }
}
