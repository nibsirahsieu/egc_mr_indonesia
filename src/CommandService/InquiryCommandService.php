<?php 

namespace App\CommandService;

use App\Entity\Inquiry;
use App\Message\InquiryEmail;
use App\Request\InquiryRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class InquiryCommandService
{
    public function __construct(private EntityManagerInterface $entityManager, private MessageBusInterface $messageBus)
    {
    }

    public function create(InquiryRequest $request): void
    {
        $inquiry = new Inquiry();
        $inquiry->setFirstName($request->firstName);
        $inquiry->setLastName($request->lastName);
        $inquiry->setJobTitle($request->jobTitle);
        $inquiry->setCompanyName($request->companyName);
        $inquiry->setCountry($request->country);
        $inquiry->setPhoneNumber($request->phoneNumber);
        $inquiry->setEmail($request->emailAddress);
        $inquiry->setMessage($request->message);
        $inquiry->setFromPage($request->fromPage);
        
        $this->entityManager->persist($inquiry);
        $this->entityManager->flush();

        $this->messageBus->dispatch(new InquiryEmail($inquiry->getId()));
    }
}
