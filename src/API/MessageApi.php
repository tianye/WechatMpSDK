<?php
namespace Wechat\API;

/**
 * 微信被动回复.
 *
 * @author Tian.
 */
class MessageApi extends BaseApi
{
    /**
     * [__call 魔术方法 生成回调array]
     *
     * @param  mixed $MsgType [类型]
     * @param  array $datas   [参数]
     *
     * @return string xml          [XML]
     */
    public function __call($MsgType, $datas)
    {
        $MsgType = strtolower($MsgType);

        $data               = [];
        $data['CreateTime'] = time();
        $data['MsgType']    = $MsgType;

        $datas = reset($datas);

        if ($MsgType == 'text') {
            $data['Content'] = $datas['Content'];
        } elseif ($MsgType == 'news') {
            $data['ArticleCount'] = count($datas['item']);
            $data['Articles']     = $datas;
        } elseif ($MsgType == 'music') {
            $data['Music'] = $datas;
        } elseif ($MsgType == 'video') {
            $data['Video'] = $datas;
        } else {
            $data[$MsgType] = $datas;
        }

        return $data;
    }
}
