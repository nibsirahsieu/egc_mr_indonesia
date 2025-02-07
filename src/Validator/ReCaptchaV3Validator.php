<?php 

namespace App\Validator;

use ReCaptcha\ReCaptcha;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class ReCaptchaV3Validator extends ConstraintValidator
{
    public function __construct(private RequestStack $requestStack, private ReCaptcha $reCaptcha)
    {    
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ReCaptchaV3) {
            throw new UnexpectedTypeException($constraint, ReCaptchaV3::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) to take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'string');
        }

        if (!$this->isTokenValid($value, $constraint->action, $constraint->threshold)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }

    private function isTokenValid(string $token, string $action, float $threshold): bool
    {
        try {
            $remotIp = $this->requestStack->getCurrentRequest()->getClientIp();
            $response = $this->reCaptcha
                ->setExpectedAction($action)
                ->setScoreThreshold($threshold)
                ->verify($token, $remotIp);

            return $response->isSuccess();
        } catch (\Throwable $e) {}

        return false;
    }
}
