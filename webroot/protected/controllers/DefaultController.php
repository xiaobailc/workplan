<?php
/**
 * 系统首页
 * 
 * @author		Shiliang <guan.shiliang@gmail.com>
 * @copyright	 Copyright (c) 2007-2013 icntv. All rights reserved.
 * @link		  http://www.icntv.tv
 * @package	   iCC.admin.Controller
 * @license	   http://www.icntv.tv/license
 * @version	   v1.0.0
 */

class DefaultController extends XAdminBase
{
	/**
	 * 首页
	 */
	public function actionIndex ()
	{
		$menu = parent::_getMenus();
		//var_dump($menu);
		$this->render('index', array('menu' => $menu));
	}
	
	/**
	 * 终端版本管理首页
	 */
	public function actionTv ($tid)
	{
		$model = Terminal::model()->find("status_is='Y' and id=".$tid);
		$model || XUtils::message('errorBack', "ID=".$tid." 的终端版本不存在");
		$menu = parent::_getMenus(array(32));
		$this->render('tv',array('menu' => $menu , 'model'=>$model));
	}
	
	/**
	 * 主界面
	 */
	public function actionHome ()
	{
		$data['soft'] = 'icc';
		$data['softVersion'] = $this->_icc;
		$data['softRelease'] = $this->_iccRelease;
		$data['serverSoft'] = $_SERVER['SERVER_SOFTWARE'];
		$data['serverOs'] = PHP_OS;
		$data['phpVersion'] = PHP_VERSION;
		$data['fileupload'] = ini_get('file_uploads') ? ini_get('upload_max_filesize') : '禁止上传';
		$data['serverUri'] = $_SERVER['SERVER_NAME'];
		$data['maxExcuteTime'] = ini_get('max_execution_time') . ' 秒';
		$data['maxExcuteMemory'] = ini_get('memory_limit');
		$data['magic_quote_gpc'] = ini_get('magic_quotes_gpc') ? '开启' : '关闭';
		$data['allow_url_fopen'] = ini_get('allow_url_fopen') ? '开启' : '关闭';
		$data['excuteUseMemory'] = function_exists('memory_get_usage') ? XUtils::byteFormat(memory_get_usage()) : '未知';
		$dbsize = 0;
		$connection = Yii::app()->db;
		$sql = 'SHOW TABLE STATUS LIKE \'' . $connection->tablePrefix . '%\'';
		$command = $connection->createCommand($sql)->queryAll();
		foreach ($command as $table) 
			$dbsize += $table['Data_length'] + $table['Index_length'];
		$mysqlVersion = $connection->createCommand("SELECT version() AS version")->queryAll();
		$data['mysqlVersion'] = $mysqlVersion[0]['version'];
		$data['dbsize'] = $dbsize ? XUtils::byteFormat($dbsize) : '未知';
		$notebook = Admin::model()->findByPk($this->_adminUserId);
		$env = XUtils::b64encode(serialize($data));
		$this->render('home', array ('notebook' => $notebook ,'env'=>$env, 'server' => $data ));
	}

	/**
	 * 更新备注
	 */
	public function actionNotebookUpdate ()
	{
		try {
			$notebook = $this->_gets->getPost('notebook');
			$adminModel = Admin::model()->findByPk($this->_adminUserId);
			if($adminModel == false)
				throw new Exception('用户不存在');
			$adminModel->notebook = trim($notebook);
			if ($adminModel->save()) {
				$var['state'] = 'success';
				$var['message'] = '更新成功';
			}else {
				throw new Exception('更新失败');
			}
		} catch (Exception $e) {
			$var['state'] = 'error';
			$var['message'] = $e->getMessage();
		}
		exit(CJSON::encode($var));
	}
}