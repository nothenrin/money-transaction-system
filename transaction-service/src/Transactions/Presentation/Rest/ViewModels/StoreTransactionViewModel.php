<?php

namespace Src\Transactions\Presentation\Rest\ViewModels;

use Src\Shared\ValueObjects\Money;
use Src\Transactions\Presentation\Rest\Requests\StoreTransactionRequest;
use Src\Transactionables\Domain\Entities\Receiver;
use Src\Transactionables\Domain\Entities\Sender;
use Src\Transactionables\Domain\Enums\Provider;
use Src\Transactionables\Domain\Exceptions\InvalidTransactionableException;
use Src\Transactionables\Domain\ValueObjects\ReceiverId;
use Src\Transactionables\Domain\ValueObjects\SenderId;

class StoreTransactionViewModel
{
    public function __construct(
        public readonly Sender $sender,
        public readonly Receiver $receiver,
        public readonly Money $amount,
    ) {
    }

    /**
     * @throws InvalidTransactionableException
     */
    public static function fromRequest(StoreTransactionRequest $request): self
    {
        /** @var array $payload */
        $payload = $request->validated();

        $sender = new Sender(
            new SenderId($payload['sender_id']),
            Provider::from($payload['sender_provider_name'])
        );

        $receiver = new Receiver(
            new ReceiverId($payload['receiver_id']),
            Provider::from($payload['receiver_provider_name'])
        );

        return new self (
            $sender,
            $receiver,
            new Money($payload['amount'])
        );
    }
}
