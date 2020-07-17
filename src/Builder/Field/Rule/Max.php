<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http\Validator\Builder\Field\Rule;

/**
 * Class Max.
 */
class Max implements Rule
{
    private const TYPE_STR = 'max:str';
    private const TYPE_NUMBER = 'max:number';
    private const MESSAGE_STR = 'Value must not have more than %s characters';
    private const MESSAGE_NUMBER = 'Value must be greater than %s';

    private int $min;

    /**
     * Min constructor.
     *
     * @param int $min
     */
    public function __construct(int $min)
    {
        $this->min = $min;
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
        if (is_string($value) && $this->min < strlen($value)) {
            throw new InvalidValue(self::TYPE_STR, sprintf(self::MESSAGE_STR, $this->min));
        }
        if ((is_int($value) || is_float($value)) && $value > $this->min) {
            throw new InvalidValue(self::TYPE_NUMBER, sprintf(self::MESSAGE_NUMBER, $this->min));
        }

        return $value;
    }
}
