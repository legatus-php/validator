<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http\Validator\Builder\Field\Rule;

use DateTimeInterface;
use InvalidArgumentException;

/**
 * Class After.
 */
class After implements Rule
{
    public const TYPE = 'after';
    public const MESSAGE = 'Value must be a date after %s';

    private DateTimeInterface $date;

    /**
     * @param string|DateTimeInterface $date
     *
     * @return After
     */
    public static function create($date): After
    {
        if (is_string($date)) {
            $dateString = $date;
            try {
                $date = new \DateTimeImmutable($date);
            } catch (\Exception $e) {
                throw new InvalidArgumentException(sprintf('Could not build date from string %s', $dateString));
            }
        }
        if (!$date instanceof DateTimeInterface) {
            throw new InvalidArgumentException(sprintf('Argument must be a date string or an instance of %s', DateTimeInterface::class));
        }

        return new self($date);
    }

    /**
     * Before constructor.
     *
     * @param DateTimeInterface $date
     */
    public function __construct(DateTimeInterface $date)
    {
        $this->date = $date;
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
        if ($this->date < $value) {
            return $value;
        }
        throw new InvalidValue(self::TYPE, sprintf(self::MESSAGE, $this->date->format(Date::ISO_JS)));
    }
}
