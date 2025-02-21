<?php 

namespace App\Twig;

use App\Entity\HeaderFooterScript;
use App\Repository\HeaderFooterScriptRepository;
use App\Repository\OurServiceRepository;
use App\Repository\SectorRepository;
use Twig\Extension\RuntimeExtensionInterface;

final class AppRuntimeExtension implements RuntimeExtensionInterface
{
    public function __construct(
        private readonly SectorRepository $sectorRepository,
        private readonly HeaderFooterScriptRepository $repository,
        private readonly OurServiceRepository $ourServiceRepository,
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
}
