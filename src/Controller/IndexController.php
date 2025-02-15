<?php 

namespace App\Controller;

use App\QueryService\CaseStudyQueryService;
use App\QueryService\MetaPageQueryService;
use App\QueryService\PostQueryService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends BaseController
{
    public function __construct(
        private PostQueryService $postQueryService, 
        private CaseStudyQueryService $caseStudyQueryService,
        private MetaPageQueryService $metaPageQueryService
    )
    {
    }

    #[Route('', name: 'app_frontpage')]
    public function __invoke(): Response
    {
        $posts = $this->postQueryService->recentPosts(4);
        $caseStudies = $this->caseStudyQueryService->recentPublished(6);
        $metaPage = $this->metaPageQueryService->metaForPage('home');

        return $this->render('index.html.twig', [
            'posts' => $posts,
            'caseStudies' => $caseStudies,
            'sectors' => $this->availableSectors(),
            'services' => $this->availableServices(),
            'metaPage' => $metaPage
        ]);
    }

    private function availableServices(): array
    {
        $services = [];
        $services['market-research'] = ['name' => 'Market Research', 'image' => '/_astro/1-market-research-thumb.j1Df4stY_ZV9bb2.webp'];
        $services['strategic-planning'] = ['name' => 'Strategic Planning', 'image' => '/_astro/2-strategic-planning-thumb.CQPGRbVC_Z1AMOKX.webp'];
        $services['market-entry-strategy'] = ['name' => 'Market Entry Strategy', 'image' => '/_astro/3-market-entry-thumb.CcT8Wxsn_Z1x6UCW.webp'];
        $services['mergers-and-acquisitions'] = ['name' => 'Mergers and Acquisitions', 'image' => '/_astro/4-merger-thumb.DJ8veUSL_13eL1B.webp'];
        $services['value-chain-analysis'] = ['name' => 'Value Chain Analysis', 'image' => '/_astro/5-value-chain-thumb.BgtSpuRD_ZuvkyA.webp'];
        $services['competitive-benchmarking'] = ['name' => 'Competitive Benchmarking', 'image' => '/_astro/6-competitive-thumb.C3A4L5LD_ZKR4wK.webp'];
        $services['distribution-strategic-partnership'] = ['name' => 'Distribution & Strategic Partnership', 'image' => '/_astro/7-distribution-thumb.DfPyMb_v_4JRE2.webp'];
        $services['consumer-behavior-analysis'] = ['name' => 'Consumer Behavior Analysis', 'image' => '/_astro/8-consumer-thumb.3ES9Av4H_ZGieJi.webp'];

        return $services;
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
