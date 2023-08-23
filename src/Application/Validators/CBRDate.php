<?php

namespace Application\Validators;

use Symfony\Component\Validator\Constraints\Date;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class CBRDate extends Date
{
    public string $lateDateMessage = "The value must be less than or equal to today";
    public function __construct(
        array $options = null,
        string $message = null,
        string $lateDateMessage = null,
        array $groups = null,
        mixed $payload = null
    )
    {
        parent::__construct($options, $message, $groups, $payload);
        $this->lateDateMessage = $lateDateMessage ?? $this->lateDateMessage;
    }

}