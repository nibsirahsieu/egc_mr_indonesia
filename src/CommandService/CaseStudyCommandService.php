<?php 

namespace App\CommandService;

use App\Entity\CaseStudy;
use App\Entity\PostStatus;
use App\Event\CaseStudyCreated;
use App\Event\CaseStudyDeleted;
use App\Event\CaseStudyUpdated;
use App\Mapper\CaseStudyMapper;
use App\Request\CaseStudyRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CaseStudyCommandService
{
    public function __construct(private EntityManagerInterface $em, private CaseStudyMapper $caseStudyMapper, private EventDispatcherInterface $eventDispatcher)
    {
    }

    public function create(CaseStudyRequest $caseStudyRequest): void
    {
        $caseStudy = new CaseStudy();
        $this->caseStudyMapper->fromRequest($caseStudyRequest, $caseStudy);
        $this->em->persist($caseStudy);
        $this->em->flush();

        if ($image = $caseStudy->getImage()) {
            $image->setUsedBy($caseStudy->getId());
            $this->em->persist($image);
            $this->em->flush();
        }

        $this->eventDispatcher->dispatch(new CaseStudyCreated($caseStudy->getId()));
    }

    public function update(int $id, CaseStudyRequest $request): void
    {
        $caseStudy = $this->findById($id);
        $prevImage = $caseStudy->getImage();

        $this->caseStudyMapper->fromRequest($request, $caseStudy);

        if ($prevImage && $prevImage !== $caseStudy->getImage()) {
            $prevImage->setUsedBy(null);
            $this->em->persist($prevImage);
        }

        $this->em->persist($caseStudy);
        $this->em->flush();

        $this->eventDispatcher->dispatch(new CaseStudyUpdated($caseStudy->getId()));
    }

    public function delete(int $id): void
    {
        $caseStudy = $this->findById($id);
        $this->em->remove($caseStudy);
        $this->em->flush();

        $this->eventDispatcher->dispatch(new CaseStudyDeleted($id));
    }

    public function publish(int $id, ?\DateTimeImmutable $publishedDate = null): void
    {
        $caseStudy = $this->findById($id);
        if ($publishedDate) {
            $caseStudy->setPublishedAt($publishedDate);
        }
        
        $caseStudy->setStatus(PostStatus::PUBLISHED);

        $this->em->persist($caseStudy);
        $this->em->flush();
    }

    public function unPublish(int $id): void
    {
        $caseStudy = $this->findById($id);
        $caseStudy->setStatus(PostStatus::DRAFT);
        
        $this->em->persist($caseStudy);
        $this->em->flush();
    }

    private function findById(int $id): CaseStudy
    {
        $caseStudy = $this->em->getRepository(CaseStudy::class)->find($id);
        if (!$caseStudy) {
            throw new NotFoundHttpException("No Case Study found for id ". $id);
        }

        return $caseStudy;
    }
}
