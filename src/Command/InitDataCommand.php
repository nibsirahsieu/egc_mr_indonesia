<?php 

namespace App\Command;

use App\Entity\MetaPage;
use App\Entity\OurService;
use App\Entity\PostType;
use App\Entity\RedirectUrl;
use App\Entity\Sector;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:data:init',
    description: 'Init data for master tables'
)]
final class InitDataCommand extends Command
{
    private const BASE_URL = 'https://marketresearchindonesia.com';

    public function __construct(private readonly EntityManagerInterface $em) 
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->initMetaPages();
        $this->initPostTypes();
        $this->initSectors();
        $this->initServices();

        $this->em->flush();
        
        return Command::SUCCESS;
    }

    private function initMetaPages(): void
    {
        $pages = [
            ['name' => 'Home', 'slug' => 'home'],
            ['name' => 'About', 'slug' => 'about-us'],
            ['name' => 'Sectors', 'slug' => 'sectors'],
            ['name' => 'Services', 'slug' => 'services'],
            ['name' => 'Case Studies', 'slug' => 'case-studies'],
            ['name' => 'Insights', 'slug' => 'insights'],
            ['name' => 'Contact Us', 'slug' => 'contact-us'],
        ];
        foreach ($pages as $page) {
            $metaPage = new MetaPage();
            $metaPage->setName($page['name']);
            $metaPage->setSlug($page['slug']);
            $this->em->persist($metaPage);
        }
    }

    private function initPostTypes(): void
    {
        $articleType = $this->em->getRepository(PostType::class)->findOneBy(['name' => 'Article']);
        if (!$articleType) {
            $articleType = new PostType();
            $articleType->setName('Article');
            $this->em->persist($articleType);
        }

        $wpType = $this->em->getRepository(PostType::class)->findOneBy(['name' => 'Whitepaper']);
        if (!$wpType) {
            $wpType = new PostType();
            $wpType->setName('Whitepaper');
            $this->em->persist($wpType);
        }
    }

    private function initSectors(): void
    {
        $indutries = [
            ['name' => 'Construction', 'slug' => 'construction'],
            ['name' => 'Healthcare', 'slug' => 'healthcare'],
            ['name' => 'Energy', 'slug' => 'energy'],
            ['name' => 'Supply Chain & Logistics', 'slug' => 'logistics'],
            ['name' => 'Transport & Mobility', 'slug' => 'transport-mobility'],
            ['name' => 'Consumer Goods', 'slug' => 'consumer-goods']
        ];
        
        foreach ($indutries as $industry) {
            $sector = $this->em->getRepository(Sector::class)->findOneBy(['name' => $industry['name']]);
            if (!$sector) {
                $sector = new Sector();
            }
            
            $sector->setSlug($industry['slug']);
            $sector->setName($industry['name']);
            $this->em->persist($sector);

            $metaPage = new MetaPage();
            $metaPage->setName('Sector --> ' . $sector->getName());
            $metaPage->setSlug($sector->getSlug());
            $this->em->persist($metaPage);
        }

        //redirect url for sectors
        $sectors = [
            ['old' => 'construction', 'new' => 'construction'],
            ['old' => 'healthcare', 'new' => 'healthcare'],
            ['old' => 'energy', 'new' => 'energy'],
            ['old' => 'supply-chain-logistics', 'new' => 'logistics'],
            ['old' => 'consumer-goods', 'new' => 'consumer-goods'],
            ['old' => 'transport-mobility', 'new' => 'transport-mobility']
        ];
        $sectorsRedirectUrl = new RedirectUrl();
        $sectorsRedirectUrl->setOldUrl(sprintf('%s/our-sectors/', self::BASE_URL));
        $sectorsRedirectUrl->setNewUrl(sprintf('%s/sectors', self::BASE_URL));
        $this->em->persist($sectorsRedirectUrl);

        foreach ($sectors as $sector) {
            $redirectUrl = new RedirectUrl();
            $redirectUrl->setOldUrl(sprintf('%s/our-sectors/%s/', self::BASE_URL, $sector['old']));
            $redirectUrl->setNewUrl(sprintf('%s/sectors/%s', self::BASE_URL, $sector['new']));
            $this->em->persist($redirectUrl);
        }
    }

    private function initServices(): void
    {
        $services = [
            ['old' => 'market-research', 'new' => 'market-research', 'name' => 'Market Research'],
            ['old' => 'strategic-planning', 'new' => 'strategic-planning', 'name' => 'Strategic Planning'],
            ['old' => 'market-entry-strategy', 'new' => 'market-entry-strategy', 'name' => 'Market Entry Strategy'],
            ['old' => 'mergers-and-acquisitions', 'new' => 'mergers-and-acquisitions', 'name' => 'Mergers and Acquisitions'],
            ['old' => 'value-chain-analysis', 'new' => 'value-chain-analysis', 'name' => 'Value Chain Analysis'],
            ['old' => 'competitive-benchmarking', 'new' => 'competitive-benchmarking', 'name' => 'Competitive Benchmarking'],
            ['old' => 'distribution-and-strategic-partnership', 'new' => 'distribution-strategic-partnership', 'name' => 'Distribution & Strategic Partnership'],
            ['old' => 'consumer-behavior-analysis', 'new' => 'consumer-behavior-analysis', 'name' => 'Consumer Behavior Analysis']
        ];
        $servicesRedirectUrl = new RedirectUrl();
        $servicesRedirectUrl->setOldUrl(sprintf('%s/our-services/', self::BASE_URL));
        $servicesRedirectUrl->setNewUrl(sprintf('%s/services', self::BASE_URL));
        $this->em->persist($servicesRedirectUrl);

        foreach ($services as $service) {
            $ourService = new OurService();
            $ourService->setName($service['name']);
            $ourService->setSlug($service['new']);
            $this->em->persist($ourService);
            
            $redirectUrl = new RedirectUrl();
            $redirectUrl->setOldUrl(sprintf('%s/our-services/%s/', self::BASE_URL, $service['old']));
            $redirectUrl->setNewUrl(sprintf('%s/services/%s', self::BASE_URL, $service['new']));
            $this->em->persist($redirectUrl);

            $metaPage = new MetaPage();
            $metaPage->setName('Service --> ' . $ourService->getName());
            $metaPage->setSlug($ourService->getSlug());
            $this->em->persist($metaPage);
        }
    }
}
