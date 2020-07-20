<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http;

/**
 * Class Str.
 */
class StrValidationRule implements ValidationRule
{
    private const TYPE = 'string';
    private const DEFAULT_MESSAGE = 'Value must be a string';

    /**
     * @param $value
     *
     * @return mixed|string
     *
     * @throws InvalidValue
     */
    public function verify($value)
    {
        if (is_string($value)) {
            return $value;
        }
        throw new InvalidValue(self::TYPE, self::DEFAULT_MESSAGE);
    }
}
