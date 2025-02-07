<?php 

namespace App\QueryService;

use App\Entity\MetaPage;
use App\Entity\PageType;
use App\Repository\MetaPageRepository;

final class MetaPageQueryService
{
    public function __construct(private readonly MetaPageRepository $metaPageRepository)
    {
        
    }

    /**
     *
     * @return array<MetaPage>
     */
    public function all(): array
    {
        return $this->metaPageRepository->orderedById();
    }

    public function metaForPage(string $slug): ?MetaPage
    {
        return $this->metaPageRepository->findOneBy(['slug' => $slug]);
    }
}
