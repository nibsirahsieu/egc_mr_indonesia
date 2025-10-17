<?php

namespace App\Common;

use Spatie\SchemaOrg\Schema;

final class SchemaGenerator
{
    public function __construct(private AppConfig $appConfig)
    {
    }
    
    public function generateBaseSchema(string $title, string $logo): string
    {
        $base = $this->appConfig->baseUrl;

        // Organization
        $organization = Schema::organization()
            ->id($base . '/#org')
            ->name($title)
            ->url($base . '/')
            ->logo(
                Schema::imageObject()
                    ->url($base . $logo)
            )
            ->sameAs(['https://www.instagram.com/eurogroupconsultingmea/'])
            ->contactPoint([
                Schema::contactPoint()
                    ->contactType('Business Development')
                    ->email($this->appConfig->contactEmail)
                    ->telephone($this->appConfig->contactNo)
                    ->areaServed($this->appConfig->addressCountry)
                    ->availableLanguage(['en'])
            ]);

        // Professional Service (local business)
        $local = Schema::professionalService()
            ->id($base . '/#local')
            ->name($title)
            ->image($base . '/_astro/getty-images-Oe7MfGGO_h0-unsplash.D-At4Vgx_1qlAmb.webp')
            ->url($base . '/')
            ->telephone($this->appConfig->contactNo)
            ->parentOrganization(Schema::thing()->id($base . '/#org'));

        // Website
        $website = Schema::webSite()
            ->id($base . '/#website')
            ->url($base . '/')
            ->name($title . ' | Eurogroup Consulting')
            ->publisher(Schema::thing()->id($base . '/#org'))
            ->potentialAction(
                Schema::searchAction()
                    ->target($base . '/?s={search_term_string}')
                    ->setProperty('query-input', 'required name=search_term_string')
            );

        // Merge all as a @graph
        $graph = [
            $organization->toArray(),
            $local->toArray(),
            $website->toArray(),
        ];

        return '<script type="application/ld+json">'
            . json_encode(['@context' => 'https://schema.org', '@graph' => $graph], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
            . '</script>';
    }

    public function generateHomeSchema(string $title, string $description, string $heroImage): string
    {
        $base = $this->appConfig->baseUrl;

        $breadcrumb = Schema::breadcrumbList()->itemListElement([
            Schema::listItem()->position(1)->name('Home')->item($base . '/'),
        ]);

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            '@id' => $base . '/#webpage',
            'url' => $base . '/',
            'name' => $title,
            'description' => $description,
            'isPartOf' => [
                '@id' => $base . '/#website',
            ],
            'about' => [
                '@id' => $base . '/#org',
            ],
            'primaryImageOfPage' => [
                '@type' => 'ImageObject',
                'url' => $base . $heroImage,
            ],
            'breadcrumb' => $breadcrumb->toArray(),
        ];

        return '<script type="application/ld+json">'
            . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
            . '</script>';
    }


    public function generateArticlesSchema(string $category, string $title, string $description, array $articles): string
    {
        // Breadcrumb
        $breadcrumbElements = [
            Schema::listItem()->position(1)->name('Home')->item($this->appConfig->baseUrl . '/'),
            Schema::listItem()->position(2)->name('Insights')->item($this->appConfig->baseUrl . '/insights')
        ];
        if ($category !== '') {
            $breadcrumbElements[] = Schema::listItem()->position(3)->name(ucfirst($category))->item($this->appConfig->baseUrl . '/insights/' . $category);
        }
        $breadcrumb = Schema::breadcrumbList()->itemListElement($breadcrumbElements);

        // Dynamic articles list
        $itemListElements = [];
        foreach ($articles as $i => $article) {
            $itemListElements[] = Schema::listItem()
                ->position($i + 1)
                ->item(
                    Schema::article()
                        ->headline($article['headline'])
                        ->url($this->appConfig->baseUrl . $article['url'])
                        ->datePublished($article['datePublished'])
                );
        }

        $itemList = Schema::itemList()
            ->setProperty('@id', $this->appConfig->baseUrl . '' !== $category ? "/insights/{$category}/#itemlist" : '/insights/#itemlist')
            ->name('Latest ' . ucfirst($category))
            ->itemListOrder('https://schema.org/ItemListOrderDescending')
            ->itemListElement($itemListElements);

        $webPage = Schema::webPage()
            ->setProperty('@id', $this->appConfig->baseUrl . '' !== $category ? "/insights/{$category}/#webpage" : '/insights/#webpage')
            ->url($this->appConfig->baseUrl . "/insights/{$category}")
            ->name($title)
            ->description($description)
            ->isPartOf(Schema::webSite()->setProperty('@id', $this->appConfig->baseUrl . '/#website'))
            ->breadcrumb($breadcrumb)
            ->mainEntity($itemList);

        // Build graph
        $graph = [
            $webPage->toArray(),
        ];

        $schema = [
            '@context' => 'https://schema.org',
            '@graph' => $graph,
        ];

        return '<script type="application/ld+json">'
            . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
            . '</script>';
    }

