<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace think\worker;

use think\Cookie as BaseCookie;
use Workerman\Protocols\Http as WorkerHttp;

/**
 * Workerman Cookie类
 */
class Cookie extends BaseCookie
{

    /**
     * Cookie初始化
     * @access public
     * @param  array $config
     * @return void
     */
    public function init(array $config = [])
    {
        $this->config = array_merge($this->config, array_change_key_case($config));
    }

    /**
     * Cookie 设置保存
     *
     * @access public
     * @param  string $name  cookie名称
     * @param  mixed  $value cookie值
     * @param  array  $option 可选参数
     * @return void
     */
    protected function setCookie($name, $value, $expire, $option = [])
    {
        WorkerHttp::setCookie($name, $value, $expire, $option['path'], $option['domain'], $option['secure'], $option['httponly']);
    }
}
