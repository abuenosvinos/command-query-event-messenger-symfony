<?php

declare(strict_types=1);

namespace App\Tests\Shared\Domain\ValueObject;

use App\Shared\Domain\Exception\NotValidEmailAddressException;
use App\Shared\Domain\ValueObject\EmailAddress;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EmailAddressTest extends KernelTestCase
{
    public function testValidValues(): void
    {
        $email = EmailAddress::create('abuenosvinos@app.local');

        $this->assertEquals('abuenosvinos@app.local', $email->value());
    }

    public function testNotValidValue(): void
    {
        $this->expectException(NotValidEmailAddressException::class);

        EmailAddress::create('patata');
    }
}
