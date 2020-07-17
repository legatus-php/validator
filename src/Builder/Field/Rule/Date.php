<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http\Validator\Builder\Field\Rule;

use DateTimeImmutable;

/**
 * Class Date.
 */
class Date implements Rule
{
    public const ISO_JS = 'Y-m-d\TH:i:s.u\Z';
    private const TYPE = 'date';
    private const MESSAGE = 'Value must be a date string in format %s';

    private string $format;

    /**
     * Date constructor.
     *
     * @param string $format
     */
    public function __construct(string $format)
    {
        $this->format = $format;
    }

    /**
     * @param $value
     *
     * @return DateTimeImmutable|false|mixed
     *
     * @throws InvalidValue
     */
    public function verify($value)
    {
        $date = DateTimeImmutable::createFromFormat($this->format, $value);
        if ($date === false) {
            throw new InvalidValue(self::TYPE, sprintf(self::MESSAGE, $this->format));
        }

        return $date;
    }
}
