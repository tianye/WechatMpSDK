<?php
namespace Wechat\API;

use Wechat\Api;

/**
 * 微信二维码相关接口.
 *
 * @author Tian.
 */
class QrcodeApi extends BaseApi
{
    /**
     * 创建临时二维码 - 场景值ID(Int)
     *
     * @param  integer $scene_id       [场景值ID]
     * @param  integer $expire_seconds [该二维码有效时间，以秒为单位。 最大不超过2592000（即30天），此字段如果不填，则默认有效期为30秒]
     *
     * @return array  $res
     */
    public function create($scene_id = 0, $expire_seconds = 30)
    {
        if (!is_numeric($scene_id) || $scene_id < 0 || $scene_id > 4294967295) {
            $this->setError('scene_id 必须为整数,且 不能 小于 0 大于 4294967295');

            return false;
        }

        if (!is_numeric($expire_seconds) || $expire_seconds < 0 || $expire_seconds > 2592000) {
            $this->setError('expire_seconds 必须为整数,且 不能 小于 0 大于 2592000');

            return false;
        }

        $queryStr = [
            'expire_seconds' => $expire_seconds,
            'action_name'    => 'QR_SCENE',
            'action_info'    => [
                'scene_id' => $scene_id,
            ],
        ];

        $res = $this->_post('create', $queryStr);

        return $res;
    }

    /**
     * 创建永久二维码 - 场景值ID(Int)
     *
     * @param  integer $scene_id [场景值ID]
     *
     * @return array  $res
     */
    public function createLimitInt($scene_id = 0)
    {
        if (!is_numeric($scene_id) || $scene_id < 0 || $scene_id > 4294967295) {
            $this->setError('scene_id 必须为整数,且 不能 小于 0 大于 4294967295');

            return false;
        }

        $queryStr = [
            'action_name' => 'QR_LIMIT_SCENE',
            'action_info' => [
                'scene_id' => $scene_id,
            ],
        ];

        $res = $this->_post('create', $queryStr);

        return $res;
    }

    /**
     * 创建永久二维码 - 场景值Str(Str)
     *
     * @param  integer $scene_id [场景值ID]
     *
     * @return array  $res
     */
    public function createLimitStr($scene_str = '')
    {
        if (!is_string($scene_str) || is_numeric($scene_str)) {
            $this->setError('scene_str 必须为字符串');

            return false;
        }

        $queryStr = [
            'action_name' => 'QR_LIMIT_STR_SCENE',
            'action_info' => [
                'scene' => [
                    'scene_str' => $scene_str,
                ],
            ],
        ];

        $res = $this->_post('create', $queryStr);

        return $res;
    }

    /**
     * 通过ticket换取二维码
     *
     * @param  string $ticket [获取的二维码ticket，凭借此ticket可以在有效时间内换取二维码。]
     *
     * @return string        [是一张图片，可以直接展示或者下载]
     */
    public function show($ticket = '')
    {
        Api::setApiUrl('https://mp.weixin.qq.com/');

        $this->module = 'showqrcode';

        $queryStr = ['ticket' => $ticket];

        $res = $this->_get('', $queryStr);

        if (!$res) {
            E($this->getError());
        }

        return $res;
    }
}
