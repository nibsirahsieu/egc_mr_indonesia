<?php 

namespace App\Controller\Admin\MetaSection;

use App\CommandService\MetaPageCommandService;
use App\Controller\BaseController;
use App\Request\MetaPageRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class UpdateController extends BaseController
{
    public function __construct(private MetaPageCommandService $commandService)
    {
    }

    #[Route('/meta-sections/{id}/update', name: "app_admin_meta_sections_update", methods: ['POST'], requirements: ['id' => '\d+'], options: ['expose' => true])]
    public function __invoke(int $id, #[MapRequestPayload()] MetaPageRequest $metaPageRequest): Response
    {
        $this->commandService->update($id, $metaPageRequest);

        return $this->json([
            'success' => true
        ]);
    }
}
