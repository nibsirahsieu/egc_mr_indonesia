<?php 

namespace App\Controller\Admin\Post;

use App\Common\BreadcrumbBuilder;
use App\Common\PageData;
use App\Controller\BaseController;
use App\QueryService\PostQueryService;
use App\SearchFilter\PostFilter;
use App\View\PostListView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ListController extends BaseController
{
    public function __construct(private PostQueryService $postQueryService)
    {
    }

    #[Route('/insights', name: "app_admin_insights_index")]
    public function index(BreadcrumbBuilder $breadcrumbBuilder): Response
    {
        $breadcrumbBuilder->add('Insights');

        return $this->render('admin/insight/index.html.twig');
    }

    #[Route('/insights/ajax-list', name: "app_insight_ajax_list", options: ['expose' => true])]
    public function ajax(Request $request): Response
    {
        $aaData = [];
        $page = $request->query->getInt('page', 1);
        $length = $request->query->getInt('length', 10);
        $search = $request->query->all('search');
        $postFilter = new PostFilter(null, $search['value'], null);

        $pageData = PageData::create($page, $length);

        $insights = $this->postQueryService->all($postFilter, $pageData);

        foreach ($insights->data as $post) {
            /** @var PostListView $post*/
            $aaData[] = [
                'DT_RowId' => $post->getId(),
                'Title' => $post->getTitle(),
                'Slug' => $post->getSlug(),
                'Category' => $post->getCategory()->name,
                'Author' => $post->getAuthor(),
                'Status' => $post->isDraft() ? '<span class="badge text-bg-secondary">Draft</span>' : '<span class="badge text-bg-success">Published</span>',
                'PublishedAt' => $post->getPublishedAt() ? $post->getPublishedAt()->format('M d, Y') : null,
                'Actions' => $this->buildActions($post)
            ];
        }

        return $this->json([
            'data' => $aaData,
            'recordsTotal' => $insights->nbData ?: 0,
            'recordsFiltered' => $insights->nbData ?: 0
        ]);
    }

    private function buildActions(PostListView $post): string
    {
        $actions = sprintf('<a href="%s" class="btn btn-primary btn-xs">Edit</a>', $this->generateUrl('app_admin_insights_edit', ['id' => $post->getId()]));
        $actions .= sprintf('<a href="%s" class="btn btn-danger btn-xs btn-delete">Delete</a>', $this->generateUrl('app_admin_insights_delete', ['id' => $post->getId()]));
        $actions .= sprintf('<a target="_blank" href="%s" class="btn btn-secondary btn-xs btn-preview">Preview</a>', $this->generateUrl('app_insights_show', ['category' => $post->getCategory()->id, 'slug' => $post->getSlug()]));
        if ($post->isDraft()) {
            if ($post->getPublishedAt()) {
                $actions .= sprintf('<a href="%s" class="btn btn-warning btn-xs btn-publish-unpublish">Publish</a>', $this->generateUrl('app_admin_insights_republish', ['id' => $post->getId()]));
            } else {
                $actions .= sprintf('<a href="%s" class="btn btn-warning btn-xs btn-publish-unpublish">Publish now</a>', $this->generateUrl('app_admin_insights_publish', ['id' => $post->getId()]));
            }
            
        } else {
            $actions .= sprintf('<a href="%s" class="btn btn-warning btn-xs btn-publish-unpublish">Unpublish</a>', $this->generateUrl('app_admin_insights_unpublish', ['id' => $post->getId()]));
        }

        return '<div class="d-grid gap-1">' .  $actions . '</div>';
    }
}
