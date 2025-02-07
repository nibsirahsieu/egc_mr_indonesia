<?php 

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final class ReCaptchaV3 extends Constraint
{
    public string $action = 'form';
    public float $threshold = 0.6;
    public string $message = 'Invalid request.';

    public function __construct(?string $action = null, ?float $threshold = null, ?string $message = null, ?array $groups = null, $payload = null)
    {
        parent::__construct([], $groups, $payload);

        $this->action = $action ?? $this->action;
        $this->threshold = $threshold ?? $this->threshold;
        $this->message = $message ?? $this->message;
    }

    public function getTargets(): string|array
    {
        return Constraint::PROPERTY_CONSTRAINT;
    }

    public function validatedBy(): string
    {
        return ReCaptchaV3Validator::class;
    }
}
