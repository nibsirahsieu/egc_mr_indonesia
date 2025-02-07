<?php 

namespace App\Controller\FileManager;

use App\CommandService\UploadCommandService;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DeleteController extends BaseController
{
    public function __construct(private UploadCommandService $uploadCommandService)
    {
    }

    #[Route('/upload-files/{id}/delete', name: 'app_upload_files_delete', requirements: ['id' => '\d+'], methods: ['DELETE'], options: ['expose' => true])]
    public function __invoke(int $id): Response
    {
        $this->uploadCommandService->delete($id);
        
        return $this->json([
            'success' => true
        ]);
    }
}
