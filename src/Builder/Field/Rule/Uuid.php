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
 * Class Uuid.
 */
class Uuid implements Rule
{
    private const TYPE = 'uuid';
    private const DEFAULT_MESSAGE = 'Value must be a uuid string';
    private const UUID_REGEX = '/^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$/';
    private const NIL_UUID = '00000000-0000-0000-0000-000000000000';

    /**
     * @param $value
     *
     * @return mixed|void
     *
     * @throws InvalidValue
     */
    public function verify($value)
    {
        if (!is_string($value)) {
            throw new InvalidValue(self::TYPE, self::DEFAULT_MESSAGE);
        }

        $value = str_replace(['urn:', 'uuid:', '{', '}'], '', $value);

        // The nil UUID is special form of UUID that is specified to have all
        // 128 bits set to zero.
        if ($value === self::NIL_UUID) {
            return $value;
        }

        if (!preg_match(self::UUID_REGEX, $value)) {
            throw new InvalidValue(self::TYPE, self::DEFAULT_MESSAGE);
        }

        return $value;
    }
}
