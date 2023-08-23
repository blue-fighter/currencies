<?php

namespace CBRBundle\Services;

use CBRBundle\DTO\CBRRateDTO;
use CBRBundle\Exceptions\CBREmptyResponseException;
use CBRBundle\Exceptions\CBRResponseException;
use CBRBundle\Exceptions\ObtainDayRateException;
use DateTime;

interface GetDayRateServiceInterface
{
    /**
     * @param DateTime $date
     * @return CBRRateDTO[]
     * @throws ObtainDayRateException Request error
     * @throws CBRResponseException   cbr.ru response 3/4/5xx status codes
     * @throws CBREmptyResponseException   cbr.ru response doesn't contain any currencies
     */
    public function execute(DateTime $date): array;
}