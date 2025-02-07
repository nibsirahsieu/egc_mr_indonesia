<?php 

namespace App\Common;

use SlopeIt\BreadcrumbBundle\Service\BreadcrumbBuilder as SlopeItBreadcrumbBuilder;

final class BreadcrumbBuilder
{
    public function __construct(private SlopeItBreadcrumbBuilder $breadcrumbBuilder, string $homeText = 'Home', string $homeRoute = 'homepage')
    {
        $this->breadcrumbBuilder->addItem($homeText, $homeRoute);
    }

    public function add(string $text, ?string $route = null, $parameters = []): BreadcrumbBuilder
    {
        $this->breadcrumbBuilder->addItem($text, $route, $parameters);
        
        return $this;
    }
}
