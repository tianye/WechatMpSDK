<?php

namespace Wechat\API;

use Wechat\Api;

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

        $this->module    = $className;
        $this->className = $className;
        $this->apitype   = 'cgi-bin';
    }

    /**
     * 获取AppId
     *
     * @return string AppId
     */
    public static function getAppId()
    {
        return Api::getAppId();
    }

    /**
     * 获取AppSecret
     *
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
     * @return bool|array 接口返回的结果
     */
    final protected function _get($node, array $queryStr, $arsort = true)
    {
        if (!is_array($queryStr)) {
            $this->setError('参数必须为一个数组');

            return false;
        }

        $module  = $this->module;
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
     * @return bool|array 接口返回的结果
     */
    final protected function _post($node, array $data, $jsonEncode = true)
    {
        if (!is_array($data)) {
            $this->setError('参数必须为一个数组');

            return false;
        }

        $module  = $this->module;
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
     * 获取api原始返回值
     *
     * @return string
     */
    final public function getApiData()
    {
        return Api::getApiData();
    }

    /**
     * 设置post操作的get参数.
     *
     * @param $name
     * @param $value
     */
    final public function setPostQueryStr($name, $value)
    {
        Api::setPostQueryStr($name, $value);
    }
}
