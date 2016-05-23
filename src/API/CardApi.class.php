<?php
namespace Wechat\API;

use Wechat\Api;

/**
 * 卡券相关接口.
 *
 * @author Tian.
 */
class CardApi extends BaseApi
{
    /**
     * 获取卡券颜色
     * @return [array] [颜色列表]
     */
    public function getcolors()
    {
        $queryStr = array();

        $this->apitype = 'card';
        $this->module = 'getcolors';

        $res = $this->_get('', $queryStr);

        return  $res;
    }

    /**
     * 创建卡券
     *
     * @param  string $type          [卡券类型]
     * @param  array  $base_info     [必要字段]
     * @param  array  $especial      [特殊字段]
     * @param  array  $advanced_info [图文列表]
     *
     * @return string            [CardId]
     */
     public function create($type = 'member_card', $base_info = array(), $especial = array(), $advanced_info = array())
    {
        if (!is_string($type) || !is_array($base_info) || !is_array($especial)) {
            $this->setError('参数缺失');
            return false;
        }

        $card = array();
        $card['card'] = array();
        $card['card']['card_type'] = strtoupper($type);

        $type = strtolower($type);

        $card['card'][$type] = array();

        $card_info = array();
        $card_info['base_info'] = $base_info;

        $card['card'][$type] = array_merge($card_info, $especial, $advanced_info);

        $this->apitype = 'card';
        $this->module = 'create';

        $res = $this->_post('', $card);

        return $res;
    }

    /**
     * 卡券二维码
     * @param  [array] $card  卡列表
     * @return [type]       [description]
     */
    public function qrcode($card)
    {
        if (!is_array($card)) {
            $this->setError('参数缺失');
            return false;
        }

        $this->apitype = 'card';
        $this->module = 'qrcode';

        $res = $this->_post('create', $card);

        return  $res;
    }

    /**
     * 通过ticket换取二维码
     * @param  string $ticket [获取的二维码ticket，凭借此ticket可以在有效时间内换取二维码。]
     * @return [type]         [是一张图片，可以直接展示或者下载]
     */
    public function showqrcode($ticket = '')
    {
        Api::setApiUrl('https://mp.weixin.qq.com/');

        $this->apitype = 'cgi-bin';
        $this->module = 'showqrcode';

        $queryStr = array('ticket' => $ticket);

        $res = $this->_get('', $queryStr);

        return $res;
    }

    /**
     * 通过ticket换取二维码 链接
     * @param  string $ticket [获取的二维码ticket，凭借此ticket可以在有效时间内换取二维码。]
     * @return [type]         [是一张图片，可以直接展示或者下载]
     */
    public function showqrcode_url($ticket = '')
    {
        if (!is_string($ticket) || $ticket == '') {
            $this->setError('参数错误');
            return false;
        }

        $url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$ticket;

        return $url;
    }

    /**
     * 获取 卡券 Api_ticket
     * @param  boolean $jus  是否强制刷新
     * @return string  $api_ticket  api_ticket
     */
    public function cardApiTicket($jus = false)
    {
        $key = $this->getAppId().'card_api_ticket';
        $api_ticket = S($key);

        if (false == $api_ticket || $jus) {
            $this->apitype = 'cgi-bin';
            $this->module = 'ticket';

            $queryStr = array('type' => 'wx_card');

            $res = $this->_get('getticket', $queryStr);

            if (false === $res) {
                exit('获取Card Api Ticket失败!');
            }

            $api_ticket = $res['ticket'];

            S($key, $api_ticket, 7200 - 300);
        }

        return $api_ticket;
    }

