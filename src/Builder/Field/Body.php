<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http\Validator\Builder\Field;

use DateTimeInterface;
use InvalidArgumentException;
use Legatus\Http\Validator\Builder\Field\Rule\Date;
use Legatus\Http\Validator\Builder\Field\Rule\Password;
use Legatus\Http\Validator\Builder\ValidatorBuilder;
use Legatus\Http\Validator\ValidationContext;
use RuntimeException;

/**
 * Class Body.
 */
class Body extends BaseField
{
    private string $path;

    /**
     * Body constructor.
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

    public function required(): Body
    {
        $this->rules[] = new Rule\Required();

        return $this;
    }

    public function uuid(): Body
    {
        $this->rules[] = new Rule\SkipNull(new Rule\Uuid());

        return $this;
    }

    public function string(): Body
    {
        $this->rules[] = new Rule\SkipNull(new Rule\Str());

        return $this;
    }

    /**
     * @param int $min
     *
     * @return $this
     */
    public function min(int $min): Body
    {
        $this->rules[] = new Rule\SkipNull(new Rule\Min($min));

        return $this;
    }

    /**
     * @param int $max
     *
     * @return $this
     */
    public function max(int $max): Body
    {
        $this->rules[] = new Rule\SkipNull(new Rule\Max($max));

        return $this;
    }

    /**
     * @param int $force
     *
     * @return $this
     */
    public function password(int $force = Password::STRONG): Body
    {
        $this->rules[] = new Rule\SkipNull(new Rule\Password($force));

        return $this;
    }

    /**
     * @param string $format
     *
     * @return Body
     */
    public function date(string $format = Date::ISO_JS): Body
    {
        $this->rules[] = new Rule\SkipNull(new Rule\Date($format));

        return $this;
    }

    /**
     * @param callable|Rule\Rule $rule
     *
     * @return Body
     */
    public function custom($rule): Body
    {
        if (is_callable($rule)) {
            $rule = new Rule\Custom($rule);
        }
        if (!$rule instanceof Rule\Rule) {
            throw new RuntimeException(sprintf('Custom rule must be an instance of %s or a callable', Rule\Rule::class));
        }
        $this->rules[] = new Rule\SkipNull($rule);

        return $this;
    }

    /**
     * @param string|DateTimeInterface $date
     *
     * @return Body
     */
    public function before($date): Body
    {
        $this->rules[] = new Rule\SkipNull(Rule\Before::create($date));

        return $this;
    }

    /**
     * @param string|DateTimeInterface $date
     *
     * @return Body
     */
    public function after($date): Body
    {
        $this->rules[] = new Rule\SkipNull(Rule\After::create($date));

        return $this;
    }

    /**
     * @return $this
     */
    public function email(): Body
    {
        $this->rules[] = new Rule\SkipNull(new Rule\Email());

        return $this;
    }

    /**
     * @param string $pattern
     *
     * @return $this
     */
    public function regex(string $pattern): Body
    {
        $this->rules[] = new Rule\SkipNull(new Rule\Regex($pattern));

        return $this;
    }

    /**
     * @param array $values
     *
     * @return $this
     */
    public function enum(array $values): Body
    {
        $this->rules[] = new Rule\SkipNull(new Rule\Enum(...$values));

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
            } catch (Rule\InvalidValue $e) {
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
