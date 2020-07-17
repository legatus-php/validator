<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http\Validator\Builder\Field;

use Legatus\Http\Validator\Builder\BuildsFields;
use Legatus\Http\Validator\Builder\Field\Rule\Rule;
use Legatus\Http\Validator\Builder\ValidatorBuilder;
use Legatus\Http\Validator\Validator;

/**
 * Class BaseField.
 */
abstract class BaseField implements Field, BuildsFields
{
    /**
     * @var ValidatorBuilder
     */
    protected ValidatorBuilder $builder;
    /**
     * @var Rule[]
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

    public function body(string $path): Body
    {
        return $this->builder->body($path);
    }

    public function query(string $name): Query
    {
        return $this->builder->query($name);
    }

    public function file(string $name): File
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
