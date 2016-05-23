<?php
namespace Wechat\API;

/**
 * 模板相关接口.
 *
 * @author Tian.
 */
class TemplateApi extends BaseApi
{
    /**
     * 设置所属行业
     *
     * @param  int $industry_id1 [公众号模板消息所属行业编号]
     * @param  int $industry_id2 [公众号模板消息所属行业编号]
     *
     * @return bool|array
     */
    public function apiSetIndustry($industry_id1, $industry_id2)
    {
        if (!is_numeric($industry_id1) || !is_numeric($industry_id2)) {
            $this->setError('参数错误');

            return false;
        }

        $queryStr = [
            'industry_id1' => $industry_id1,
            'industry_id2' => $industry_id2,
        ];

        $res = $this->_post('api_set_industry', $queryStr);

        return $res;
    }

    /**
     * 获得模板ID
     *
     * @param  String $template_id_short [公众号模板消息所属行业编号]
     *
     * @return bool|array
     */
    public function apiAddTemplate($template_id_short)
    {
        if (!is_string($template_id_short)) {
            $this->setError('参数错误');

            return false;
        }

        $queryStr = [
            'template_id_short' => $template_id_short,
        ];

        $res = $this->_post('api_add_template', $queryStr);

        return $res;
    }

    /**
     * 发送模板消息
     *
     * @param  string $touser      [OPENID]
     * @param  string $template_id [模板ID]
     * @param  string $url         [跳转url]
     * @param  array  $data        [模板参数]
     *
     * @return int               [msgId]
     *
     */
    public function send($touser, $template_id, $url, $data = [])
    {
        if (!is_string($touser) || !is_string($template_id) || !is_array($data) || empty($data)) {
            $this->setError('参数错误');

            return false;
        }

        $queryStr = [
            'touser'      => $touser,
            'template_id' => $template_id,
            'url'         => $url,
            'data'        => $data,
        ];

        $this->module = 'message';

        $res = $this->_post('template/send', $queryStr);

        return $res;
    }
}
