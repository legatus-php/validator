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
 * Class SkipNull.
 *
 * Decorates a rule so it does not run when the value passed is null.
 */
final class SkipNull implements Rule
{
    /**
     * @var Rule
     */
    private Rule $rule;

    /**
     * SkipNull constructor.
     *
     * @param Rule $rule
     */
    public function __construct(Rule $rule)
    {
        $this->rule = $rule;
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
        if ($value !== null) {
            return $this->rule->verify($value);
        }

        return null;
    }
}
