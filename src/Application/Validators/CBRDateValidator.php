<?php

namespace Application\Validators;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateValidator as BaseValidator;
use DateTime;

class CBRDateValidator extends BaseValidator
{
    public const PATTERN = '/^(?<day>\d{2})-(?<month>\d{2})-(?<year>\d{4})$/';

    public function validate(mixed $value, Constraint $constraint)
    {
        parent::validate($value, $constraint);

        if(new DateTime() < DateTime::createFromFormat("d-m-Y", $value)){
            $this->context->buildViolation($constraint->lateDateMessage)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Date::INVALID_DATE_ERROR)
                ->addViolation();
        }

    }
}