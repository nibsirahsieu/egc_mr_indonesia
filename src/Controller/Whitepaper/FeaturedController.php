<?php 

namespace App\Controller\Whitepaper;

use App\QueryService\PostQueryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class FeaturedController extends AbstractController
{
    public function __construct(private readonly PostQueryService $postQueryService)
    {
    }

    public function featured()
    {
        return $this->render('whitepaper/_featured.html.twig', [
            'whitepaper' => [],
            'featuredTitle' => "",
            'featuredDescription' => ""
        ]);
    }
}
