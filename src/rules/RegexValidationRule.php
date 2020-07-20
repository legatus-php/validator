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
 * Class Regex.
 */
class RegexValidationRule implements ValidationRule
{
    public const TYPE = 'regex';
    public const MESSAGE = 'Value must match the pattern %s';

    private string $pattern;

    /**
     * Regex constructor.
     *
     * @param string $pattern
     */
    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @param $value
     *
     * @return string
     *
     * @throws InvalidValue
     */
    public function verify($value): string
    {
        if (!preg_match($this->pattern, $value, $matches, )) {
            return $value;
        }
        throw new InvalidValue(self::TYPE, sprintf(self::MESSAGE, $this->pattern));
    }
}
