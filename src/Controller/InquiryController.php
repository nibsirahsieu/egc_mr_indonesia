<?php 

namespace App\Controller;

use App\CommandService\InquiryCommandService;
use App\Request\InquiryRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class InquiryController extends AbstractController
{
    public function __construct(private InquiryCommandService $inquiryCommandService)
    {
    }

    #[Route('/inquiries', name: 'app_inquiry_submit', methods: ['POST'])]
    public function submit(#[MapRequestPayload()] InquiryRequest $inquiryRequest): Response
    {
        $this->inquiryCommandService->create($inquiryRequest);

        return $this->json([
            'success' => true
        ]);
    }
}
