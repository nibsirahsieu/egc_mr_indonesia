<?php 

namespace App\Controller\Insight;

use App\Common\SchemaGenerator;
use App\QueryService\MetaPageQueryService;
use App\QueryService\PostQueryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Inflector\EnglishInflector;

final class IndexController extends AbstractController
{
    const MAX_POST = 12;

    public function __construct(private PostQueryService $queryService, private MetaPageQueryService $metaPageQueryService, private SchemaGenerator $schemaGenerator)
    {
    }
    
    #[Route('/insights/{category}', name: 'app_insights_index', methods: ['GET'])]
    public function index(string $category = ""): Response
    {
        $defaultTypeId = null; 
        $pageTitle = 'Insights';
        $postTypes = $this->queryService->postTypes();

        if ($category && count($postTypes) > 0) {
            $filteredPostType = array_filter($postTypes, fn ($postType) => $postType['slug'] === $category);
            if (0 === count($filteredPostType)) {
                //throw not found exception, so that, it can be handle by PageNotFoundListener.
                throw $this->createNotFoundException();
            }

            $postCategory = current($filteredPostType);
            $defaultTypeId = $postCategory['id'];
            $pageTitle = (new EnglishInflector)->pluralize($postCategory['name'])[0];
        }
        
        $metaPage = $this->metaPageQueryService->metaForPage($category ?: 'insights');
        $schema = $this->schemaGenerator->generateArticlesSchema($category, $metaPage?->getMetaTitle() ?: '', $metaPage?->getMetaDescription() ?: '', $this->getArticlesForSchema());

        return $this->render('insight/index.html.twig', [
            'metaPage' => $metaPage,
            'defaultTypeId' => $defaultTypeId,
            'postTypes' => $postTypes,
            'category' => $category,
            'pageTitle' => $pageTitle,
            'schema' => $schema
        ]);
    }

    #[Route('/insights/load-more', name: 'app_insights_load_more', priority: 10, methods: ['GET'])]
    public function loadMore(Request $request): Response
    {
        $lastId = null;
        $lastPublishedAt = null;
        $typeId = $request->query->get('type_id', null);

        if ($request->query->get('last_published_at')) {
            $lastPublishedAt = new \DateTimeImmutable($request->query->get('last_published_at'));
            $lastId = $request->query->getInt('last_id');
        }

        $posts = iterator_to_array($this->queryService->recentPosts(self::MAX_POST, $typeId ? (int) $typeId : null, $lastPublishedAt, $lastId));
        $nbPost = count($posts);

        if (0 === $nbPost) {
            //return 204, so that infinite-scroll able to determine if it is the last page
            return new Response('', Response::HTTP_NO_CONTENT);
        }

        $lastPost = $posts[array_key_last($posts)];
        
        return $this->json([
            'html' => $this->renderView('insight/_data.html.twig', ['posts' => $posts]),
            'lastPublishedAt' => $lastPost->publishedAt->format('Y-m-d'),
            'lastId' => $lastPost->id,
            'nbData' => $nbPost
        ]);
    }

    private function getArticlesForSchema() : array 
    {
        $articles = [];
        $posts = $this->queryService->recentPosts(3);

        foreach ($posts as $post) {
            $articles[] = [
                'headline' => $post->title,
                'url' => $this->generateUrl('app_insights_show', ['category' => $post->category->name, 'slug' => $post->slug]),
                'datePublished' => $post->publishedAt->format('Y-m-d')
            ];
        }
        
        return $articles;
    }
}
