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

    /**
     * ValidationFailed constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct('Validation failed');
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
