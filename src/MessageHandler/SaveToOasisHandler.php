<?php 

namespace App\MessageHandler;

use App\Common\OasisApiClient;
use App\Entity\Inquiry;
use App\Message\SaveToOasis;
use App\Repository\InquiryRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
final readonly class SaveToOasisHandler
{
    public function __construct(private InquiryRepository $inquiryRepository, private OasisApiClient $oasisApi, private LoggerInterface $logger)
    {
    }

    public function __invoke(SaveToOasis $message): void
    {
        /** @var Inquiry */
        $inquiry = $this->inquiryRepository->find($message->inquiryId);
        if (!$inquiry) return;

        $data = [
            'firstName' => $inquiry->getFirstName(),
            'lastName' => $inquiry->getLastName(),
            'companyName' => $inquiry->getCompanyName(),
            'jobTitle' => $inquiry->getJobTitle(),
            'country' => $inquiry->getCountry(),
            'phoneNumber' => $inquiry->getPhoneNumber(),
            'email' => $inquiry->getEmail()
        ];

        $response = $this->oasisApi->post('/clients', $data);
        if ($response->getStatusCode() !== 200) {
            $this->logger->error('API error: ' . $response->getContent(false));
        }
    }
    
}
