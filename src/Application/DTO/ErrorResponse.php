<?php

namespace Application\DTO;

/**
 * Error response schema
 */
readonly class ErrorResponse
{
    public function __construct(
        public string $result,
        public array $messages,
    )
    {
    }
}