    /**
     * 微信卡券：JSAPI 卡券Package - 基础参数没有附带任何值 - 再生产环境中需要根据实际情况进行修改
     *
     * @param string  $card_id
     * @param int     $code
     * @param int     $openid
     * @param int     $outer_id
     * @param int     $timestamp
     * @param int     $api_ticket
     *
     * @return array
     */
    public function wxCardPackage(array $card_list, $timestamp = null, $api_ticket = null)
    {
        if (!is_array($card_list)) {
            $this->setError('参数缺失');
            return false;
        }

        if (empty($timestamp) || $timestamp == '') {
            $timestamp = time();
        }

        if (empty($api_ticket) || $api_ticket == '') {
            $api_ticket = $this->cardApiTicket();
        }

        $resultArray = array();
        foreach ($card_list as $key => $value) {
            if (empty($value['code']) || !isset($value['code'])) {
                $value['code'] = '';
            }
            if (empty($value['openid']) || !isset($value['openid'])) {
                $value['openid'] = '';
            }
            $arrays = array($api_ticket, $timestamp, $value['card_id'], $value['code'], $value['openid']);
            sort($arrays, SORT_STRING);
            $string = sha1(implode($arrays));

            $resultArray['cardList'][$key]['cardId'] = $value['card_id'];
            $resultArray['cardList'][$key]['cardExt']['code'] = $value['code'];
            $resultArray['cardList'][$key]['cardExt']['openid'] = $value['openid'];

            $resultArray['cardList'][$key]['cardExt']['timestamp'] = $timestamp;
            $resultArray['cardList'][$key]['cardExt']['signature'] = $string;

            if (!empty($value['outer_id'])) {
                $resultArray['cardList'][$key]['cardExt']['outer_id']  = $value['outer_id'];
            }
            $resultArray['cardList'][$key]['cardExt'] = json_encode($resultArray['cardList'][$key]['cardExt']);
        }
        $resultJson = json_encode($resultArray);

        return $resultJson;
    }

    /**
     * 创建货架接口
     *
     * @param  url     $banner     页面的banner图片链接
     * @param  string  $page_title 页面的title
     * @param  boolean $can_share  页面是否可以分享,填入true/false
     * @param  string  $scene      投放页面的场景值 SCENE_NEAR_BY 附近 SCENE_MENU 自定义菜单 SCENE_QRCODE  二维码 SCENE_ARTICLE   公众号文章 SCENE_H5  h5页面 SCENE_IVR  自动回复 SCENE_CARD_CUSTOM_CELL 卡券自定义cell
     * @param  array   $card_list  卡券列表，每个item有两个字段
     * @param  string  $card_list->cardid       所要在页面投放的cardid
     * @param  url     $card_list->thumb_url    缩略图url
     *
     * @return url 货架链接  page_id 货架ID。货架的唯一标识。
     */
    public function landingpage($banner, $page_title, $can_share = false, $scene = 'SCENE_CARD_CUSTOM_CELL', $card_list = array())
    {
        if (empty($banner) || empty($page_title) || !is_bool($can_share) || !is_string($scene) || !is_array($card_list)) {
            $this->setError('参数错误');
            return false;
        }

        $queryStr = array();
        $queryStr['banner']     = $banner;
        $queryStr['page_title'] = $page_title;
        $queryStr['can_share']  = (bool)$can_share;
        $queryStr['scene']      = strtoupper($scene);
        $queryStr['card_list']  = $card_list;

        $this->apitype = 'card';
        $this->module = 'landingpage';
        $res = $this->_post('create', $queryStr);

        return  $res;
    }

    /**
     * 导入code接口
     *
     * @param  [string] $card_id 卡券Id
     * @param  [array] $code    自定义code 数组
     *
     * @return array
     */
    public function deposit($card_id, $code = array())
    {
        if (!is_string($card_id) || !is_array($code)) {
            $this->setError('参数错误');
            return false;
        }

        $queryStr = array();
        $queryStr['card_id'] = $card_id;
        $queryStr['code']    = $code;

        $this->apitype = 'card';
        $this->module = 'code';
        $res = $this->_post('deposit', $queryStr);

        return  $res;
    }

    /**
     * 查询导入code数目
     * @param string $card_id 卡券ID
     *
     * @return int
     */
    public function getdepositcount($card_id)
    {
        if (!is_string($card_id)) {
            $this->setError('参数错误');
            return false;
        }

        $queryStr = array();
        $queryStr['card_id'] = $card_id;

        $this->apitype = 'card';
        $this->module = 'code';
        $res = $this->_post('getdepositcount', $queryStr);

        return  $res;
    }

    /**
     * 核查code接口
     *
     * @param  [string] $card_id 卡券Id
     * @param  [array] $code    自定义code 数组
     *
     */
    public function checkcode($card_id, $code = array())
    {
        if (!is_string($card_id) || !is_array($code)) {
            $this->setError('参数错误');
            return false;
        }

        $queryStr = array();
        $queryStr['card_id'] = $card_id;
        $queryStr['code']    = $code;

        $this->apitype = 'card';
        $this->module = 'code';
        $res = $this->_post('checkcode', $queryStr);

        return  $res;
    }

