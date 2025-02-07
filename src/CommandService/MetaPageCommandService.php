<?php 

namespace App\CommandService;

use App\Entity\MetaPage;
use App\Repository\MetaPageRepository;
use App\Request\MetaPageRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class MetaPageCommandService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly MetaPageRepository $metaPageRepository
    )
    {
    }

    public function update(int $id, MetaPageRequest $metaPageRequest): void
    {
        $metaPage = $this->findById($id);
        $metaPage->setMetaTitle($metaPageRequest->metaTitle);
        $metaPage->setMetaDescription($metaPageRequest->metaDescription);

        $this->em->persist($metaPage);
        $this->em->flush();
    }

    private function findById(int $id): MetaPage
    {
        $metaPage = $this->metaPageRepository->find($id);
        if (!$metaPage) {
            throw new NotFoundHttpException("Meta page not found");
        }

        return $metaPage;
    }
}
