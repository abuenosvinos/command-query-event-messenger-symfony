<?php

declare(strict_types=1);

namespace App\Communication\Application\PurchaseFromFinance;

use App\Communication\Domain\Event\MailSent;
use App\Event\Domain\Bus\Event\EventBus;
use App\Event\Domain\Bus\Event\EventHandler;
use App\Finance\Domain\Event\Product\ProductBought;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class PurchaseFromFinanceEventHandler implements EventHandler
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly EventBus $eventBus,
        private readonly string $emailAdmin
    ) {
    }

    public function __invoke(ProductBought $event): void
    {
        $email = (new Email())
            ->from($this->emailAdmin)
            ->to($this->emailAdmin)
            ->subject('Product has been bought')
            ->html(
                sprintf(
                    "A product has been bought from Finance:<br><br>Code: %s<br>Quantity: %s<br>Price: %s<br>",
                    $event->code(),
                    $event->quantity(),
                    $event->price()
                )
            );

        $this->mailer->send($email);

        $this->eventBus->notify(
            new MailSent(
                what: 'PurchaseFromFinance',
                data: [
                    'typeEmail' => 'PurchaseFromFinance',
                    'to' => $this->emailAdmin,
                ]
            )
        );
    }
}
