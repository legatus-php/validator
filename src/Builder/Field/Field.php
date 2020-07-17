<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http\Validator\Builder\Field;

use Legatus\Http\Validator\ValidationContext;

/**
 * Interface Field.
 */
interface Field
{
    /**
     * Process the validation on this field.
     *
     * @param ValidationContext $builder
     */
    public function process(ValidationContext $builder): void;
}
