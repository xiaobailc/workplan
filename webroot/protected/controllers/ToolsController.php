<?php
/**
 * 小工具
 * 
 * @author        Shiliang <guan.shiliang@gmail.com>
 * @copyright     Copyright (c) 2007-2013 icntv. All rights reserved.
 * @link          http://www.icntv.tv
 * @package       iCC.admin.Controller
 * @license       http://www.icntv.tv/license
 * @version       v1.0.0
 */
class ToolsController extends XAdminBase{

	/**
	 * 缓存管理
	 */
	public function actionCache() {
		parent::_acl();
		$this->render( 'cache', $data );
	}

	/**
	 * 缓存更新
	 */
	public function actionCacheUpdate() {
		$scope = $this->_gets->getParam( 'scope' );
		try {
			if ( is_array( $scope ) ) {
				foreach ( $scope as $key=>$row ) {
					XXcache::refresh( $row, 3600 );
				}
				$var['state'] = 'success';
				$var['message'] = '操作完成';
			}else
				throw new Exception( '请选择要更新的内容' );
		} catch ( Exception $e ) {
			$var['state'] = 'error';
			$var['message'] = '操作失败：'.$e->getMessage();
		}
		exit( CJSON::encode( $var ) );
	}
}
