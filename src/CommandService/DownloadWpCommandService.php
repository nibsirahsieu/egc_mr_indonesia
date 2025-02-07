<?php 

namespace App\CommandService;

use App\Common\JwtHelper;
use App\Entity\DownloadWhitepaperRequest;
use App\Entity\Post;
use App\Message\DownloadWpEmail;
use App\Request\DownloadWpRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class DownloadWpCommandService
{
    public function __construct(private EntityManagerInterface $em, private JwtHelper $jwtGenerator, private MessageBusInterface $messageBus)
    {
    }

    public function create(DownloadWpRequest $request): void
    {
        $token = $this->jwtGenerator->generate(['email' => $request->emailAddress, 'wp_id' => $request->whitepaperId]);

        $downloadWpRequest = new DownloadWhitepaperRequest();
        $downloadWpRequest->setFirstName($request->firstName);
        $downloadWpRequest->setLastName($request->lastName);
        $downloadWpRequest->setJobTitle($request->jobTitle);
        $downloadWpRequest->setCompanyName($request->companyName);
        $downloadWpRequest->setCountry($request->country);
        $downloadWpRequest->setEmail($request->emailAddress);
        $downloadWpRequest->setWhitepaper($this->em->getReference(Post::class, $request->whitepaperId));
        $downloadWpRequest->setToken($token);
        $downloadWpRequest->setDownloaded(false);

        $this->em->persist($downloadWpRequest);
        $this->em->flush();

        $this->messageBus->dispatch(new DownloadWpEmail($downloadWpRequest->getId()));
    }
}
