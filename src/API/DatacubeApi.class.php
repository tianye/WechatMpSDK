<?php
namespace Wechat\API;

/**
 * 分析数据接口.
 *
 * 最大时间跨度是指一次接口调用时最大可获取数据的时间范围，如最大时间跨度为7是指最多一次性获取7天的数据。access_token的实际值请通过“获取access_token”来获取。
 *
 *关于周数据与月数据，请注意：每个月/周的周期数据的数据标注日期在当月/当周的第一天（当月1日或周一）。在某一月/周过后去调用接口，才能获取到该周期的数据。比如，在12月1日以（11月1日-11月5日）作为（begin_date和end_date）调用获取月数据接口，可以获取到11月1日的月数据（即11月的月数据）。
 *
 * @author Tian.
 */
class DatacubeApi extends BaseApi
{

/**
            用户分析数据接口
 */

    /**
     * 获取用户增减数据 (最大时间跨度 7 天)
     *
     * @param  string $begin_date 获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param  string $end_date   获取数据的结束日期，end_date允许设置的最大值为昨日
     *
     * @return array
     */
    public function getusersummary($begin_date, $end_date)
    {
        $this->apitype = 'datacube';
        $this->module = 'getusersummary';

        $queryStr = array(
            'begin_date' => $begin_date,
            'end_date'   => $end_date
        );

        $res = $this->_post('', $queryStr);

        return $res;
    }

     /**
     * 获取累计用户数据 (最大时间跨度 7 天)
     *
     * @param  string $begin_date 获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param  string $end_date   获取数据的结束日期，end_date允许设置的最大值为昨日
     *
     * @return array
     */
    public function getusercumulate($begin_date, $end_date)
    {
        $this->apitype = 'datacube';
        $this->module = 'getusercumulate';

        $queryStr = array(
            'begin_date' => $begin_date,
            'end_date'   => $end_date
        );

        $res = $this->_post('', $queryStr);

        return $res;
    }

/**
            图文分析数据接口
 */

    /**
     * 获取图文群发每日数据 (最大时间跨度 1 天)
     *
     * @param  string $begin_date 获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param  string $end_date   获取数据的结束日期，end_date允许设置的最大值为昨日
     *
     * @return array
     */
    public function getarticlesummary($begin_date, $end_date)
    {
        $this->apitype = 'datacube';
        $this->module = 'getarticlesummary';

        $queryStr = array(
            'begin_date' => $begin_date,
            'end_date'   => $end_date
        );

        $res = $this->_post('', $queryStr);

        return $res;
    }

    /**
     * 获取图文群发总数据 (最大时间跨度 1 天)
     *
     * @param  string $begin_date 获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param  string $end_date   获取数据的结束日期，end_date允许设置的最大值为昨日
     *
     * @return array
     */
    public function getarticletotal($begin_date, $end_date)
    {
        $this->apitype = 'datacube';
        $this->module = 'getarticletotal';

        $queryStr = array(
            'begin_date' => $begin_date,
            'end_date'   => $end_date
        );

        $res = $this->_post('', $queryStr);

        return $res;
    }

    /**
     * 获取图文统计数据 (最大时间跨度 3 天)
     *
     * @param  string $begin_date 获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param  string $end_date   获取数据的结束日期，end_date允许设置的最大值为昨日
     *
     * @return array
     */
    public function getuserread($begin_date, $end_date)
    {
        $this->apitype = 'datacube';
        $this->module = 'getuserread';

        $queryStr = array(
            'begin_date' => $begin_date,
            'end_date'   => $end_date
        );

        $res = $this->_post('', $queryStr);

        return $res;
    }

    /**
     * 获取图文统计分时数据 (最大时间跨度 1 天)
     *
     * @param  string $begin_date 获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param  string $end_date   获取数据的结束日期，end_date允许设置的最大值为昨日
     *
     * @return array
     */
    public function getuserreadhour($begin_date, $end_date)
    {
        $this->apitype = 'datacube';
        $this->module = 'getuserreadhour';

        $queryStr = array(
            'begin_date' => $begin_date,
            'end_date'   => $end_date
        );

        $res = $this->_post('', $queryStr);

        return $res;
    }

    /**
     * 获取图文分享转发数据 (最大时间跨度 7 天)
     *
     * @param  string $begin_date 获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param  string $end_date   获取数据的结束日期，end_date允许设置的最大值为昨日
     *
     * @return array
     */
    public function getusershare($begin_date, $end_date)
    {
        $this->apitype = 'datacube';
        $this->module = 'getusershare';

        $queryStr = array(
            'begin_date' => $begin_date,
            'end_date'   => $end_date
        );

        $res = $this->_post('', $queryStr);

        return $res;
    }

    /**
     * 获取图文分享转发分时数据 (最大时间跨度 1 天)
     *
     * @param  string $begin_date 获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param  string $end_date   获取数据的结束日期，end_date允许设置的最大值为昨日
     *
     * @return array
     */
    public function getusersharehour($begin_date, $end_date)
    {
        $this->apitype = 'datacube';
        $this->module = 'getusersharehour';

        $queryStr = array(
            'begin_date' => $begin_date,
            'end_date'   => $end_date
        );

        $res = $this->_post('', $queryStr);

        return $res;
    }

/**
            消息分析数据接口
 */

