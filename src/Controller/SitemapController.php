<?php 

namespace App\Controller;

use App\Repository\CaseStudyRepository;
use App\Repository\OurServiceRepository;
use App\Repository\PostRepository;
use App\Repository\SectorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class SitemapController extends AbstractController
{
    public function __construct(
        private readonly SectorRepository $sectorRepository,
        private readonly OurServiceRepository $ourServiceRepository,
        private readonly PostRepository $postRepository,
        private readonly CaseStudyRepository $caseStudyRepository
    )
    {
        
    }
    #[Route('/sitemap.{_format}', name: 'app_sitemap', requirements: ['_format' => 'html|xml'], format: 'xml')]
    public function index(Request $request): Response
    {
        $urls = [];
        $hostname = $request->getSchemeAndHttpHost();
        $today = new \DateTimeImmutable();

        // Static URLs
        $urls[] = ['loc' => $this->generateUrl('app_frontpage', [], UrlGeneratorInterface::ABSOLUTE_URL), 'priority' => '1.00', 'lastmod' => $today->format('c'), 'changefreq' => 'daily'];
        $urls[] = ['loc' => $this->generateUrl('app_about', [], UrlGeneratorInterface::ABSOLUTE_URL), 'priority' => '1.00', 'lastmod' => $today->format('c'), 'changefreq' => 'daily'];
        $urls[] = ['loc' => $this->generateUrl('app_sectors_index', [], UrlGeneratorInterface::ABSOLUTE_URL), 'priority' => '1.00', 'lastmod' => $today->format('c'), 'changefreq' => 'daily'];
        $urls[] = ['loc' => $this->generateUrl('app_services_index', [], UrlGeneratorInterface::ABSOLUTE_URL), 'priority' => '1.00', 'lastmod' => $today->format('c'), 'changefreq' => 'daily'];
        $urls[] = ['loc' => $this->generateUrl('app_case_studies_index', [], UrlGeneratorInterface::ABSOLUTE_URL), 'priority' => '1.00', 'lastmod' => $today->format('c'), 'changefreq' => 'daily'];
        $urls[] = ['loc' => $this->generateUrl('app_insights_index', [], UrlGeneratorInterface::ABSOLUTE_URL), 'priority' => '1.00', 'lastmod' => $today->format('c'), 'changefreq' => 'daily'];
        $urls[] = ['loc' => $this->generateUrl('app_contact_us', [], UrlGeneratorInterface::ABSOLUTE_URL), 'priority' => '1.00', 'lastmod' => $today->format('c'), 'changefreq' => 'daily'];

        $sectors = $this->sectorRepository->orderedById();
        foreach ($sectors as $sector) {
            $urls[] = ['loc' => $this->generateUrl('app_sectors_show', ['slug' => $sector->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL), 'priority' => '1.00', 'lastmod' => $today->format('c'), 'changefreq' => 'daily'];
        }

        $services = $this->ourServiceRepository->orderedById();
        foreach ($services as $service) {
            $urls[] = ['loc' => $this->generateUrl('app_services_show', ['slug' => $service->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL), 'priority' => '1.00', 'lastmod' => $today->format('c'), 'changefreq' => 'daily'];
        }

        $posts = $this->postRepository->listForSitemap();
        foreach ($posts as $post) {
            $urls[] = [
                'loc' => $this->generateUrl('app_insights_show', ['slug' => $post['slug'], 'category' => $post['category_slug']], UrlGeneratorInterface::ABSOLUTE_URL), 
                'priority' => '1.00',
                'lastmod' => (new \DateTimeImmutable($post['updated_at']))->format('c'),
                'changefreq' => 'daily',
            ];
        }

        $caseStudies = $this->caseStudyRepository->listForSitemap();
        foreach ($caseStudies as $caseStudy) {
            $urls[] = [
                'loc' => $this->generateUrl('app_case_studies_show', ['slug' => $caseStudy['slug']], UrlGeneratorInterface::ABSOLUTE_URL), 
                'priority' => '1.00',
                'lastmod' => (new \DateTimeImmutable($caseStudy['updated_at']))->format('c'),
                'changefreq' => 'daily',
            ];
        }

        $xml = $this->renderView('sitemap/sitemap.xml.twig', [
            'urls' => $urls,
            'hostname' => $hostname
        ]);
    
        return new Response($xml, 200, ['Content-Type' => 'text/xml']);
    }
}
