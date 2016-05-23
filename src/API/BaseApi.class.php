<?php

namespace Wechat\API;

use Wechat\Api;
use Think\Log;

class BaseApi
{
    protected $module; // 接口模块
    protected $className;
    protected $apitype;

    protected $AppId;
    protected $AppSecret;

    /**
     * 构造方法 根据类名设置 当前要访问的接口模块.
     *
     * @author Tian
     *
     * @date   2015-12-08
     */
    public function __construct()
    {
        $className = get_called_class();
        $className = explode('\\', $className);
        $className = end($className);
        $className = str_replace('Api', '', $className);
        $className = strtolower($className);

        $this->module = $className;
        $this->className = $className;
        $this->apitype = 'cgi-bin';
    }

    /**
     * 获取AppId
     * @return string AppId
     */
    public static function getAppId()
    {
        return Api::getAppId();
    }

    /**
     * 获取AppSecret
     * @return string AppSecret
     */
    public static function getAppSecret()
    {
        return Api::getAppSecret();
    }

    /**
     * get发送数据.
     *
     * @author Tian
     *
     * @date   2015-12-08
     *
     * @param string $node     接口节点
     * @param array  $queryStr 需要携带的查询字符串
     *
     * @return 接口返回的结果
     */
    final protected function _get($node, array $queryStr, $arsort = true)
    {
        if (!is_array($queryStr)) {
            $this->setError('参数必须为一个数组');

            return false;
        }

        $module = $this->module;
        $apitype = $this->apitype;

        if ($this->module != $this->className) {
            $this->module = $this->className;
        }

        return Api::_get($module, $node, $queryStr, $arsort, $apitype);
    }

    /**
     * post发送数据.
     *
     * @author Tian
     *
     * @date   2015-12-08
     *
     * @param string $node       接口节点
     * @param array  $data       需要发送的数据
     * @param bool   $jsonEncode 是否转换为jsons数据
     *
     * @return 接口返回的结果
     */
    final protected function _post($node, array $data, $jsonEncode = true)
    {
        if (!is_array($data)) {
            $this->setError('参数必须为一个数组');

            return false;
        }

        $module = $this->module;
        $apitype = $this->apitype;

        if ($this->module != $this->className) {
            $this->module = $this->className;
        }

        return Api::_post($module, $node, $data, $jsonEncode, $apitype);
    }

    /**
     * 设置错误信息.
     *
     * @author Tian
     *
     * @date   2015-12-08
     *
     * @param string $error 错误信息
     */
    final protected function setError($error)
    {
        Api::setError($error);
    }

    /**
     * 返回错误信息.
     *
     * @author Tian
     *
     * @date   2015-12-08
     *
     * @return string
     */
    final public function getError()
    {
        return Api::getError();
    }

    /**
     * 设置post操作的get参数.
     *
     * @author Tian
     *
     * @date   2015-08-03
     *
     * @param string $name  参数名
     * @param string $value 值
     */
    final public function setPostQueryStr($name, $value)
    {
        return Api::setPostQueryStr($name, $value);
    }

    /**
     * 日志调试方法
     *
     * @author Cui
     *
     * @date   2015-10-01
     *
     * @param  string|int|array    $info  信息
     * @param  string              $level 日志级别
     */
    public function wlog($info, $level = Log::DEBUG)
    {
        if (is_array($info)) {
            $info = print_r($info, true);
        }

        Log::record($info, $level);
    }
}
