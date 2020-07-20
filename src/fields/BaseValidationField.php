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
 * Class BaseValidationField.
 */
abstract class BaseValidationField implements ValidationField
{
    /**
     * @var ValidatorBuilder
     */
    protected ValidatorBuilder $builder;
    /**
     * @var ValidationRule[]
     */
    protected array $rules;

    /**
     * AbstractField constructor.
     *
     * @param ValidatorBuilder $builder
     */
    public function __construct(ValidatorBuilder $builder)
    {
        $this->builder = $builder;
        $this->rules = [];
    }

    public function body(string $path): BodyValidationField
    {
        return $this->builder->body($path);
    }

    public function query(string $name): QueryValidationField
    {
        return $this->builder->query($name);
    }

    public function file(string $name): FileValidationField
    {
        return $this->builder->file($name);
    }

    /**
     * @return Validator
     */
    public function getValidator(): Validator
    {
        return $this->builder->getValidator();
    }
}
