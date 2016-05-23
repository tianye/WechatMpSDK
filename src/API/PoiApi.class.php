<?php
namespace Wechat\API;

/**
 * 门店相关接口.
 *
 * @author Tian.
 */
class PoiApi extends BaseApi
{
    /**
     * 创建门店
     *
     * @param  array  $base_info 基本信息
     *
     * 必填:
     * @param $base_info->business_name     门店名称（仅为商户名，如：国美、麦当劳，不应包含地区、地址、分店名等信息，错误示例：北京国美
     * @param $base_info->branch_name       分店名称（不应包含地区信息，不应与门店名有重复，错误示例：北京王府井店
     * @param $base_info->province          门店所在的省份（直辖市填城市名,如：北京市）
     * @param $base_info->city              门店所在的城市
     * @param $base_info->district          门店所在地区
     * @param $base_info->address           门店所在的详细街道地址（不要填写省市信息
     * @param $base_info->telephone         门店的电话（纯数字，区号、分机号均由“-”隔开）
     * @param $base_info->categories        门店的类型（不同级分类用“,”隔开，如：美食，川菜，火锅。详细分类参见附件：微信门店类目表）
     * @param $base_info->offset_type       坐标类型，1 为火星坐标（目前只能选1）
     * @param $base_info->longitude         门店所在地理位置的经度
     * @param $base_info->latitude          门店所在地理位置的纬度（经纬度均为火星坐标，最好选用腾讯地图标记的坐标
     *
     * 非必填:
     * @param $base_info->photo_list        图片列表，url 形式，可以有多张图片，尺寸为
     * @param $base_info->special           特色服务，如免费wifi，免费停车，送货上门等商户能提供的特色功能或服务
     * @param $base_info->open_time         营业时间，24 小时制表示，用“-”连接，如 8:00-20:00
     * @param $base_info->avg_price         人均价格，大于0 的整数
     * @param $base_info->sid               商户自己的id，用于后续审核通过收到poi_id 的通知时，做对应关系。请商户自己保证唯一识别性
     * @param $base_info->introduction      商户简介，主要介绍商户信息等
     * @param $base_info->recommend         推荐品，餐厅可为推荐菜；酒店为推荐套房；景点为推荐游玩景点等，针对自己行业的推荐内容
     *
     * @return
     */
    public function addpoi($base_info = array())
    {
        if (empty($base_info) || !is_array($base_info)) {
            $this->setError('参数错误');
            return false;
        }

        $queryStr = array();
        $queryStr['business']['base_info'] = $base_info;

        $res = $this->_post('addpoi', $queryStr);

        return  $res;
    }

    /**
     * 查询门店信息
     *
     * @param  int $poi_id 创建门店后获取poi_id
     *
     * @return array
     */
    public function getpoi($poi_id)
    {
        if (empty($poi_id) || !is_numeric($poi_id)) {
            $this->setError('参数错误');
            return false;
        }

        $queryStr = array();
        $queryStr['poi_id'] = $poi_id;

        $res = $this->_post('getpoi', $queryStr);

        return  $res;
    }

    /**
     * 查询门店列表
     *
     * @param  integer $begin 开始位置，0 即为从第一条开始查询
     * @param  integer $limit 返回数据条数，最大允许50，默认为20
     *
     * @return  array
     */
    public function getpoilist($begin = 0, $limit = 20)
    {
        if (!is_numeric($begin) || $begin < 0 || !is_numeric($limit) || $limit > 50 || $limit < 0) {
            $this->setError('参数错误');
            return false;
        }

        $queryStr = array();
        $queryStr['begin'] = $begin;
        $queryStr['limit'] = $limit;

        $res = $this->_post('getpoilist', $queryStr);

        return  $res;
    }

    /**
     * 修改门店服务信息
     *
     * @param  array  $base_info 基本信息
     *
     * @return  array
     */
    public function updatepoi($base_info = array())
    {
        if (empty($base_info) || !is_array($base_info) || empty($base_info['poi_id'])) {
            $this->setError('参数错误');
            return false;
        }

        $queryStr = array();
        $queryStr['business']['base_info'] = $base_info;

        $res = $this->_post('updatepoi', $queryStr);

        return  $res;
    }

    /**
     * 删除门店
     *
     * @param  int $poi_id 创建门店后获取poi_id
     *
     * @return
     */
    public function delpoi($poi_id)
    {
        if (empty($poi_id) || !is_numeric($poi_id)) {
            $this->setError('参数错误');
            return false;
        }

        $queryStr = array();
        $queryStr['poi_id'] = $poi_id;

        $res = $this->_post('delpoi', $queryStr);

        return  $res;
    }

    /**
     * 门店类目表
     *
     * @return array
     */
    public function getwxcategory()
    {
        $queryStr = array();

        $res = $this->_get('getwxcategory', $queryStr);

        return  $res;
    }
}
