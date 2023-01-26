<?php

namespace Src\Transaction\Application;

use Src\Customer\Domain\Entities\Customer;
use Src\Infrastructure\Clients\Http\Exceptions\ExternalServiceException;
use Src\Infrastructure\Clients\Http\Exceptions\InvalidURLException;
use Src\Infrastructure\Clients\Http\Exceptions\RequestException;
use Src\Infrastructure\Clients\Http\TransactionsService\Endpoint;
use Src\Infrastructure\Clients\Http\TransactionsService\Exceptions\ClientException;
use Src\Infrastructure\Clients\Http\TransactionsService\Payloads\GetTransactionsPayload;
use Src\Infrastructure\Clients\Http\TransactionsService\Responses\GetTransactionsResponse\GetTransactionsResponse;
use Src\Infrastructure\Clients\Http\TransactionsService\TransactionsServiceClient;

class GetTransactions
{
    public function __construct(private readonly TransactionsServiceClient $client)
    {
    }

    /**
     * @throws ExternalServiceException
     * @throws ClientException
     */
    public function for(Customer $customer): GetTransactionsResponse
    {
        $payload = new GetTransactionsPayload($customer->id);

        try {
            /** @var GetTransactionsResponse $response */
            $response = $this->client->send(Endpoint::GET_TRANSACTIONS, $payload);
        } catch (RequestException|InvalidURLException $e) {
            throw new ClientException();
        }

        return $response;
    }
}
