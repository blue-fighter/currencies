<?php

namespace Application\Services;
use Application\Exceptions\DataProviderError;
use DateTime;

interface DownloadRatesServiceInterface
{
    /**
     * @param DateTime $date
     * @return void
     * @throws DataProviderError
     */
    public function execute(DateTime $date): void;
}