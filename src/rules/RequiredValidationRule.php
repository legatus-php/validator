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
 * Class Required.
 */
class RequiredValidationRule implements ValidationRule
{
    private const TYPE = 'required';
    private const DEFAULT_MESSAGE = 'Value is required';

    /**
     * @param $value
     *
     * @return mixed|void
     *
     * @throws InvalidValue
     */
    public function verify($value)
    {
        if ($value === null || $value === '') {
            throw new InvalidValue(self::TYPE, self::DEFAULT_MESSAGE);
        }

        return $value;
    }
}
