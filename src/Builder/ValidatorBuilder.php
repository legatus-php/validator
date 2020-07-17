<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http\Validator\Builder;

use Legatus\Http\Validator\Validator;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class ValidatorBuilder.
 */
class ValidatorBuilder implements BuildsFields
{
    /**
     * @var Request
     */
    private Request $request;
    /**
     * @var array
     */
    private array $fields;

    /**
     * ValidatorBuilder constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->fields = [];
    }

    /**
     * @return Validator
     */
    public function getValidator(): Validator
    {
        return new Validator($this->request, ...$this->fields);
    }

    public function body(string $path): Field\Body
    {
        $field = new Field\Body($this, $path);
        $this->fields[] = $field;

        return $field;
    }

    public function query(string $name): Field\Query
    {
        $field = new Field\Query($this, $name);
        $this->fields[] = $field;

        return $field;
    }

    public function file(string $name): Field\File
    {
        $field = new Field\File($this, $name);
        $this->fields[] = $field;

        return $field;
    }
}
