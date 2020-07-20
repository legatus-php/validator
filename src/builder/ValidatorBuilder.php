<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http;

use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class ValidatorBuilder.
 */
class ValidatorBuilder
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

    public function body(string $path): BodyValidationField
    {
        $field = new BodyValidationField($this, $path);
        $this->fields[] = $field;

        return $field;
    }

    public function query(string $name): QueryValidationField
    {
        $field = new QueryValidationField($this, $name);
        $this->fields[] = $field;

        return $field;
    }

    public function file(string $name): FileValidationField
    {
        $field = new FileValidationField($this, $name);
        $this->fields[] = $field;

        return $field;
    }
}
