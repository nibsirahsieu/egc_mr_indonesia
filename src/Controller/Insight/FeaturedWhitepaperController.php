<?php 

namespace App\Controller\Insight;

use App\QueryService\PostQueryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class FeaturedWhitepaperController extends AbstractController
{
    public function __construct(private readonly PostQueryService $postQueryService)
    {
    }

    public function featured()
    {
        $whitepaper = null;
        
        return $this->render('insight/_featured_whitepaper.html.twig', [
            'whitepaper' => $whitepaper,
            'featuredTitle' => "Explore ASEAN's Rapidly Evolving Consumer Market",
            'featuredDescription' => "Dive into post-Covid trends, eco-conscious movements, and strategies to capture Southeast Asia's next wave of growth."
        ]);
    }
}
