<?php 

namespace App\Validator;

use Symfony\Component\Validator\Constraints\Email;

#[\Attribute]
final class InquiryEmail extends Email
{
    public string $message = 'Please use corporate email.';

    public function __construct(
        array $options = null,
        string $message = null,
        string $mode = null,
        callable $normalizer = null,
        array $groups = null,
        $payload = null
    )
    {
        $validationMode = $mode ?: self::VALIDATION_MODE_STRICT;

        parent::__construct($options, $message, $validationMode, $normalizer, $groups, $payload);
    }

    public function validatedBy(): string
    {
        return InquiryEmailValidator::class;
    }
}
