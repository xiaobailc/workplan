<?php
/**
 * SESSION工具
 * 
 * @author        Shiliang <guan.shiliang@gmail.com>
 * @copyright     Copyright (c) 2007-2013 icntv. All rights reserved.
 * @link          http://www.icntv.tv
 * @package       iCC.Tools
 * @license       http://www.icntv.tv/license
 * @version       v1.0.0
 */

class XSession
{
    private $_obj;
    public function __construct ()
    {
        if ($this->_obj == null)
            $this->_obj = new CHttpSession();
    }

    /**
     * 设置session
     * @param data 数据,可以是数组
     */
    public function set ($name, $value = '', $expire = 0, $path = '')
    {
        //$session = new CHttpSession();
        $this->_obj->open();
        $expire && $this->_obj->expire = $expire;
        $expire && $this->_obj->path = $path;
        $this->_obj[$name] = $value;
    }

    /**
     * 获取session
     */
    public function get ($name, $once = false)
    {
        //$session = new CHttpSession();
        $this->_obj->open();
        $data = $this->_obj[$name];
        if ($once)
            $this->_obj->remove($name);
        return $data;
    }

    /**
     * 清除session
     * @param  $name
     */
    public function clear ($name)
    {
        $this->_obj->remove($name);
    }
}

?>