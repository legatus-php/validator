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
 * Class Number.
 */
class NumberValidationRule implements ValidationRule
{
    private const TYPE = 'number';
    private const MESSAGE = 'Value must be a number';

    private bool $float;

    /**
     * Number constructor.
     *
     * @param bool $float
     */
    public function __construct(bool $float = true)
    {
        $this->float = $float;
    }

    /**
     * @param $value
     *
     * @return float|int
     *
     * @throws InvalidValue
     */
    public function verify($value)
    {
        if (!is_numeric($value)) {
            throw new InvalidValue(self::TYPE, self::MESSAGE);
        }
        if ($this->float) {
            return (float) $value;
        }

        return (int) $value;
    }
}
