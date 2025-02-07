<?php 

namespace App\Common;

use App\Entity\CaseStudy;
use Doctrine\ORM\EntityManagerInterface;

final class SyncCaseStudyImage
{
    public function __construct(private EntityManagerInterface $entityManager, private SyncContentImage $syncContentImage)
    {
    }

    public function proceed(int $postId): void
    {
        /** @var CaseStudy $caseStudy */
        $caseStudy = $this->entityManager->getRepository(CaseStudy::class)->find($postId);
        if (!$caseStudy) {
            return;
        }

        $newIssue = $this->syncContentImage->sync($caseStudy->getIssue(), $caseStudy->getId());
        $newSolution = $this->syncContentImage->sync($caseStudy->getSolution(), $caseStudy->getId());
        $newApproach = $this->syncContentImage->sync($caseStudy->getApproach(), $caseStudy->getId());
        $newRecommendation = $this->syncContentImage->sync($caseStudy->getRecommendation(), $caseStudy->getId());
        $newEngagementRoi = $this->syncContentImage->sync($caseStudy->getEngagementRoi(), $caseStudy->getId());

        $caseStudy->setIssue($newIssue);
        $caseStudy->setSolution($newSolution);
        $caseStudy->setApproach($newApproach);
        $caseStudy->setRecommendation($newRecommendation);
        $caseStudy->setEngagementRoi($newEngagementRoi);
        
        $this->entityManager->persist($caseStudy);
        $this->entityManager->flush();
    }
}
