<?php

namespace Wechat\CacheDriver;

use Wechat\CacheDriver\BaseDriver;

/**
 * Thinkphp驱动.
 */
class ThinkPHPDriver extends BaseDriver
{
    /**
     * 根据缓存名获取缓存内容.
     *
     * @param string $name 缓存名
     */
    public function _get($name)
    {
        return S($name);
    }

    /**
     * 根据缓存名 设置缓存值和超时时间.
     *
     * @param string $name    缓存名
     * @param void   $value   缓存值
     * @param int    $expires 超时时间
     *
     * @return boolean;
     */
    public function _set($name, $value, $expires)
    {
        return S($name, $value, $expires);
    }
}