    /**
     * 图文消息群发卡券
     * @param  [type] $card_id [description]
     * @return  array
     */
    public function gethtml($card_id)
    {
        if (!is_string($card_id)) {
            $this->setError('参数错误');
            return false;
        }

        $queryStr = array();
        $queryStr['card_id'] = $card_id;

        $this->apitype = 'card';
        $this->module = 'mpnews';
        $res = $this->_post('gethtml', $queryStr);

        return  $res;
    }

    /**
     * 设置测试白名单
     * @param  array  $openid   白名单 openid 列表
     * @param  array  $username 白名单 微信号 列表
     * @return
     */
    public function testwhitelist($openid = array(), $username  = array())
    {
        if (!is_array($openid) || !is_array($username)) {
            $this->setError('参数错误');
            return false;
        }

        $queryStr = array();
        $queryStr['openid'] = $openid;
        $queryStr['username'] = $username;

        $this->apitype = 'card';
        $this->module = 'testwhitelist';
        $res = $this->_post('set', $queryStr);

        return  $res;
    }

    /**
     * 查询Code接口
     * @param  string  $code          单张卡券的唯一标准。
     * @param  boolean $check_consume 是否校验code核销状态，填入true和false时的code异常状态返回数据不同。
     * @param  string  $card_id       卡券ID代表一类卡券。自定义code卡券必填。
     * @return array
     */
    public function codeGet($code, $check_consume = true, $card_id = '')
    {
        if (empty($code) || !is_bool($check_consume)) {
            $this->setError('参数错误');
            return false;
        }

        $queryStr = array();
        if (!empty($card_id)) {
            $queryStr['card_id']       = $card_id;
        }
        $queryStr['code']          = $code;
        $queryStr['check_consume'] = $check_consume;

        $this->apitype = 'card';
        $this->module = 'code';
        $res = $this->_post('get', $queryStr);

        return  $res;
    }

    /**
     * 核销卡券
     * @param  [type] $code    需核销的Code码
     * @param  string $card_id 卡券ID。创建卡券时use_custom_code填写true时必填。非自定义Code不必填写。
     * @return array
     */
    public function consume($code, $card_id = '')
    {
        if (empty($code)) {
            $this->setError('参数错误');
            return false;
        }

        $queryStr = array();

        if (!empty($card_id)) {
            $queryStr['card_id'] = $card_id;
        }

        $queryStr['code'] = $code;

        $this->apitype = 'card';
        $this->module = 'code';
        $res = $this->_post('consume', $queryStr);

        return  $res;
    }

    /**
     * Code解码接口
     * @param  [string] $encrypt_code  经过加密的Code码。
     * @return [string] code
     */
    public function decrypt($encrypt_code)
    {
        if (!is_string($encrypt_code)) {
            $this->setError('参数错误');
            return false;
        }

        $queryStr = array();
        $queryStr['encrypt_code'] = $encrypt_code;

        $this->apitype = 'card';
        $this->module = 'code';
        $res = $this->_post('decrypt', $queryStr);

        return  $res;
    }


    /**
     * 用于获取用户卡包里的，属于该appid下的卡券
     * @param  string $openid  需要查询的用户openid
     * @param  string $card_id 卡券ID。不填写时默认查询当前appid下的卡券
     * @return array
     */
    public function getcardlist($openid, $card_id = '')
    {
        if (empty($openid)) {
            $this->setError('缺少openid');
            return false;
        }

        $queryStr = array();
        $queryStr['openid'] = $openid;

        if (!empty($card_id)) {
            $queryStr['card_id'] = $card_id;
        }

        $this->apitype = 'card';
        $this->module = 'user';
        $res = $this->_post('getcardlist', $queryStr);

        return  $res;
    }

    /**
     * 获取卡券内容
     *
     * @param  string $card_id 卡券ID
     *
     * @return array
     */
    public function cardGet($card_id)
    {
        if (empty($card_id)) {
            $this->setError('缺少card_id');
            return false;
        }

        $queryStr = array();
        $queryStr['card_id'] = $card_id;

        $this->apitype = 'card';
        $this->module = 'get';
        $res = $this->_post('', $queryStr);

        return  $res;
    }


