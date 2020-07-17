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
 * Class Min.
 */
class Min implements Rule
{
    private const TYPE_STR = 'min:str';
    private const TYPE_NUMBER = 'min:number';
    private const MESSAGE_STR = 'Value must have at least %s characters';
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
        if (is_string($value) && $this->min >= strlen($value)) {
            throw new InvalidValue(self::TYPE_STR, sprintf(self::MESSAGE_STR, $this->min));
        }
        if ((is_int($value) || is_float($value)) && $value <= $this->min) {
            throw new InvalidValue(self::TYPE_NUMBER, sprintf(self::MESSAGE_NUMBER, $this->min));
        }

        return $value;
    }
}
