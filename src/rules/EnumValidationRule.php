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
 * Class Enum.
 */
class EnumValidationRule implements ValidationRule
{
    private const TYPE = 'enum';
    private const MESSAGE = 'Value must be one of the following strings: %s';
    /**
     * @var array|string[]
     */
    private array $values;

    /**
     * Enum constructor.
     *
     * @param array $values
     */
    public function __construct(string ...$values)
    {
        $this->values = $values;
    }

    /**
     * @param $value
     *
     * @return mixed|void
     *
     * @throws InvalidValue
     */
    public function verify($value)
    {
        if (in_array($value, $this->values, true) === false) {
            throw new InvalidValue(self::TYPE, sprintf(self::MESSAGE, implode(', ', $this->values)));
        }
    }
}
