<?php 

namespace App\Controller;

use App\QueryService\MetaPageQueryService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ContactUsController extends BaseController
{
    public function __construct(private MetaPageQueryService $metaPageQueryService)
    {
    }
    
    #[Route('/contact-us', name: 'app_contact_us')]
    public function __invoke(): Response
    {
        $metaPage = $this->metaPageQueryService->metaForPage('contact-us');
        
        return $this->render('contact_us.html.twig', [
            'metaPage' => $metaPage
        ]);
    }
}
