<?php

namespace Ledc\Printer\Support;

use InvalidArgumentException;

/**
 * 数据结构
 */
trait HasData
{
    /**
     * 当前数据
     * @var array
     */
    protected array $data;

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    final public function toArray(): array
    {
        return $this->data;
    }

    /**
     * 输出Json数据
     * @return string
     */
    final public function toJson(): string
    {
        $json = json_encode($this->data, JSON_UNESCAPED_UNICODE);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidArgumentException('json_encode error: ' . json_last_error_msg());
        }
        return $json;
    }

    /**
     * 当对不可访问属性调用 isset() 或 empty() 时，__isset() 会被调用
     * @param int|string $name
     * @return bool
     */
    public function __isset(int|string $name): bool
    {
        return isset($this->data[$name]);
    }

    /**
     * 当对不可访问属性调用 unset() 时，__unset() 会被调用
     * @param int|string $name
     */
    public function __unset(int|string $name)
    {
        unset($this->data[$name]);
    }

    /**
     * 当访问不可访问属性时调用
     * @param int|string $name
     * @return array|string|null
     */
    public function __get(int|string $name)
    {
        return $this->get($name);
    }

    /**
     * 在给不可访问（protected 或 private）或不存在的属性赋值时，__set() 会被调用。
     * @param int|string $key
     * @param mixed $value
     */
    public function __set(int|string $key, mixed $value)
    {
        $this->set($key, $value);
    }

    /**
     * 获取配置项参数
     * - 支持 . 分割符
     * @param int|string|null $key
     * @param mixed|null $default
     * @return mixed
     */
    final public function get(int|string $key = null, mixed $default = null): mixed
    {
        if (null === $key) {
            return $this->data;
        }
        $keys = explode('.', $key);
        $value = $this->data;
        foreach ($keys as $index) {
            if (!isset($value[$index])) {
                return $default;
            }
            $value = $value[$index];
        }
        return $value;
    }

    /**
     * 设置 $this->data
     * @param int|string|null $key
     * @param mixed $value
     * @return static
     */
    final public function set(int|string|null $key, mixed $value): static
    {
        if ($key === null) {
            $this->data[] = $value;
        } else {
            $this->data[$key] = $value;
        }
        return $this;
    }
}
