<?php 

namespace App\Controller\Admin\Post;

use App\CommandService\PostCommandService;
use App\Controller\BaseController;
use App\Request\PostRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UpdateController extends BaseController
{
    public function __construct(private PostCommandService $postCommandService)
    {
    }

    #[Route('/insights/{id}/update', name: "app_admin_insights_update", methods: ['POST'], requirements: ['id' => '\d+'])]
    public function __invoke(Request $request, int $id): Response
    {
        $data = $request->request->all();
        $this->postCommandService->update($id, new PostRequest(
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

        $this->addFlash('success', sprintf('Post "%s" has been updated.', $data['title']));
        
        return $this->redirectToRoute('app_admin_insights_index');
    }
}
