<?php 

namespace App\MessageHandler;

use App\Entity\DownloadWhitepaperRequest;
use App\Message\DownloadWpEmail;
use App\Repository\DownloadWhitepaperRequestRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
final class DownloadWpEmailHandler
{
    public function __construct(
        private DownloadWhitepaperRequestRepository $repository, 
        private MailerInterface $mailer,
        #[Autowire('%env(string:APP_SENDER)%')]
        private string $sender,
        #[Autowire('%env(string:APP_CONTACT_EMAIL)%')]
        private string $contactEmail,
        #[Autowire('%env(string:APP_CONTACT_PHONE_NO)%')]
        private string $contactPhone
    )
    {
    }

    public function __invoke(DownloadWpEmail $message): void
    {
        /** @var DownloadWhitepaperRequest $downloadRequest */
        $downloadRequest = $this->repository->find($message->id);
        if (!$downloadRequest) return;

        $email = (new TemplatedEmail())
            ->from($this->sender)
            ->to($downloadRequest->getEmail())
            ->subject('Your Whitepaper Download Request: ' . $downloadRequest->getWhitepaper()->getTitle())
            ->htmlTemplate('emails/download_wp.html.twig')
            ->context([
                'first_name' => $downloadRequest->getFirstName(),
                'token' => $downloadRequest->getToken(),
                'wp_title' => $downloadRequest->getWhitepaper()->getTitle(),
                'contact_email' => $this->contactEmail,
                'contact_phone' => $this->contactPhone
            ]);

        $this->mailer->send($email);
    }
}