    /**
     * 批量查询卡列表
     *
     * @param integer $offset      查询卡列表的起始偏移量，从0开始，即offset: 5是指从从列表里的第六个开始读取
     * @param integer $count       需要查询的卡片的数量（数量最大50）。
     * @param string  $status_list 支持开发者拉出指定状态的卡券列表
     *
     * @return  array
     */
    public function batchget($offset = 0, $count = 10, $status_list = 'CARD_STATUS_VERIFY_OK')
    {
        if (!is_numeric($offset) || $offset < 0) {
            $this->setError('offset 参数不正确');
            return false;
        }

        if (!is_numeric($count) || $count < 0 || $count > 50) {
            $this->setError('count 参数不正确');
            return false;
        }

        $queryStr = array();
        $queryStr['offset']      = $offset;
        $queryStr['count']       = $count;
        $queryStr['status_list'] = $status_list;

        $this->apitype = 'card';
        $this->module = 'batchget';
        $res = $this->_post('', $queryStr);

        return  $res;
    }

     /**
     * 修改卡券
     * @param  string $card_id   [卡券ID]
     * @param  string $type      [卡券类型]
     * @param  array  $base_info [必要字段]
     * @param  array  $especial  [特殊字段]
     * @return send_check   是否提交审核，false为修改后不会重新提审，true为修改字段后重新提审，该卡券的状态变为审核中
     */
    public function update($card_id, $type, $base_info = array(), $especial = array())
    {
        if (empty($card_id) || empty($type) || !is_array($base_info) || !is_array($especial)) {
            $this->setError('参数缺失');
            return false;
        }

        $card = array();
        $card['card_id'] = $card_id;
        $card[$type] = array();

        $card_info = array();
        $card_info['base_info'] = $base_info;

        $card[$type] = array_merge($card_info, $especial);

        $this->apitype = 'card';
        $this->module = 'update';
        $res = $this->_post('', $card);

        return  $res;
    }

    /**
     * 设置微信买单接口
     *
     * @param  string  $card_id 卡券ID
     * @param  boolean $is_open 是否开启买单功能，填true/false
     *
     * @return
     */
    public function paycellSet($card_id, $is_open = true)
    {
        $queryStr = array();
        $queryStr['card_id'] = $card_id;
        $queryStr['is_open'] = $is_open;

        $this->apitype = 'card';
        $this->module = 'paycell';

        $res = $this->_post('set', $queryStr);

        return  $res;
    }

    /**
     * 修改库存接口
     * @param  string  $card_id 卡券ID
     * @param  string  $stock   操作 increase(增加) reduce(减少)
     * @param  integer $value   数值
     * @return [type]           [description]
     */
    public function modifystock($card_id, $stock = 'increase', $value = 0)
    {
        $queryStr = array();
        $queryStr['card_id'] = $card_id;
        if ($stock == 'increase') {
            $queryStr['increase_stock_value'] = intval($value);
        } elseif ($stock == 'reduce') {
            $queryStr['reduce_stock_value']   = intval($value);
        } else {
            $this->setError('$stock 参数错误');
            return false;
        }

        $this->apitype = 'card';
        $this->module = 'modifystock';

        $res = $this->_post('', $queryStr);

        return  $res;
    }

    /**
     * 更改Code接口
     *
     * @param  string $code     需变更的Code码
     * @param  string $new_code 变更后的有效Code码
     * @param  string $card_id  卡券ID。自定义Code码卡券为必填
     *
     * @return
     */
    public function codeUpdate($code, $new_code, $card_id)
    {
        if (empty($code) || empty($new_code)) {
            $this->setError('缺少错误');
            return false;
        }

        $queryStr = array();
        $queryStr['code']     = $code;
        $queryStr['new_code'] = $new_code;
        if (!empty($card_id)) {
            $queryStr['card_id']  = $card_id;
        }

        $this->apitype = 'card';
        $this->module = 'code';

        $res = $this->_post('update', $queryStr);

        return  $res;
    }

    /**
     * 删除卡券接口
     *
     * @param  string $card_id  变更后的有效Code码。
     *
     * @return
     */
    public function cardDelete($card_id)
    {
        if (empty($card_id)) {
            $this->setError('缺少错误');
            return false;
        }

        $queryStr = array();
        $queryStr['card_id'] = $card_id;

        $this->apitype = 'card';
        $this->module = 'delete';
        $res = $this->_post('', $queryStr);

        return  $res;
    }

