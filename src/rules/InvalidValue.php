<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http;

use Exception;

/**
 * Class InvalidValue.
 */
class InvalidValue extends Exception
{
    private string $type;
    private string $errorMessage;

    /**
     * InvalidValue constructor.
     *
     * @param string $type
     * @param string $errorMessage
     */
    public function __construct(string $type, string $errorMessage)
    {
        parent::__construct('Invalid value');
        $this->type = $type;
        $this->errorMessage = $errorMessage;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }
}
