<?php

namespace App\Data\Application\LogBook;

use App\Data\Domain\Entity\LogBook;
use App\Data\Domain\Event\LogBook as LogBookEvent;
use App\Data\Domain\Repository\LogBookRepository;
use App\Data\Domain\Repository\UserRepository;
use App\Event\Domain\Bus\Event\EventHandler;
use App\Shared\Domain\ValueObject\EmailAddress;

class LogBookEventHandler implements EventHandler
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly LogBookRepository $logBookRepository
    ) {
    }

    public function __invoke(LogBookEvent $event): void
    {
        $logBook = new LogBook();
        $logBook->setAction($event->action());
        $logBook->setOccurredOn(new \DateTime($event->occurredOn()));
        $logBook->setObjectType($event->objectType());
        $logBook->setObjectId($event->objectId());
        $data = $event->data();
        if (count($data) > 0) {
            $logBook->setMetadata(json_encode($data));
        }

        if ($event->email()) {
            $user = $this->userRepository->findByEmail(EmailAddress::create($event->email()));
            if ($user) {
                $logBook->setUser($user);
            }
        }

        $this->logBookRepository->save(
            $logBook
        );
    }
}
