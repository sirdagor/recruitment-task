<?php

declare(strict_types=1);

namespace App\Invoice\UserInterface\Http;

use App\Invoice\Application\Query\GetAccountSummary;
use App\Invoice\Domain\Entity\CustomerId;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class CustomerController
{
    public function __construct(
        private readonly LoggerInterface     $logger,
        private readonly MessageBusInterface $messageBus
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $limit = 20;
            $offset = 0;
            $customerId = null;

            if (!empty($request->query->get('customerId'))) {
                $customerId = new CustomerId(Uuid::fromString($request->query->get('customerId')));
            }

            if (!empty($request->query->get('limit'))) {
                $limit = $request->query->get('limit');
            }

            if (!empty($request->query->get('offset'))) {
                $offset = $request->query->get('offset');
            }

            $envelope = $this->messageBus->dispatch(
                new GetAccountSummary(
                    (int) $limit,
                    (int) $offset,
                    $customerId
                )
            );
            $handledStamp = $envelope->last(HandledStamp::class);
            $summary = $handledStamp->getResult();
            return new JsonResponse($summary);
        } catch (HandlerFailedException $exception) {
            if ($exception->getCode() === 400) {
                return new JsonResponse($exception, Response::HTTP_BAD_REQUEST);
            }
            $message = $exception->getMessage();
            $id = Uuid::uuid4();
            $this->logger->error("There was error:$id during generating 
            accountSummary for customer:$customerId message: $message");
            return new JsonResponse("Something went wrong error:$id", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
