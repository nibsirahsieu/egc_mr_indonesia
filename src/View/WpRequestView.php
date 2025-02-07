<?php 

namespace App\View;

readonly class WpRequestView
{
    public function __construct(
        public int $id, public string $firstName, public string $lastName, public string $companyName, 
        public string $jobTitle, public string $email, public string $country, public string $phoneNumber, 
        public string $message, public bool $downloaded, public string $whitepaper
    )
    {        
    }
}
