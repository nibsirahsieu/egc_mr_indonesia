<?php 

namespace App\Controller;

use App\QueryService\MetaPageQueryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SectorController extends AbstractController
{
    public function __construct(private MetaPageQueryService $metaPageQueryService)
    {
    }
    
    #[Route('/sectors', name: 'app_sectors_index', methods: ['GET'])]
    public function index(): Response
    {
        $metaPage = $this->metaPageQueryService->metaForPage('sectors');
        
        return $this->render('sector/index.html.twig', [
            'sectors' => $this->availableSectors(),
            'metaPage' => $metaPage
        ]);
    }

    #[Route('/sectors/{slug}', name: 'app_sectors_show', methods: ['GET'])]
    public function detail(string $slug): Response
    {
        $metaPage = $this->metaPageQueryService->metaForPage($slug);
        $cleanSlug = str_replace('-', '_', $slug);

        return $this->render("sector/{$cleanSlug}.html.twig", [
            'sectors' => $this->availableSectors(),
            'metaPage' => $metaPage,
            'slug' => $slug
        ]);
    }

    private function availableSectors(): array
    {
        $sectors = [];
        $sectors['construction'] = ['name' => 'Construction', 'image' => '/_astro/1-sector-construction-crane-2023-11-27-05-16-44-utc.BQdACh03_2j4kUO.webp'];
        $sectors['healthcare'] = ['name' => 'Healthcare', 'image' => '/_astro/2-sector-close-up-with-stethoscope-in-the-background-you-se-2023-11-27-05-25-55-utc.BqleVDf7_ZdRKru.webp'];
        $sectors['energy'] = ['name' => 'Energy', 'image' => '/_astro/3-sector-solar-energy-panels-wind-power-and-electricity-py-2023-11-27-05-22-47-utc.wVTUL3Wh_Z2geDtD.webp'];
        $sectors['logistics'] = ['name' => 'Supply Chain & Logistics', 'image' => '/_astro/4-sector-containers-in-international-shipping-dock-waiting-2023-11-27-05-15-39-utc.DJWLiroV_ZOEbvQ.webp'];
        $sectors['transport-mobility'] = ['name' => 'Transport & Mobility', 'image' => '/_astro/5-sector-mobility.ZRj8Kl2b_ZIr2rv.webp'];
        $sectors['consumer-goods'] = ['name' => 'Consumer Goods', 'image' => '/_astro/6-sector-shopping-cart-full-of-food-products-over-supermark-2023-11-27-05-25-42-utc.BmQ7mxF-_ZgQsn6.webp'];

        return $sectors;
    }
}
