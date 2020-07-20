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
 * Class File.
 */
class FileValidationField extends BaseValidationField
{
    private string $name;

    /**
     * File constructor.
     *
     * @param ValidatorBuilder $builder
     * @param string           $name
     */
    public function __construct(ValidatorBuilder $builder, string $name)
    {
        parent::__construct($builder);
        $this->name = $name;
    }

    public function process(ValidationContext $builder): void
    {
        // TODO: Implement process() method.
    }
}
