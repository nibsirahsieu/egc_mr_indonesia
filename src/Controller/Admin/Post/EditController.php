<?php 

namespace App\Controller\Admin\Post;

use App\Common\BreadcrumbBuilder;
use App\Controller\BaseController;
use App\QueryService\PostQueryService;
use App\Repository\PostTypeRepository;
use App\Repository\SectorRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EditController extends BaseController
{
    public function __construct(private PostQueryService $postQueryService, private PostTypeRepository $postTypeRepository, private SectorRepository $sectorRepository)
    {
    }

    #[Route('/insights/{id}/edit', name: "app_admin_insights_edit", methods: ['GET'], requirements: ['id' => '\d+'])]
    public function __invoke(BreadcrumbBuilder $breadcrumbBuilder, int $id): Response
    {
        $post = $this->postQueryService->detail($id);
        $categories = $this->postTypeRepository->findAllOrderedByName();
        $sectors = $this->sectorRepository->findBy([], ['name' => 'ASC']);

        $breadcrumbBuilder
            ->add('Insights', 'app_admin_insights_index')
            ->add('Edit');

        return $this->render('admin/insight/edit.html.twig', [
            'post' => $post,
            'sectors' => $sectors,
            'categories' => $categories
        ]);
    }
}
