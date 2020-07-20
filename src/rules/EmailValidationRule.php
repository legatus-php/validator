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
 * Class Email.
 */
class EmailValidationRule implements ValidationRule
{
    private const TYPE = 'email';
    private const MESSAGE = 'Value must be a valid email';

    /**
     * @param $value
     *
     * @return string
     *
     * @throws InvalidValue
     */
    public function verify($value): string
    {
        $email = filter_var($value, FILTER_VALIDATE_EMAIL);
        if ($email === false) {
            throw new InvalidValue(self::TYPE, self::MESSAGE);
        }

        return $email;
    }
}
