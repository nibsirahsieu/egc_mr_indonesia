<?php 

namespace App\Controller\FileManager;

use App\CommandService\UploadCommandService;
use App\Common\UploadHelper;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GenerateCoverController extends BaseController
{
    public function __construct(private UploadCommandService $uploadCommandService, private UploadHelper $uploadHelper)
    {
    }
    
    #[Route('/upload-files/{id}/generate-cover', name: 'app_upload_files_generate_cover', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function __invoke(int $id): Response
    {
        $response = [];

        $view = $this->uploadCommandService->generateCover($id);

        $response['id'] = $view->getId();
        $response['name'] = $view->getName();
        $response['original_name'] = $view->getOriginalName();
        $response['url'] = $this->uploadHelper->getPublicUrl($view->getRelativePath());

        return $this->json($response);
    }
}
