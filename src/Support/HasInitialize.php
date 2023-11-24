<?php

namespace Ledc\Printer\Support;

/**
 * 初始化属性
 */
trait HasInitialize
{
    /**
     * 初始化属性
     * @param array $data
     * @return void
     */
    protected function initialize(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }
}
