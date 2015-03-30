<?php
/**
 * 静态参数
 * 
 * @author        Shiliang <guan.shiliang@gmail.com>
 * @copyright     Copyright (c) 2007-2013 icntv. All rights reserved.
 * @link          http://www.icntv.tv
 * @package       iCC.Tools
 * @license       http://www.icntv.tv/license
 * @version       v1.0.0
 */

class XParams{
	static $adminLoggerType = array('login'=>'登录','create'=>'添加','delete'=>'删除','update'=>'编辑');
	static $attrScope = array('post'=>'内容','config'=>'系统配置','page'=>'单页');
	static $attrItemType = array('input'=>'文本输入','select'=>'下拉选择','checkbox'=>'多选','textarea'=>'大段内容','radio'=>'单选');
	/**
	 * 取参数值
	 */
	static public function get($val, $type){
		switch ($type) {
			case 'adminLoggerType': return self::$adminLoggerType[$val]; break;
			default: break;
		}
	}
}
