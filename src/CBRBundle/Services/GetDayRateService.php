<?php

namespace CBRBundle\Services;

use CBRBundle\DTO\CBRRateDTO;
use CBRBundle\Exceptions\CBREmptyResponseException;
use CBRBundle\Exceptions\CBRResponseException;
use CBRBundle\Exceptions\ObtainDayRateException;
use DateTime;
use InvalidArgumentException;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetDayRateService implements GetDayRateServiceInterface
{
    const CBR_URL = 'https://www.cbr.ru/scripts/XML_daily.asp';

    public function __construct(private readonly HttpClientInterface $client)
    {
    }

    /**
     * @param DateTime $date
     * @return array|CBRRateDTO[]
     * @throws CBREmptyResponseException
     * @throws ObtainDayRateException
     * @throws CBRResponseException
     */
    public function execute(DateTime $date): array
    {
        $result = [];
        $date_req = $date->format("d/m/Y");
        try {
            $response = $this->client->request('GET', self::CBR_URL, ['query' => ['date_req' => $date_req]]);
            $content = $response->getContent();
        } catch (TransportExceptionInterface) {
            throw new ObtainDayRateException("Invalid request to cbr.ru");
        } catch (HttpExceptionInterface) {
            throw new CBRResponseException("cbr.ru error response");
        }

        $crawler = new Crawler($content);
        $currencies = $crawler->filterXpath('//ValCurs/*');

        if (!$currencies->count()) {
            throw new CBREmptyResponseException(sprintf("cbr.ru has no currency rates for %s", $date_req));
        }

        foreach ($currencies as $key => $currency) {
            $index = $key + 1;
            $numericCode = $this->getNodeValue($crawler, $index, "NumCode");
            $characterCode = $this->getNodeValue($crawler, $index, "CharCode");
            $nominal = $this->getNodeValue($crawler, $index, "Nominal");
            $name = $this->getNodeValue($crawler, $index, "Name");
            $value = $this->getNodeValue($crawler, $index, "Value");

            $result[] = new CBRRateDTO($numericCode, $characterCode, $nominal, $name, $value, $date);
        }
        return $result;
    }

    /**
     * Returns node value if it exists, else returns null
     *
     * @param Crawler $crawler
     * @param int $index
     * @param string $name
     * @return string|null
     */
    private function getNodeValue(Crawler $crawler, int $index, string $name): ?string
    {
        try {
            return $crawler->filterXpath("//ValCurs/Valute[{$index}]/{$name}")->text();
        } catch (InvalidArgumentException) {
            return null;
        }
    }
}