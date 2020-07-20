<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Interface Validator.
 */
class Validator
{
    /**
     * @var ValidationField[]
     */
    private array $validatables;
    /**
     * @var Request
     */
    private Request $request;

    /**
     * @param Request $request
     *
     * @return ValidatorBuilder
     */
    public static function build(Request $request): ValidatorBuilder
    {
        return new ValidatorBuilder($request);
    }

    /**
     * Validator constructor.
     *
     * @param Request         $request
     * @param ValidationField ...$validatables
     */
    public function __construct(ServerRequestInterface $request, ValidationField ...$validatables)
    {
        $this->request = $request;
        $this->validatables = $validatables;
    }

    /**
     * @return ValidatedData
     *
     * @throws ValidationFailed
     */
    public function validate(): ValidatedData
    {
        $builder = new ValidationContext($this->request);
        foreach ($this->validatables as $validatable) {
            $validatable->process($builder);
        }

        return $builder->getResult();
    }
}
