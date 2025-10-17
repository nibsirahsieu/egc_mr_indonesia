<?php 

namespace App\Request;

use App\Validator\InquiryEmail;
use App\Validator\ReCaptchaV3;
use Symfony\Component\Validator\Constraints as Assert;

final class InquiryRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 100)]
        public string $firstName,

        #[Assert\NotBlank]
        #[Assert\Length(max: 100)]
        public string $lastName,

        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $companyName,

        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $jobTitle,

        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        #[InquiryEmail()]
        public string $emailAddress,

        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $country,

        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $phoneNumber,

        #[Assert\NotBlank]
        public string $message,

        #[Assert\NotBlank]
        #[ReCaptchaV3(action: 'inquiry', threshold: 0.6)]
        public string $recaptcha,

        #[Assert\Length(max: 255)]
        public ?string $fromPage,

        public array $rfpFileIds
    )
    {
    }
}
