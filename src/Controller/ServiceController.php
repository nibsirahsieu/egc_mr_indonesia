<?php 

namespace App\Controller;

use App\QueryService\MetaPageQueryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ServiceController extends AbstractController
{
    public function __construct(private MetaPageQueryService $metaPageQueryService)
    {
    }
    
    #[Route('/services', name: 'app_services_index', methods: ['GET'])]
    public function index(): Response
    {
        $metaPage = $this->metaPageQueryService->metaForPage('services');
        
        return $this->render("service/index.html.twig", [
            'services' => $this->availableServices(),
            'metaPage' => $metaPage
        ]);
    }

    #[Route('/services/{slug}', name: 'app_services_show', methods: ['GET'])]
    public function detail(string $slug): Response
    {
        $cleanSlug = str_replace('-', '_', $slug);
        $metaPage = $this->metaPageQueryService->metaForPage($slug);

        return $this->render("service/{$cleanSlug}.html.twig", [
            'services' => $this->availableServices(),
            'metaPage' => $metaPage,
            'slug' => $slug
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
}
