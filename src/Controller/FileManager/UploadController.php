<?php 

namespace App\Controller\FileManager;

use Oneup\UploaderBundle\Controller\DropzoneController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/upload-files')]
class UploadController extends DropzoneController
{
    #[Route('', name: 'app_upload_files', methods: ['POST'])]
    public function __invoke(): Response
    {
        return $this->upload();
    }
}