     /**
     * 设置卡券失效
     *
     * @param  string $code     设置失效的Code码。
     * @param  string $card_id  卡券ID 非自定义code 可不填。
     *
     * @return
     */
    public function unavailable($code, $card_id)
    {
        if (empty($code)) {
            $this->setError('缺少错误');
            return false;
        }

        $queryStr = array();
        $queryStr['code'] = $code;

        if (!empty($card_id)) {
            $queryStr['card_id'] = $card_id;
        }

        $this->apitype = 'card';
        $this->module = 'code';
        $res = $this->_post('unavailable', $queryStr);

        return  $res;
    }

    /**
     * 拉取卡券概况数据接口
     * @param  string $begin_date  查询数据的起始时间。
     * @param  int $end_date       查询数据的截至时间。
     * @param  int $cond_source    卡券来源，0为公众平台创建的卡券数据、1是API创建的卡券数据
     * @return array
     */
    public function getcardbizuininfo($begin_date, $end_date, $cond_source = 0)
    {
        if (empty($begin_date) || empty($end_date) || !is_numeric($cond_source) || $cond_source < 0 || $cond_source > 1) {
            $this->setError('参数错误');
            return false;
        }

        if (is_numeric($begin_date)) {
            $begin_date = date('Y-m-d', $begin_date);
        }

        if (is_numeric($end_date)) {
            $end_date = date('Y-m-d', $end_date);
        }

        $queryStr = array();
        $queryStr['begin_date']  = $begin_date;
        $queryStr['end_date']    = $end_date;
        $queryStr['cond_source'] = intval($cond_source);

        $this->apitype = 'datacube';
        $this->module  = 'getcardbizuininfo';
        $res = $this->_post('', $queryStr);

        return  $res;
    }

    /**
     * 获取免费券数据接口
     * @param  string $begin_date  查询数据的起始时间。
     * @param  int    $end_date       查询数据的截至时间。
     * @param  int    $cond_source    卡券来源，0为公众平台创建的卡券数据、1是API创建的卡券数据
     * @param  string $card_id    卡券来源，0为公众平台创建的卡券数据、1是API创建的卡券数据
     * @return array
     */
    public function getcardcardinfo($begin_date, $end_date, $cond_source = 0, $card_id = '')
    {
        if (empty($begin_date) || empty($end_date) || !is_numeric($cond_source) || $cond_source < 0 || $cond_source > 1) {
            $this->setError('参数错误');
            return false;
        }

        if (is_numeric($begin_date)) {
            $begin_date = date('Y-m-d', $begin_date);
        }

        if (is_numeric($end_date)) {
            $end_date = date('Y-m-d', $end_date);
        }

        $queryStr = array();
        $queryStr['begin_date']  = $begin_date;
        $queryStr['end_date']    = $end_date;
        $queryStr['cond_source'] = intval($cond_source);

        if (!empty($card_id)) {
            $queryStr['card_id'] = $card_id;
        }

        $this->apitype = 'datacube';
        $this->module  = 'getcardcardinfo';
        $res = $this->_post('', $queryStr);

        return  $res;
    }

    /**
     * 拉取会员卡数据接口
     * @param  string $begin_date  查询数据的起始时间。
     * @param  int $end_date       查询数据的截至时间。
     * @param  int $cond_source    卡券来源，0为公众平台创建的卡券数据、1是API创建的卡券数据
     * @return array
     */
    public function getcardmembercardinfo($begin_date, $end_date, $cond_source = 0)
    {
        if (empty($begin_date) || empty($end_date) || !is_numeric($cond_source) || $cond_source < 0 || $cond_source > 1) {
            $this->setError('参数错误');
            return false;
        }

        if (is_numeric($begin_date)) {
            $begin_date = date('Y-m-d', $begin_date);
        }

        if (is_numeric($end_date)) {
            $end_date = date('Y-m-d', $end_date);
        }

        $queryStr = array();
        $queryStr['begin_date']  = $begin_date;
        $queryStr['end_date']    = $end_date;
        $queryStr['cond_source'] = intval($cond_source);

        $this->apitype = 'datacube';
        $this->module  = 'getcardmembercardinfo';
        $res = $this->_post('', $queryStr);

        return  $res;
    }

