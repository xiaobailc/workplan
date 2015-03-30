<?php
/**
 * 后台公共AJAX
 *
 * @author        Shiliang <guan.shiliang@gmail.com>
 * @copyright     Copyright (c) 2007-2013 icntv. All rights reserved.
 * @link          http://www.icntv.tv
 * @package       iCC.admin.Controller
 * @license       http://www.icntv.tv/license
 * @version       v1.0.0
 */

class AjaxController extends XAdminBase{

	/**
	 * 属性内容
	 *
	 * @return [type] [description]
	 */
	public function actionAttr2content() {
		$catalog = intval( $this->_gets->getPost( 'catalog' ) );
		$attrModel = Attr::model()->findAll( array( 'condition'=>'scope=:scope AND catalog_id=:catalogId', 'params'=>array( 'scope'=>'post', 'catalogId'=>$catalog ) ) );

		if ( $attrModel ) {
			$var['state'] = 'success';
			$var['message'] = '找到属性';
			$var['text'] = $this->renderPartial( '/_include/attr2content', array( 'attrModel'=>$attrModel ), true );
		}else {
			$var['state'] = 'error';
			$var['message'] = '没有属性';
		}
		exit( CJSON::encode( $var ) );
	}

	/**
	 * 测试邮件发送
	 */
	public function actionMailTest() {

		$data = $this->_gets->getParam( 'Config' );
		try {
			$var['state'] = 'success';
			$var['message'] = '发送成功';
			XMail::send();
			var_dump($data);
		} catch ( Exception $e ) {
			$var['state'] = 'error';
			$var['message'] = '错误：'. $e->getMessage();
		}
		exit( CJSON::encode( $var ) );
	}
}
