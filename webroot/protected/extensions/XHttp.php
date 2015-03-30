<?php
/**
 * Http工具类
 * 
 * @author        Shiliang <guan.shiliang@gmail.com>
 * @copyright     Copyright (c) 2007-2013 icntv. All rights reserved.
 * @link          http://www.icntv.tv
 * @package       iCC.Tools
 * @license       http://www.icntv.tv/license
 * @version       v1.0.0
 */
class XHttp{

	/**
	 * 文件下载
	 */
	static function download ($filename, $showname='', $content='',$expire=180){
		Yii::import( 'application.vendors.*' );
        require_once 'Tp/Http.class.php';
        Http::download($filename, $showname, $content,$expire);
	}
}


