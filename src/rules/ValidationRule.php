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
 * Interface Rule.
 */
interface ValidationRule
{
    /**
     * Executes the rule on the passed value.
     *
     * @param $value
     *
     * @throws InvalidValue if the value does not meet the rule
     *
     * @return mixed The value, modified for next rules if needed
     */
    public function verify($value);
}