    public function generateContactSchema(string $title): string
    {
        $professionalService = Schema::professionalService()
            ->setProperty('@id', sprintf('%s%s/#local', $this->appConfig->baseUrl, '/contact_us'))
            ->name($title)
            ->url(sprintf('%s%s', $this->appConfig->baseUrl, '/contact_us'))
            ->image(sprintf('%s%s', $this->appConfig->baseUrl, '/_astro/getty-images-Oe7MfGGO_h0-unsplash.D-At4Vgx_1qlAmb.webp'))
            ->telephone($this->appConfig->contactNo)
            ->email($this->appConfig->contactEmail)
            ->openingHoursSpecification([
                Schema::openingHoursSpecification()
                    ->dayOfWeek(['Monday','Tuesday','Wednesday','Thursday','Friday'])
                    ->opens('09:00')
                    ->closes('18:00')
            ])
            ->parentOrganization(Schema::organization()->setProperty('@id', sprintf('%s/#org', $this->appConfig->baseUrl)))
        ;

        return $professionalService->toScript();
    }

    public function generateSectorsSchema(string $title, string $description, array $sectors): string
    {
        $base = $this->appConfig->baseUrl;

        // Breadcrumb
        $breadcrumb = Schema::breadcrumbList()->itemListElement([
            Schema::listItem()->position(1)->name('Home')->item($base . '/'),
            Schema::listItem()->position(2)->name('Sectors')->item($base . '/sectors'),
        ]);

        // ItemList of sectors
        $itemListElements = [];
        foreach ($sectors as $i => $sector) {
            $itemListElements[] = Schema::listItem()
                ->position($i + 1)
                ->item(
                    Schema::webPage()
                        ->name($sector['name'])
                        ->url($base . '/sectors/' . $sector['slug'])
                );
        }

        $itemList = Schema::itemList()
            ->setProperty('@id', $base . '/sectors/#itemlist')
            ->name('Sector Pages')
            ->itemListOrder('https://schema.org/ItemListOrderAscending')
            ->itemListElement($itemListElements);

        // WebPage schema
        $webPage = Schema::webPage()
            ->setProperty('@id', $base . '/sectors/#webpage')
            ->url($base . '/sectors')
            ->name($title)
            ->description($description)
            ->isPartOf(Schema::webSite()->setProperty('@id', $base . '/#website'))
            ->breadcrumb($breadcrumb)
            ->mainEntity($itemList);

        // Combine into graph
        $graph = [
            $webPage->toArray(),
        ];

        $schema = [
            '@context' => 'https://schema.org',
            '@graph' => $graph,
        ];

        return '<script type="application/ld+json">'
            . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
            . '</script>';
    }

    public function generateServicesSchema(string $title, string $description, array $services): string
    {
        $base = $this->appConfig->baseUrl;
        $serviceUrl = '/services';

        // Breadcrumb
        $breadcrumb = Schema::breadcrumbList()->itemListElement([
            Schema::listItem()->position(1)->name('Home')->item($base . '/'),
            Schema::listItem()->position(2)->name('Our Services')->item($base . $serviceUrl),
        ]);

        // ItemList of sectors
        $itemListElements = [];
        foreach ($services as $i => $service) {
            $itemListElements[] = Schema::listItem()
                ->position($i + 1)
                ->item(
                    Schema::webPage()
                        ->name($service['name'])
                        ->url($base . $serviceUrl . '/' . $service['slug'])
                );
        }

        $itemList = Schema::itemList()
            ->setProperty('@id', $base . "{$serviceUrl}/#itemlist")
            ->name('Services Pages')
            ->itemListOrder('https://schema.org/ItemListOrderAscending')
            ->itemListElement($itemListElements);

        // WebPage schema
        $webPage = Schema::webPage()
            ->setProperty('@id', $base . "{$serviceUrl}/#webpage")
            ->url($base . $serviceUrl)
            ->name($title)
            ->description($description)
            ->isPartOf(Schema::webSite()->setProperty('@id', $base . '/#website'))
            ->breadcrumb($breadcrumb)
            ->mainEntity($itemList);

        // Combine into graph
        $graph = [
            $webPage->toArray(),
        ];

        $schema = [
            '@context' => 'https://schema.org',
            '@graph' => $graph,
        ];

        return '<script type="application/ld+json">'
            . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
            . '</script>';
    }
}
