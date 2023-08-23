<?php

namespace Application\Services;

use Application\DTO\CreateCurrencyRequestDTO;

interface CreateCurrencyServiceInterface
{
    public function execute(CreateCurrencyRequestDTO $request);
}