<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http;

use DateTimeInterface;
use InvalidArgumentException;
use RuntimeException;

/**
 * Class BodyValidationField.
 */
class BodyValidationField extends BaseValidationField
{
    private string $path;

    /**
     * BodyValidationField constructor.
     *
     * @param ValidatorBuilder $builder
     * @param string           $path
     */
    public function __construct(ValidatorBuilder $builder, string $path)
    {
        parent::__construct($builder);
        $this->path = $path;
    }

    public function process(ValidationContext $builder): void
    {
        $value = $this->getDataFrom($builder);
        $this->runRules($builder, $value);
    }

    public function required(): BodyValidationField
    {
        $this->rules[] = new RequiredValidationRule();

        return $this;
    }

    public function uuid(): BodyValidationField
    {
        $this->rules[] = new SkipNullValidationRule(new UuidValidationRule());

        return $this;
    }

    public function boolean(): BodyValidationField
    {
        $this->rules[] = new SkipNullValidationRule(new BooleanValidationRule());

        return $this;
    }

    public function string(): BodyValidationField
    {
        $this->rules[] = new SkipNullValidationRule(new StrValidationRule());

        return $this;
    }

    /**
     * @param int $min
     *
     * @return $this
     */
    public function min(int $min): BodyValidationField
    {
        $this->rules[] = new SkipNullValidationRule(new MinValidationRule($min));

        return $this;
    }

    /**
     * @param int $max
     *
     * @return $this
     */
    public function max(int $max): BodyValidationField
    {
        $this->rules[] = new SkipNullValidationRule(new MaxValidationRule($max));

        return $this;
    }

    /**
     * @param int $force
     *
     * @return $this
     */
    public function password(int $force = PasswordValidationRule::STRONG): BodyValidationField
    {
        $this->rules[] = new SkipNullValidationRule(new PasswordValidationRule($force));

        return $this;
    }

    /**
     * @param string $format
     *
     * @return BodyValidationField
     */
    public function date(string $format = DateValidationRule::ISO_JS): BodyValidationField
    {
        $this->rules[] = new SkipNullValidationRule(new DateValidationRule($format));

        return $this;
    }

    /**
     * @param callable|ValidationRule $rule
     *
     * @return BodyValidationField
     */
    public function custom($rule): BodyValidationField
    {
        if (is_callable($rule)) {
            $rule = new CustomValidationRule($rule);
        }
        if (!$rule instanceof ValidationRule) {
            throw new RuntimeException(sprintf('Custom rule must be an instance of %s or a callable', ValidationRule::class));
        }
        $this->rules[] = new SkipNullValidationRule($rule);

        return $this;
    }

    /**
     * @param string|DateTimeInterface $date
     *
     * @return BodyValidationField
     */
    public function before($date): BodyValidationField
    {
        $this->rules[] = new SkipNullValidationRule(BeforeValidationRule::create($date));

        return $this;
    }

    /**
     * @param string|DateTimeInterface $date
     *
     * @return BodyValidationField
     */
    public function after($date): BodyValidationField
    {
        $this->rules[] = new SkipNullValidationRule(AfterValidationRule::create($date));

        return $this;
    }

    /**
     * @return $this
     */
    public function email(): BodyValidationField
    {
        $this->rules[] = new SkipNullValidationRule(new EmailValidationRule());

        return $this;
    }

    /**
     * @param string $pattern
     *
     * @return $this
     */
    public function regex(string $pattern): BodyValidationField
    {
        $this->rules[] = new SkipNullValidationRule(new RegexValidationRule($pattern));

        return $this;
    }

    /**
     * @param array $values
     *
     * @return $this
     */
    public function enum(array $values): BodyValidationField
    {
        $this->rules[] = new SkipNullValidationRule(new EnumValidationRule(...$values));

        return $this;
    }

    /**
     * @param ValidationContext $builder
     * @param $data
     * @param string $path
     */
    public function runRules(ValidationContext $builder, $data, string $path = null): void
    {
        $path = $path ?? $this->path;
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $keyPath = $this->path($path, $key);
                $this->runRules($builder, $value, $keyPath);
            }

            return;
        }
        foreach ($this->rules as $rule) {
            try {
                $data = $rule->verify($data) ?? $data;
            } catch (InvalidValue $e) {
                $builder->addError($path, $e->getType(), $e->getErrorMessage());
            }
        }
        $builder->addData($path, $data);
    }

    /**
     * @param ValidationContext $builder
     *
     * @return array|bool|float|int|string|null
     */
    protected function getDataFrom(ValidationContext $builder)
    {
        $data = $builder->getRequest()->getParsedBody();
        if (!is_array($data)) {
            throw new InvalidArgumentException('Request parsed body must be an array');
        }
        $segments = explode('.', $this->path);

        return $this->extract($data, $segments);
    }

    /**
     * @param array $data
     * @param array $segments
     *
     * @return array|bool|float|int|string|null
     */
    private function extract(?array $data, array $segments)
    {
        while (count($segments) > 0) {
            if ($data === null) {
                return null;
            }
            $segment = array_shift($segments);

            if ($segment === '*') {
                if (!is_array($data)) {
                    throw new InvalidArgumentException('Used * operator on path that does not point to an array');
                }
                $result = [];
                foreach ($data as $item) {
                    if (is_scalar($item)) {
                        $result[] = $item;
                        continue;
                    }
                    $result[] = $this->extract($item, $segments);
                }

                return $result;
            }

            if (is_numeric($segment)) {
                $segment = (int) $segment;
            }
            $data = $data[$segment] ?? null;
        }

        return $data;
    }

    /**
     * @param string $path
     * @param int    $key
     *
     * @return string
     */
    private function path(string $path, int $key): string
    {
        $pattern = '/'.preg_quote('*', '/').'/';

        return preg_replace($pattern, (string) $key, $path, 1);
    }
}
