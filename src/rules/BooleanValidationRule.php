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
 * Class Boolean.
 */
class BooleanValidationRule implements ValidationRule
{
    protected const TYPE = 'boolean';
    protected const MESSAGE = 'Value must be a boolean string';

    /**
     * @param $value
     *
     * @return bool
     *
     * @throws InvalidValue
     */
    public function verify($value): bool
    {
        if (is_bool($value)) {
            return $value;
        }
        $result = filter_var($value, FILTER_VALIDATE_BOOLEAN);
        if ($result === false) {
            throw new InvalidValue(self::TYPE, self::MESSAGE);
        }

        return $result;
    }
}
