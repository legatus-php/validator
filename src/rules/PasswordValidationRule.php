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
 * Class Password.
 */
class PasswordValidationRule implements ValidationRule
{
    private const TYPE = 'password';

    public const WITH_UPPERCASE = 1;
    public const WITH_SPECIAL_CHAR = 2;
    public const WITH_NUMBER = 4;
    public const VERY_STRONG = self::WITH_NUMBER + self::WITH_UPPERCASE + self::WITH_SPECIAL_CHAR;
    public const STRONG = self::WITH_NUMBER + self::WITH_UPPERCASE;
    public const MEDIUM = self::WITH_NUMBER;
    public const WEAK = 0;

    private static array $regexMap = [
        self::WITH_NUMBER => '(?=.*[A-Z])',
        self::WITH_UPPERCASE => '(?=.*[0-9])',
        self::WITH_SPECIAL_CHAR => '(?=.*[!@#$%^&*])',
    ];

    private int $force;

    /**
     * Password constructor.
     *
     * @param int $force
     */
    public function __construct(int $force = self::VERY_STRONG)
    {
        $this->force = $force;
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
        $pattern = $this->buildPattern();
        if (!preg_match($pattern, $value)) {
            throw new InvalidValue(self::TYPE, $this->buildErrorMessage());
        }
    }

    /**
     * @return string
     */
    private function buildPattern(): string
    {
        $pattern = '/^';
        $parts = [];
        if ($this->force & self::WITH_NUMBER) {
            $parts[] = self::$regexMap[self::WITH_NUMBER];
        }
        if ($this->force & self::WITH_UPPERCASE) {
            $parts[] = self::$regexMap[self::WITH_UPPERCASE];
        }
        if ($this->force & self::WITH_SPECIAL_CHAR) {
            $parts[] = self::$regexMap[self::WITH_SPECIAL_CHAR];
        }
        $pattern .= implode('', $parts).'/';

        return $pattern;
    }

    private function buildErrorMessage(): string
    {
        $message = 'Value must contain at least';

        $errors = [];
        if ($this->force & self::WITH_NUMBER) {
            $errors[] = 'one numeric character';
        }
        if ($this->force & self::WITH_UPPERCASE) {
            $errors[] = 'one uppercase character';
        }
        if ($this->force & self::WITH_SPECIAL_CHAR) {
            $errors[] = 'one special character';
        }
        $message .= implode(' and ', $errors).'.';

        return $message;
    }
}
