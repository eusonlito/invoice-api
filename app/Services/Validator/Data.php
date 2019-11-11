<?php declare(strict_types=1);

namespace App\Services\Validator;

class Data
{
    /**
     * @var array
     */
    protected array $data = [];

    /**
     * @var array
     */
    protected array $rules = [];

    /**
     * @var bool
     */
    protected bool $all = true;

    /**
     * @param array $data
     * @param array $rules
     * @param bool $all = true
     *
     * @return self
     */
    public function __construct(array $data, array $rules, bool $all = true)
    {
        $this->data = $this->data($data);
        $this->rules = $this->rules($rules);
        $this->all = $all;
    }

    /**
     * @return array
     */
    public function get(): array
    {
        return $this->castRules($this->data, $this->rules);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function data(array $data): array
    {
        return array_filter($data, static function ($key): bool {
            return strpos((string)$key, '_') !== 0;
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param array $rules
     *
     * @return array
     */
    protected function rules(array $rules): array
    {
        foreach ($rules as $key => $rule) {
            if (is_array($rule)) {
                $rules[$key] = implode('|', array_filter($rule, 'is_string'));
            }
        }

        $rulesNew = [];

        foreach ($rules as $key => $value) {
            array_set($rulesNew, $key, $value);
        }

        return $rulesNew;
    }

    /**
     * @param array $data
     * @param array $rules
     *
     * @return array
     */
    protected function castRules(array $data, array $rules): array
    {
        foreach ($rules as $key => $rule) {
            $data = $this->castRule($data, $key, $rule);
        }

        return $data;
    }

    /**
     * @param array $data
     * @param string $key
     * @param string|array $rule
     *
     * @return mixed
     */
    protected function castRule(array $data, string $key, $rule)
    {
        $value = $data[$key] ?? null;

        if (is_array($rule)) {
            $value = $this->castRules(is_array($value) ? $value: [], $rule);
        } else {
            $value = $this->cast($value, $rule);
        }

        $data[$key] = $value;

        return $data;
    }

    /**
     * @param mixed $value
     * @param string $rule
     *
     * @return mixed
     */
    protected function cast($value, string $rule)
    {
        $rule = explode('|', $rule);

        if (empty($value) && in_array('nullable', $rule, true)) {
            return null;
        }

        if (in_array('integer', $rule, true)) {
            return (int)$value;
        }

        if (in_array('numeric', $rule, true)) {
            return (float)$value;
        }

        if (in_array('string', $rule, true)) {
            return (string)$value;
        }

        if (in_array('boolean', $rule, true)) {
            return (bool)$value;
        }

        if (in_array('array', $rule, true)) {
            return (array)$value;
        }

        return $value;
    }
}
