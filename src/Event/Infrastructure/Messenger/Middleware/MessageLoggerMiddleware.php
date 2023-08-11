<?php

declare(strict_types=1);

namespace App\Event\Infrastructure\Messenger\Middleware;

use App\Event\Domain\Bus\Request;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

final class MessageLoggerMiddleware implements MiddlewareInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $messengerLogger)
    {
        $this->logger = $messengerLogger;
    }

    /**
     * @param Envelope $envelope
     * @param StackInterface $stack
     *
     * @return Envelope
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $request = $envelope->getMessage();

        if ($request instanceof Request) {
            $this->logger->debug(
                'New message dispatched',
                [
                    'type' => $request->requestType(),
                    'name' => $this->nameOf($request),
                ]
            );
        }

        return $stack->next()->handle($envelope, $stack);
    }

    private function nameOf(Request $request): string
    {
        return get_class($request);
    }
}
