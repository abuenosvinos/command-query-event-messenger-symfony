<?php

declare(strict_types=1);

namespace App\Event\Infrastructure\Messenger\Middleware;

use App\Shared\Domain\Exception\UnrecoverableDomainException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

final class DomainExceptionClassifierMiddleware implements MiddlewareInterface
{
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        try {
            return $stack->next()->handle($envelope, $stack);
        } catch (HandlerFailedException $e) {
            $allUnrecoverable = true;
            foreach ($e->getWrappedExceptions() as $nestedException) {
                if (!$nestedException instanceof UnrecoverableDomainException) {
                    $allUnrecoverable = false;
                    break;
                }
            }
            if ($allUnrecoverable) {
                throw new UnrecoverableMessageHandlingException($e->getMessage(), 0, $e);
            }

            throw $e;
        } catch (UnrecoverableDomainException $exception) {
            throw new UnrecoverableMessageHandlingException($exception->getMessage(), 0, $exception);
        }
    }
}