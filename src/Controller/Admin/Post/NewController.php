<?php 

namespace App\Controller\Admin\Post;

use App\Common\BreadcrumbBuilder;
use App\Controller\BaseController;
use App\Repository\PostTypeRepository;
use App\Repository\SectorRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class NewController extends BaseController
{
    public function __construct(private PostTypeRepository $postTypeRepository, private SectorRepository $sectorRepository)
    {
    }

    #[Route('/insights/new', name: "app_admin_insights_new", methods: ['GET'])]
    public function __invoke(BreadcrumbBuilder $breadcrumbBuilder): Response
    {
        $categories = $this->postTypeRepository->findAllOrderedByName();
        $sectors = $this->sectorRepository->findBy([], ['name' => 'ASC']);

        $breadcrumbBuilder
            ->add('Insights', 'app_admin_insights_index')
            ->add('New');

        return $this->render('admin/insight/new.html.twig', [
            'categories' => $categories,
            'sectors' => $sectors
        ]);
    }
}
