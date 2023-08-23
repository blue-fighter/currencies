<?php

namespace Application\Controllers;

use Application\DTO\GetExchangeRateRequest;
use Application\DTO\GetExchangeRateRequestDTO;
use Application\Exceptions\CurrencyNotFoundException;
use Application\Exceptions\DataProviderError;
use Application\Services\GetExchangeRateServiceInterface;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Validator\Validator\ValidatorInterface;


readonly class ExchangeRateController
{
    public function __construct(
        private GetExchangeRateServiceInterface $exchangeRateService,
        private ValidatorInterface              $validator
    )
    {
    }

    public function execute(
        #[MapQueryParameter] ?string $date,
        #[MapQueryParameter] ?string $targetCurrencyCode,
        #[MapQueryParameter] ?string $sourceCurrencyCode
    ): JsonResponse
    {
        $request = new GetExchangeRateRequest($date, $targetCurrencyCode, $sourceCurrencyCode);
        $validationErrors = $this->validateRequest($request);

        if (count($validationErrors)){
            return new JsonResponse(["result" => "error", "messages" => $validationErrors], 400);
        }

        $requestDTO = new GetExchangeRateRequestDTO(
            DateTime::createFromFormat('d-m-Y', $date),
            $targetCurrencyCode,
            $sourceCurrencyCode
        );
        try {
            $rate = $this->exchangeRateService->execute($requestDTO);
        } catch (CurrencyNotFoundException $e) {
            return new JsonResponse(["result" => "error", "messages" => [$e->getMessage()]], 404);
        } catch (DataProviderError $e){
            return new JsonResponse(["result" => "error", "messages" => [$e->getMessage()]], 400);
        }
        return new JsonResponse(["result" => "success", "payload" => $rate]);
    }

    /**
     * @param GetExchangeRateRequest $request
     * @return array
     */
    private function validateRequest(GetExchangeRateRequest $request): array
    {
        $messages = [];
        $errors = $this->validator->validate($request);
        if (count($errors) > 0) {
            foreach ($errors->getIterator() as $error) {
                $messages[] = sprintf(
                    "Attribute '%s' error: '%s'",
                    $error->getPropertyPath(),
                    $error->getMessage()
                );
            }
        }
        return $messages;
    }

}