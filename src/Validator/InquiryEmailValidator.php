<?php 

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EmailValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class InquiryEmailValidator extends EmailValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof InquiryEmail) {
            throw new UnexpectedTypeException($constraint, InquiryEmail::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!\is_scalar($value) && !$value instanceof \Stringable) {
            throw new UnexpectedValueException($value, 'string');
        }

        $value = (string) $value;
        if ('' === $value) {
            return;
        }

        //check whether russian email or not
        $endings = array('\.ru');
        if (preg_match('/('.implode('|', $endings).')$/i', $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Email::INVALID_FORMAT_ERROR)
                ->addViolation();

            return;
        }

        //replace gmail, yahoo, hotmail with ''
        $sanitizedValue = str_replace(['gmail', 'yahoo', 'hotmail'], '', $value);

        parent::validate($sanitizedValue, $constraint);
    }
}
