<?php 

namespace App\Twig;

use App\Common\SchemaGenerator;
use App\Entity\HeaderFooterScript;
use App\Repository\HeaderFooterScriptRepository;
use App\Repository\OurServiceRepository;
use App\Repository\PostRepository;
use App\Repository\SectorRepository;
use Twig\Extension\RuntimeExtensionInterface;

final class AppRuntimeExtension implements RuntimeExtensionInterface
{
    public function __construct(
        private readonly PostRepository $postRepository,
        private readonly SectorRepository $sectorRepository,
        private readonly HeaderFooterScriptRepository $repository,
        private readonly OurServiceRepository $ourServiceRepository,
        private readonly SchemaGenerator $schemaGenerator
    )
    {
    }

    public function getHeaderFooterScript(): ?HeaderFooterScript
    {
        return $this->repository->findOne();
    }

    public function getSectors(): iterable
    {
        return $this->sectorRepository->orderedById();
    }

    public function getServices(): iterable
    {
        return $this->ourServiceRepository->orderedById();
    }

    public function getPostTypes(): iterable
    {
        return $this->postRepository->postTypes();
    }

    public function rootSchema(string $title, string $logo): string
    {
        return $this->schemaGenerator->generateBaseSchema($title, $logo);
    }

    public function homeSchema(string $title, string $description, string $heroImage): string
    {
        return $this->schemaGenerator->generateHomeSchema($title, $description, $heroImage);
    }

    public function contactSchema(string $title): string
    {
        return $this->schemaGenerator->generateContactSchema($title);
    }
}
