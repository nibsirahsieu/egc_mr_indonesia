<?php

namespace App\Common;

readonly class AppConfig
{
    public function __construct(
        public string $baseUrl, public string $contactEmail, public string $contactNo, public string $addressCountry
    )
    {
    }
}
