<?php

namespace App\Mapper;

use App\Common\IdName;
use App\Entity\CaseStudy;
use App\Entity\FileUploaded;
use App\Entity\PostStatus;
use App\Entity\Sector;
use App\Request\CaseStudyRequest;
use App\View\CaseStudyView;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;

final class CaseStudyMapper
{
    public function __construct(private EntityManagerInterface $entityManager, private UploadMapper $uploadMapper, private HtmlSanitizerInterface $appPostSanitizer)
    {
    }

    public function fromRequest(CaseStudyRequest $request, CaseStudy $caseStudy)
    {
        $status = PostStatus::tryFrom($request->status);
        $isPublished = $status === PostStatus::PUBLISHED;

        $image = $request->imageId ? $this->entityManager->getRepository(FileUploaded::class)->find((int) $request->imageId) : null;
        if ($image) {
            $image->setUsedBy($caseStudy->getId());
            $caseStudy->setImage($image);
        }

        $caseStudy->setTitle($request->title);
        $caseStudy->setSlug($request->slug);
        $caseStudy->setClient($request->client);
        $caseStudy->setIssue($this->appPostSanitizer->sanitize($request->issue));
        $caseStudy->setSolution($this->appPostSanitizer->sanitize($request->solution));
        $caseStudy->setApproach($this->appPostSanitizer->sanitize($request->approach));
        $caseStudy->setRecommendation($this->appPostSanitizer->sanitize($request->recommendation));
        $caseStudy->setEngagementRoi($this->appPostSanitizer->sanitize($request->engagementRoi));
        $caseStudy->setMetaTitle($request->metaTitle);
        $caseStudy->setMetaDescription($request->metaDescription);
        $caseStudy->setStatus($status);
        $caseStudy->setPublishedAt($request->publishedAt);
        if ($isPublished && null === $caseStudy->getPublishedAt()) {
            $caseStudy->setPublishedAt(new \DateTimeImmutable());
        }

        $requestedSectors = $this->entityManager->getRepository(Sector::class)->findBy(['id' => $request->sectorIds]);
        $caseStudy->syncSectors($requestedSectors);
    }

    public function toView(CaseStudy $caseStudy): CaseStudyView
    {
        $imageView = $caseStudy->getImage() ? $this->uploadMapper->toView($caseStudy->getImage()) : null;
        $sectors = $caseStudy->getSectors()->map(function(Sector $sector) {
            return IdName::create($sector->getId(), $sector->getName());
        });

        return new CaseStudyView(
            $caseStudy->getId(),
            $caseStudy->getTitle(),
            $caseStudy->getSlug(),
            $caseStudy->getClient(),
            $caseStudy->getIssue(),
            $caseStudy->getSolution(),
            $caseStudy->getApproach(),
            $caseStudy->getRecommendation(),
            $caseStudy->getEngagementRoi(),
            $caseStudy->getMetaTitle(),
            $caseStudy->getMetaDescription(),
            $imageView,
            $sectors,
            $caseStudy->getStatus() ?: PostStatus::DRAFT,
            $caseStudy->getPublishedAt(),
            $caseStudy->getUpdatedAt()
        );
    }
}
