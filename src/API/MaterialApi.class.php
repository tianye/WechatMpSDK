<?php

namespace Wechat\API;

use Wechat\Api;

/**
 * 素材相关接口.
 *
 * @author Tian.
 */
class MaterialApi extends BaseApi
{
    /**
     * 获取素材列表.
     *
     * @param string $type 获取素材类型,
     * @param        $type $offset 偏移 ,
     * @param        $type $count 数量  不能大于20
     *
     * @return array. 注 返回值为图文(news)时 无法获得文章的正文
     */
    public function batchget($type = 'image', $offset = 0, $count = 20)
    {
        if (!is_string($type)) {
            $this->setError('参数错误,类型参数错误');

            return false;
        }

        $queryStr = [
            "type" => $type, 'offset' => $offset, 'count' => $count,
        ];

        $res = $this->_post('batchget_material', $queryStr);

        return $res;
    }

    /**
     *  上传图文素材.
     *
     * @param array $info 图文素材的信息, title 标题,thumb_media_id 封面图片ID,author 作者,digest 摘要,show_cover_pic 是否显示封面,content 内容支持html少于2万字符 小于1M,content_source_url 点击 阅读原文的url
     *
     * @return string.  成功返回mediaid
     */
    public function addNews($info)
    {
        if (!is_array($info)) {
            $this->setError('参数错误');

            return false;
        }

        $data = ['articles' => $info];

        $res = $this->_post('add_news', $data);

        return $res;
    }

    /**
     *  新增永久素材.
     *
     * @param string $file 媒体文件路径
     * @param string $type 文件类型 (video,image,voice,thumb)
     *
     * @return array. 媒体文件ID 获取地址  https://mmbiz.qlogo.cn/mmbiz/NdxGKqW8jE9GbAqUEPdSgSvbUbProSmE8NbUFwIYnp0Duibs611ZsCLza6b2dS8Ex3CO5dtv0u1HP9QY32djCxA/0?wx_fmt=jpeg   视频素材 官方接口也无法上传
     */
    public function add($file, $type = "image", $info = [])
    {
        if (!$file || !$type) {
            $this->setError('参数缺失');

            return false;
        }

        if (!file_exists($file)) {
            $this->setError('文件路径不正确');

            return false;
        }

        if ($type == "video") {
            if (!is_array($info)) {
                $this->setError('视频信息必须是数组格式');

                return false;
            }

            if (empty($info['title']) || empty($info['introduction'])) {
                $this->setError('上传视频请填写标题介绍');

                return false;
            }

            $description['title']        = $info['title'];
            $description['introduction'] = $info['introduction'];
            $des                         = json_encode($description);
            $data                        = ['media' => '@' . realpath($file), "type" => $type, "description" => $des];
        } else {
            $data = ['media' => '@' . realpath($file), "type" => $type];
        }

        $node = 'add_material';
        $res  = $this->_post($node, $data, false);

        return $res;
    }

    /**
     * 获取 永久素材.通常为图文素材 其他素材可以在GetMaterialList获取对应地址
     *
     * @param string $media_id 素材ID
     *
     * @return array.
     */
    public function get($media_id)
    {
        if (!is_string($media_id)) {
            $this->setError('参数错误,媒体ID参数错误');

            return false;
        }

        $queryStr = [
            'media_id' => $media_id,
        ];

        $res = $this->_post('get_material', $queryStr);

        return $res;
    }

    /**
     *   删除永久素材.
     *
     * @param string $media_id 素材ID
     *
     * @return bool.  成功返回ok
     */
    public function del($media_id)
    {
        if (!is_string($media_id)) {
            $this->setError('参数错误,媒体ID参数错误');

            return false;
        }

        $queryStr = [
            "media_id" => $media_id,
        ];

        $res = $this->_post('del_material', $queryStr);

        return $res;
    }

    /**
     *   修改永久素材.
     *
     * @param string $media_id 素材ID,$info 保存信息,$index 多图文素材时才有意义 更新位置
     *
     * @return bool.  成功返回ok
     */
    public function updateNews($media_id, $articles, $index = 0)
    {
        if (!is_string($media_id)) {
            $this->setError('参数错误,媒体ID参数错误');

            return false;
        }

        $queryStr = [
            "media_id" => $media_id,
            "index"    => intval($index),
            "articles" => $articles,
        ];

        $res = $this->_post('update_news', $queryStr);

        return $res;
    }

    /**
     * 获取素材总数.
     *
     * @return array.
     */
    public function getCount()
    {
        $queryStr = [];

        $res = $this->_get('get_materialcount', $queryStr);

        return $res;
    }
}
