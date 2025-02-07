<?php 

namespace App\Controller;

use App\CommandService\DownloadWpCommandService;
use App\Common\JwtHelper;
use App\Common\StreamDownload;
use App\Entity\DownloadWhitepaperRequest;
use App\QueryService\PostQueryService;
use App\Request\DownloadWpRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/wp-requests', name: 'app_download_wp_request_')]
final class WpRequestController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private DownloadWpCommandService $commandService,
        private PostQueryService $postQueryService, 
        private JwtHelper $jwtHelper,
        private StreamDownload $streamDownload
    )
    {
    }

    #[Route('', name: 'submit', methods: ['POST'])]
    public function submit(#[MapRequestPayload()] DownloadWpRequest $downloadWpRequest): Response
    {
        $this->commandService->create($downloadWpRequest);

        return $this->json([
            'success' => true
        ]);
    }

    #[Route('/{token}', name: 'download', methods: ['GET'])]
    public function download(string $token): Response
    {
        $downloadRequest = null;

        try {
            $payload = (array) $this->jwtHelper->parse($token);
            $email = $payload['email'] ?? null;
            $wpId = $payload['wp_id'] ?? null;

            if ($email && $wpId) {
                $downloadRequest = $this->em->getRepository(DownloadWhitepaperRequest::class)->findByEmailAndWhitepaperId($email, (int) $wpId);
            }
        } catch (\Exception $e) {}
        
        if (null === $downloadRequest) {
            return $this->render('insight/download_wp_error.html.twig');
        }

        $post = $this->postQueryService->detail((int) $wpId);
        if (!$post) {
            $this->createNotFoundException("File not found");
        }

        $wpFile = $post->getFile();
        if (!$wpFile) {
            $this->createNotFoundException("File not found");
        }

        $downloadRequest->setDownloaded(true);
        $this->em->persist($downloadRequest);
        $this->em->flush();

        return $this->streamDownload->getResponse($wpFile->getRelativePath(), $wpFile->getMimeType(), $wpFile->getOriginalName());
    }
}
