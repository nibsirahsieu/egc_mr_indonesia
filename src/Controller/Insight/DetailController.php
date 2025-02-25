<?php 

namespace App\Controller\Insight;

use App\QueryService\PostQueryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DetailController extends AbstractController
{
    public function __construct(private PostQueryService $postQueryService)
    {
    }

    #[Route('/insights/{category}/{slug}', name: 'app_insights_show', methods: ['GET'])]
    public function __invoke(string $category, string $slug): Response
    {
        $post = $this->postQueryService->detail($slug);
        $otherPosts = $this->postQueryService->otherPosts(6, $post->getId());
        
        if ($post->isWhitepaper()) {
            $headings = $this->extractHeadings($post->getContent());
            
            return $this->render('insight/detail_whitepaper.html.twig', [
                'post' => $post,
                'other_posts' => iterator_to_array($otherPosts),
                'headings' => $headings,
                'category' => $category
            ]);
        }

        return $this->render('insight/detail.html.twig', [
            'post' => $post,
            'category' => $category,
            'other_posts' => iterator_to_array($otherPosts)
        ]);
    }
    
    private function extractHeadings(string $content): array
    {
        $tags = [];
        libxml_use_internal_errors(true);

        $doc = new \DOMDocument();
        $doc->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
        $xpath = new \DOMXpath($doc);
        $htags = $xpath->query('//h1 | //h2 | //h3 | //h4');

        /** @var \DOMElement $htag */
        foreach ($htags as $htag) {
            if ($id = $htag->id) {
                $tags[] = [
                    'id' => $id,
                    'value' => $htag->nodeValue,
                    'level' => intval(str_ireplace('h', '', $htag->tagName))
                ];
            }
        }

        return $tags;
    }
}
