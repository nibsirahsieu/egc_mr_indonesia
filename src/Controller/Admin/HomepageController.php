<?php 

namespace App\Controller\Admin;

use App\Common\BreadcrumbBuilder;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomepageController extends BaseController
{
    #[Route('/', name: 'app_admin_homepage')]
    public function __invoke(BreadcrumbBuilder $breadcrumbBuilder): Response
    {
        return $this->render('admin/index.html.twig');
    }
}