    /**
     * 获取消息发送概况数据 (最大时间跨度 7 天)
     *
     * @param  string $begin_date 获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param  string $end_date   获取数据的结束日期，end_date允许设置的最大值为昨日
     *
     * @return array
     */
    public function getupstreammsg($begin_date, $end_date)
    {
        $this->apitype = 'datacube';
        $this->module = 'getupstreammsg';

        $queryStr = array(
            'begin_date' => $begin_date,
            'end_date'   => $end_date
        );

        $res = $this->_post('', $queryStr);

        return $res;
    }

     /**
     * 获取消息分送分时数据 (最大时间跨度 1 天)
     *
     * @param  string $begin_date 获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param  string $end_date   获取数据的结束日期，end_date允许设置的最大值为昨日
     *
     * @return array
     */
    public function getupstreammsghour($begin_date, $end_date)
    {
        $this->apitype = 'datacube';
        $this->module = 'getupstreammsghour';

        $queryStr = array(
            'begin_date' => $begin_date,
            'end_date'   => $end_date
        );

        $res = $this->_post('', $queryStr);

        return $res;
    }

    /**
     * 获取消息发送周数据 (最大时间跨度 30 天)
     *
     * @param  string $begin_date 获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param  string $end_date   获取数据的结束日期，end_date允许设置的最大值为昨日
     *
     * @return array
     */
    public function getupstreammsgweek($begin_date, $end_date)
    {
        $this->apitype = 'datacube';
        $this->module = 'getupstreammsgweek';

        $queryStr = array(
            'begin_date' => $begin_date,
            'end_date'   => $end_date
        );

        $res = $this->_post('', $queryStr);

        return $res;
    }

    /**
     * 获取消息发送月数据 (最大时间跨度 30 天)
     *
     * @param  string $begin_date 获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param  string $end_date   获取数据的结束日期，end_date允许设置的最大值为昨日
     *
     * @return array
     */
    public function getupstreammsgmonth($begin_date, $end_date)
    {
        $this->apitype = 'datacube';
        $this->module = 'getupstreammsgmonth';

        $queryStr = array(
            'begin_date' => $begin_date,
            'end_date'   => $end_date
        );

        $res = $this->_post('', $queryStr);

        return $res;
    }

    /**
     * 获取消息发送分布数据 (最大时间跨度 15 天)
     *
     * @param  string $begin_date 获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param  string $end_date   获取数据的结束日期，end_date允许设置的最大值为昨日
     *
     * @return array
     */
    public function getupstreammsgdist($begin_date, $end_date)
    {
        $this->apitype = 'datacube';
        $this->module = 'getupstreammsgdist';

        $queryStr = array(
            'begin_date' => $begin_date,
            'end_date'   => $end_date
        );

        $res = $this->_post('', $queryStr);

        return $res;
    }

    /**
     * 获取消息发送分布周数据 (最大时间跨度 30 天)
     *
     * @param  string $begin_date 获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param  string $end_date   获取数据的结束日期，end_date允许设置的最大值为昨日
     *
     * @return array
     */
    public function getupstreammsgdistweek($begin_date, $end_date)
    {
        $this->apitype = 'datacube';
        $this->module = 'getupstreammsgdistweek';

        $queryStr = array(
            'begin_date' => $begin_date,
            'end_date'   => $end_date
        );

        $res = $this->_post('', $queryStr);

        return $res;
    }

    /**
     * 获取消息发送分布月数据 (最大时间跨度 30 天)
     *
     * @param  string $begin_date 获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param  string $end_date   获取数据的结束日期，end_date允许设置的最大值为昨日
     *
     * @return array
     */
    public function getupstreammsgdistmonth($begin_date, $end_date)
    {
        $this->apitype = 'datacube';
        $this->module = 'getupstreammsgdistmonth';

        $queryStr = array(
            'begin_date' => $begin_date,
            'end_date'   => $end_date
        );

        $res = $this->_post('', $queryStr);

        return $res;
    }

/**
            接口分析数据接口
 */

    /**
     * 获取接口分析数据 (最大时间跨度 30 天)
     *
     * @param  string $begin_date 获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param  string $end_date   获取数据的结束日期，end_date允许设置的最大值为昨日
     *
     * @return array
     */
    public function getinterfacesummary($begin_date, $end_date)
    {
        $this->apitype = 'datacube';
        $this->module = 'getinterfacesummary';

        $queryStr = array(
            'begin_date' => $begin_date,
            'end_date'   => $end_date
        );

        $res = $this->_post('', $queryStr);

        return $res;
    }

    /**
     * 获取接口分析分时数据 (最大时间跨度 1 天)
     *
     * @param  string $begin_date 获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错
     * @param  string $end_date   获取数据的结束日期，end_date允许设置的最大值为昨日
     *
     * @return array
     */
    public function getinterfacesummaryhour($begin_date, $end_date)
    {
        $this->apitype = 'datacube';
        $this->module = 'getinterfacesummaryhour';

        $queryStr = array(
            'begin_date' => $begin_date,
            'end_date'   => $end_date
        );

        $res = $this->_post('', $queryStr);

        return $res;
    }
}
