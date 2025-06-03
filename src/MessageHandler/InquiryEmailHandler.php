<?php 

namespace App\MessageHandler;

use App\Entity\Inquiry;
use App\Message\InquiryEmail;
use App\Repository\InquiryRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
final class InquiryEmailHandler
{
    public function __construct(
        private InquiryRepository $inquiryRepository, 
        private MailerInterface $mailer,
        #[Autowire('%env(string:APP_SENDER)%')]
        private string $sender,
        #[Autowire('%env(string:APP_INQUIRY_RECIPIENT)%')]
        private string $recipient,
        #[Autowire('%env(string:APP_NAME)%')]
        private string $appName
    )
    {
    }

    public function __invoke(InquiryEmail $inquiryEmail): void
    {
        $inquiry = $this->inquiryRepository->find($inquiryEmail->inquiryId);
        if (!$inquiry) return;

        $this->sendToAdmin($inquiry);
        $this->sendToSender($inquiry);
    }

    private function sendToAdmin(Inquiry $inquiry): void
    {
        $email = (new TemplatedEmail())
            ->from($this->sender)
            ->to($this->recipient)
            ->subject('New inquiry from ' . $this->appName)
            ->htmlTemplate('emails/inquiry.html.twig')
            ->context([
                'name' => sprintf('%s %s', $inquiry->getFirstName(), $inquiry->getLastName()),
                'company_name' => $inquiry->getCompanyName(),
                'job_title' => $inquiry->getJobTitle(),
                'country' => $inquiry->getCountry(),
                'phone_number' => $inquiry->getPhoneNumber(),
                'message' => nl2br($inquiry->getMessage()),
                'contact_email' => $inquiry->getEmail()
            ]);

        $this->mailer->send($email);
    }

    private function sendToSender(Inquiry $inquiry): void
    {
        $email = (new TemplatedEmail())
            ->from($this->sender)
            ->to($inquiry->getEmail())
            ->subject('New message from ' . $this->appName)
            ->htmlTemplate('emails/inquiry_sender.html.twig')
            ->context([
                'first_name' => $inquiry->getFirstName(),
                'name' => sprintf('%s %s', $inquiry->getFirstName(), $inquiry->getLastName()),
                'company_name' => $inquiry->getCompanyName(),
                'job_title' => $inquiry->getJobTitle(),
                'country' => $inquiry->getCountry(),
                'phone_number' => $inquiry->getPhoneNumber(),
                'message' => nl2br($inquiry->getMessage()),
                'contact_email' => $inquiry->getEmail()
            ]);

        $this->mailer->send($email);
    }
}
