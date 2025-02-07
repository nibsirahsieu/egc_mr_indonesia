<?php 

namespace App\CommandService;

use App\Entity\RedirectUrl;
use App\Repository\RedirectUrlRepository;
use App\Request\RedirectUrlRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class RedirectUrlCommandService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly RedirectUrlRepository $redirectUrlRepository
    )
    {
    }

    public function create(RedirectUrlRequest $redirectUrlRequest): void
    {
        $redirectUrl = new RedirectUrl();
        $redirectUrl->setOldUrl($redirectUrlRequest->oldUrl);
        $redirectUrl->setNewUrl($redirectUrlRequest->newUrl);

        $this->em->persist($redirectUrl);
        $this->em->flush();
    }

    public function update(int $id, RedirectUrlRequest $redirectUrlRequest): void
    {
        $redirectUrl = $this->findById($id);
        $redirectUrl->setOldUrl($redirectUrlRequest->oldUrl);
        $redirectUrl->setNewUrl($redirectUrlRequest->newUrl);

        $this->em->persist($redirectUrl);
        $this->em->flush();
    }

    public function delete(int $id): void
    {
        $redirectUrl = $this->findById($id);
        $this->em->remove($redirectUrl);
        $this->em->flush();
    }

    private function findById(int $id): RedirectUrl
    {
        $redirectUrl = $this->redirectUrlRepository->find($id);
        if (!$redirectUrl) {
            throw new NotFoundHttpException("Redirect url not found");
        }

        return $redirectUrl;
    }
}
