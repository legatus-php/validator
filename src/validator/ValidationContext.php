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
 * Class ResultBuilder.
 */
class ValidationContext
{
    /**
     * @var Request
     */
    private Request $request;
    private array $errors;
    private array $data;

    /**
     * ResultBuilder constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->data = [];
        $this->errors = [];
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return ValidatedData
     *
     * @throws ValidationFailed
     */
    public function getResult(): ValidatedData
    {
        if (count($this->errors) > 0) {
            throw new ValidationFailed($this->createErrorData());
        }

        return new ValidatedData($this->createValidatedData());
    }

    /**
     * @param string $path
     * @param string $type
     * @param string $message
     */
    public function addError(string $path, string $type, string $message): void
    {
        $this->errors[$path][$type] = $message;
    }

    /**
     * @param string $path
     * @param $data
     */
    public function addData(string $path, $data): void
    {
        $this->data[$path] = $data;
    }

    /**
     * @param array  $arr
     * @param string $path
     * @param $value
     */
    protected function pathToArrayAssign(array &$arr, string $path, $value): void
    {
        $keys = explode('.', $path);

        foreach ($keys as $key) {
            if (is_numeric($key)) {
                $key = (int) $key;
            }
            $arr = &$arr[$key];
        }

        $arr = $value;
    }

    private function createErrorData(): array
    {
        $data = $this->createValidatedData(true);
        foreach ($this->errors as $path => $errors) {
            foreach ($errors as $type => $message) {
                $this->pathToArrayAssign($data, $path.'.errors.'.$type, $message);
            }
        }
        return $data;
    }

    /**
     * @param bool $appendValue
     *
     * @return array
     */
    private function createValidatedData(bool $appendValue = false): array
    {
        $data = [];
        foreach ($this->data as $path => $value) {
            if ($appendValue) {
                $path .= '.value';
            }
            $this->pathToArrayAssign($data, $path, $value);
        }

        return $data;
    }
}
