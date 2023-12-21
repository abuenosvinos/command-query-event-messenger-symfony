<?php

declare(strict_types=1);

namespace App\Event\Infrastructure\Messenger\Event;

use App\Event\Domain\Bus\Event\Event;
use App\Event\Domain\Bus\Event\EventConfiguration;
use App\Event\Domain\Bus\Event\EventOptions;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Messenger\Stamp\StampInterface;
use Symfony\Component\Messenger\Stamp\TransportNamesStamp;

class SymfonyEventConfiguration implements EventConfiguration
{
    /**
     * @return array<StampInterface>
     */
    public function getConfiguration(Event $event, ?EventOptions $eventOptions): array
    {
        $stamps = [];

        if (!$eventOptions) {
            return $stamps;
        }

        /*
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'routing'     => null,
            'delay'    => null,
        ]);

        $resolver->resolve($eventOptions->all());
        */


        if (is_string($eventOptions->get('routing'))) {
            $stamps[] = new AmqpStamp($eventOptions->get('routing'));
        }

        if (is_array($eventOptions->get('transport'))) {
            $stamps[] = new TransportNamesStamp($eventOptions->get('transport'));
        }

        if (is_int($eventOptions->get('delay'))) {
            $stamps[] = new DelayStamp($eventOptions->get('delay'));
        }

        return $stamps;
    }
}
