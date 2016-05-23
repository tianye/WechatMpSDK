<?php
namespace Wechat\API;

use Closure;

/**
 * 微信菜单相关接口.
 *
 * @author Tian.
 */
class MenuApi extends BaseApi
{

    /**
     * 设置菜单
     *
     * @return bool
     */
    public function set($menus)
    {
        if ($menus instanceof Closure) {
            $menus = $menus($this);
        }

        if (!is_array($menus)) {
            $this->setError('子菜单必须是数组或者匿名函数返回数组');

            return false;
        }

        $menus = $this->extractMenus($menus);

        $data = ['button' => $menus];

        $res = $this->_post('create', $data);

        return $res;
    }

    /**
     * 获取菜单
     *
     * @return array
     */
    public function get()
    {
        $queryStr = [];

        $res = $this->_get('get', $queryStr);

        return $res;
    }

    /**
     * 获取菜单【查询接口，能获取到任意方式设置的菜单】
     *
     * @return array
     */
    public function current()
    {
        $queryStr = [];

        $this->module = 'get_current_selfmenu_info';

        $res = $this->_get('', $queryStr);

        return $res;
    }

    /**
     * 删除菜单
     *
     * @return array
     */
    public function delete()
    {
        $queryStr = [];

        $res = $this->_get('delete', $queryStr);

        return $res;
    }

    /**
     * 转menu为数组
     *
     * @param array $menus
     *
     * @return array
     */
    protected function extractMenus(array $menus)
    {
        foreach ($menus as $key => $menu) {
            $menus[$key] = $menu->toArray();

            if ($menu->sub_button) {
                $menus[$key]['sub_button'] = $this->extractMenus($menu->sub_button);
            }
        }

        return $menus;
    }
}
