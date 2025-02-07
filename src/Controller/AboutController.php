<?php 

namespace App\Controller;

use App\QueryService\MetaPageQueryService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AboutController extends BaseController
{
    public function __construct(private MetaPageQueryService $metaPageQueryService)
    {
    }

    #[Route('/about-us', name: 'app_about')]
    public function __invoke(): Response
    {
        $metaPage = $this->metaPageQueryService->metaForPage('about-us');
        
        return $this->render('about.html.twig', [
            'metaPage' => $metaPage
        ]);
    }
}
