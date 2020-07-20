<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * Class DateTest.
 */
class DateTest extends TestCase
{
    public function testItValidatesRightFormat(): void
    {
        $string = '2011-10-05T14:48:00.000Z';
        $date = new DateValidationRule(DateValidationRule::ISO_JS);
        $result = $date->verify($string);
        self::assertInstanceOf(DateTimeImmutable::class, $result);
    }

    public function testItInvalidatesWrongFormat(): void
    {
        $string = '2011-10-05';
        $date = new DateValidationRule(DateValidationRule::ISO_JS);
        $this->expectException(InvalidValue::class);
        $date->verify($string);
    }
}
