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
 * Class ValidationFailed.
 */
class ValidationFailed extends Exception
{
    private array $data;
    private array $errors;

    /**
     * ValidationFailed constructor.
     *
     * @param array $data
     * @param array $errors
     */
    public function __construct(array $data, array $errors)
    {
        parent::__construct('Validation failed');
        $this->data = $data;
        $this->errors = $errors;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
