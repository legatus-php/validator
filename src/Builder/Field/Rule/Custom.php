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
 * Class Custom.
 */
final class Custom implements Rule
{
    /**
     * @var callable
     */
    private $callable;

    /**
     * Custom constructor.
     *
     * @param callable $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public function verify($value)
    {
        return ($this->callable)($value);
    }
}
