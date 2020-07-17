<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http\Validator\Tests;

use Legatus\Http\Validator\Builder\Field\Rule\InvalidValue;
use Legatus\Http\Validator\Builder\Field\Rule\Rule;

/**
 * Class PromoCode.
 */
class PromoCode implements Rule
{
    public const VALID = '123456789';

    /**
     * @param $value
     *
     * @return string
     *
     * @throws InvalidValue
     */
    public function verify($value): string
    {
        if ($value === self::VALID) {
            return $value;
        }
        throw new InvalidValue('promo-code', 'The promo code is invalid');
    }
}
