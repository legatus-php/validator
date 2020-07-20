<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http;

use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{
    public function testItValidatesStrongPassword(): void
    {
        $this->expectNotToPerformAssertions();
        $pass = 'ThisIsAStrongPass@2020';
        $password = new PasswordValidationRule();
        $password->verify($pass);
    }

    public function testItFailsWithStrongPassword(): void
    {
        $pass = 'weakPass';
        $password = new PasswordValidationRule();
        $this->expectException(InvalidValue::class);
        $password->verify($pass);
    }
}