    /**
     * 会员卡接口激活
     * @param  array  $activate 数据
     * @return
     */
    public function activate($activate = array())
    {
        if (empty($activate) || !is_array($activate)) {
            $this->setError('参数错误');
            return false;
        }

        $queryStr = $activate;

        $this->apitype = 'card';
        $this->module  = 'membercard';
        $res = $this->_post('activate', $queryStr);

        return  $res;
    }

    /**
     * 设置开卡字段接口
     *
     * @param  [type] $card_id             卡券ID。
     * @param  array  $required_form       会员卡激活时的必填选项。
     * @param  array  $optional_form       会员卡激活时的选填项。
     *
     * @param  string common_field_id_list 微信格式化的选项类型。见以下列表。
     * @param  string custom_field_list    喜欢的家具风格 自定义选项名称。
     *
     * @return
     */
    public function activateuserform($card_id, $required_form = array(), $optional_form = array())
    {
        if (empty($card_id) || !is_array($required_form) || !is_array($optional_form)) {
            $this->setError('参数错误');
            return false;
        }

        $queryStr = array();
        $queryStr['card_id'] = $card_id;

        $queryStr = array_merge($queryStr, $required_form, $optional_form);

        $this->apitype = 'card';
        $this->module  = 'membercard';
        $res = $this->_post('activateuserform/set', $queryStr);

        return  $res;
    }

    /**
     * 拉取会员信息接口
     *
     * @param  string $card_id CardID
     * @param  string $code    Code
     *
     * @return array
     */
    public function membercardUserinfo($card_id, $code)
    {
        if (empty($card_id) || empty($code)) {
            $this->setError('缺少参数');
            return false;
        }

        $queryStr = array();
        $queryStr['card_id'] = $card_id;
        $queryStr['code']    = $code;

        $this->apitype = 'card';
        $this->module  = 'membercard';
        $res = $this->_post('userinfo/get', $queryStr);

        return  $res;
    }

    /**
     * 更新会员信息
     *
     * @param  array $updateuser 参数
     *
     * @return
     */
    public function membercardUpdateuser($updateuser = array())
    {
        if (empty($updateuser) || !is_array($updateuser)) {
            $this->setError('参数错误');
            return false;
        }

        $queryStr = $updateuser;

        $this->apitype = 'card';
        $this->module  = 'membercard';
        $res = $this->_post('updateuser', $queryStr);

        return  $res;
    }

    /**
     * 添加子商户
     *
     * @param string $brand_name            子商户名称（12个汉字内），该名称将在制券时填入并显示在卡券页面上
     * @param string $logo_url              子商户logo，可通过上传logo接口获取。该logo将在制券时填入并显示在卡券页面上
     * @param string $protocol              授权函ID，即通过上传临时素材接口上传授权函后获得的meida_id
     * @param int    $end_time              授权函有效期截止时间（东八区时间，单位为秒），需要与提交的扫描件一致
     * @param string $primary_category_id   一级类目id,可以通过本文档中接口查询
     * @param string $secondary_category_id 二级类目id，可以通过本文档中接口查询
     * @param string $agreement_media_id    营业执照或个体工商户营业执照彩照或扫描件
     * @param string $operator_media_id     营业执照内登记的经营者身份证彩照或扫描件
     * @param string $app_id                子商户的公众号app_id，配置后子商户卡券券面上的app_id为该app_id。注意：该app_id须经过认证
     *
     * @return bool|array
     */
    public function submerchant($brand_name, $logo_url, $protocol, $end_time, $primary_category_id, $secondary_category_id, $agreement_media_id, $operator_media_id, $app_id = '')
    {
        $queryStr                                  = array();
        if (!empty($app_id)) {
            $queryStr['info']['app_id'] = $app_id;
        }
        $queryStr['info']['brand_name']            = $brand_name;
        $queryStr['info']['logo_url']              = $logo_url;
        $queryStr['info']['protocol']              = $protocol;
        $queryStr['info']['end_time']              = intval($end_time);
        $queryStr['info']['primary_category_id']   = $primary_category_id;
        $queryStr['info']['secondary_category_id'] = $secondary_category_id;
        $queryStr['info']['agreement_media_id']    = $agreement_media_id;
        $queryStr['info']['operator_media_id']     = $operator_media_id;

        $this->apitype = 'card';
        $this->module  = 'submerchant';

        $res = $this->_post('submit', $queryStr);

        return $res;
    }
}
