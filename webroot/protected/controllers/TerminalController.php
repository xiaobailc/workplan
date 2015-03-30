<?php

/**
 * 控制器-终端
 * 
 * @author		liu.chang <liu.chang@icntv.tv>
 * @copyright	Copyright (c) 2007-2013 icntv. All rights reserved.
 * @link		http://www.icntv.tv
 * @package		WCPP.admin.Controller
 * @license		http://www.icntv.tv/license
 * @version		v1.0.0
 */

class TerminalController extends XAdminBase
{
	protected $tvid;
	
	public function actionIndex ($group='')
	{
		parent::_acl();
		$model = new Terminal();
		$criteria = new CDbCriteria();
		!empty($group) && $criteria->addCondition("`group` = '".$group."'");
		$criteria->order = 'create_time DESC';
		$count = $model->count($criteria);
		$pages = new CPagination($count);
		$pages->pageSize = 15;
		$criteria->limit = $pages->pageSize;
		$criteria->offset = $pages->currentPage * $pages->pageSize;
		$result = $model->findAll($criteria);
		
		$group = $model->findAll(array('group'=>'`group`','select'=>'`group`'));
		
		$this->render('terminal_index', array ('datalist' => $result , 'pagebar' => $pages , 'grouplist' => $group));
	}

	public function actionCreate ()
	{
		parent::_acl();
		$model = new Terminal();
		if (isset($_POST['Terminal'])) {
			$model->attributes = $_POST['Terminal'];
			$file = XUpload::upload( $_FILES['headface_url'], array( 'thumb'=>false, 'thumbSize'=>array ( 100 , 100 ) ) );
			if (is_array($file)) {
				$model->headface_url = $file['pathname'];
			}
			if ($model->save()) {
				$path_webroot = getcwd();
				$path_dis = $path_webroot.'/partials/'.$model->partial;
				if(!is_dir($path_dis)){
					if(mkdir($path_dis)){
						file_put_contents($path_dis.'/data.js', '');
					}else{
						XUtils::message('error','版本目录创建失败，可能是权限不够，请手动创建！',$this->createUrl('index'),6);
					}
				}
				parent::_adminLogger(array ('catalog' => 'create' , 'intro' => '添加版本:' . $model->name ));
				$this->redirect(array ('index' ));
			}
		}
		$this->render('terminal_create', array ('model' => $model ));
	}

	public function actionUpdate ($id)
	{
		parent::_acl();
		$model = parent::_dataLoad(new Terminal(), $id);
		if (isset($_POST['Terminal'])) {
			$partial_old = $model->partial;
			$model->attributes = $_POST['Terminal'];
			$file = XUpload::upload( $_FILES['headface_url'], array( 'thumb'=>false, 'thumbSize'=>array ( 100 , 100 ) ) );
			if (is_array($file)) {
				$model->headface_url = $file['pathname'];
			}
			if ($model->save()) {
				if($model->partial !== $partial_old){
					$path_webroot = getcwd();
					$path_old = $path_webroot.'/partials/'.$partial_old;
					$path_dis = $path_webroot.'/partials/'.$model->partial;
					if(is_dir($path_old)){
						if(rename($path_old, $path_dis)){
						}else{
							XUtils::message('error','版本目录重命名失败，可能是权限不够，请手动创建！',$this->createUrl('index'),6);
						}
					}else{
						if(mkdir($path_dis)){
							file_put_contents($path_dis.'/data.js', '');
						}else{
							XUtils::message('error','版本目录创建失败，可能是权限不够，请手动创建！',$this->createUrl('index'),6);
						}
					}
				}
				parent::_adminLogger(array ('catalog' => 'update' , 'intro' => '更新版本:' . $model->name )); 
				$this->redirect(array ('index' ));
			}
		}
		$this->render('terminal_create', array ('model' => $model ));
	}

	/**
	 * 批量操作
	 *
	 */
	public function actionBatch ()
	{	
		if (XUtils::method() == 'GET') {
			$command = trim($_GET['command']);
			$ids = intval($_GET['id']);
		} elseif (XUtils::method() == 'POST') {
			$command = trim($_POST['command']);
			$ids = $this->_gets->getPost('id');
			is_array($ids) && $ids = implode(',', $ids);
		} else {
			XUtils::message('errorBack', '只支持POST,GET数据');
		}
		empty($ids) && XUtils::message('error', '未选择记录');
		switch ($command) {
			
			case 'delete':
				parent::_acl();
				parent::_adminLogger(array ('catalog' => 'delete' , 'intro' => '删除版本,ID:' . $ids ));
				parent::_delete(new Terminal(), $ids, array ('index' ));
				break;
			case 'menuDelete':
				parent::_adminLogger(array ('catalog' => 'delete' , 'intro' => '删除版本菜单,ID:' . $ids ));
				parent::_delete(new WpMenu(), $ids, array ('menu' ));
				break;
			default:
				throw new CHttpException(404, '错误的操作类型:' . $command);
				break;
		}
	
	}
	
	public function actionApiIndex ($tid)
	{
		parent::_acl(); 
		$model = new MemberpublicApi();
		$criteria = new CDbCriteria();
		$criteria->condition = 'pid = '.$tid;
		$criteria->order = 'create_time DESC';
		$count = $model->count($criteria);
		$pages = new CPagination($count);
		$pages->pageSize = 13;
		$criteria->limit = $pages->pageSize;
		$criteria->offset = $pages->currentPage * $pages->pageSize;
		$result = $model->findAll($criteria);
		$this->render('api_index', array ('datalist' => $result , 'tid'=>$tid, 'pagebar' => $pages ));
	}
	/**
	 * 接口添加
	 *
	 */
	public function actionApiCreate ($tid)
	{
		parent::_acl(); 
		$model = new MemberpublicApi('create');
		if (isset($_POST['MemberpublicApi'])) {
			$model->attributes = $_POST['MemberpublicApi'];
			$model->create_time=time();
			$model->pid = $tid;
			$id = $model->save();
			if ($id) {
				parent::_adminLogger(array ('catalog' => 'create' , 'intro' => '添加接口:' . $model->name ));
				$this->redirect(array ('apiindex','tid'=>$tid ));
			}
		}
		$this->render('api_create', array ('model' => $model,'tid'=>$tid));
	}

	/**
	 * 接口编辑
	 *
	 * @param  $id
	 */
	public function actionApiUpdate ($id)
	{
		parent::_acl(); 
		$model = parent::_dataLoad(new MemberpublicApi(), $id);
		
		if (isset($_POST['MemberpublicApi'])) {
			$model->attributes = $_POST['MemberpublicApi'];
			$model->update_time = time();
			if ($model->save()) {

				parent::_adminLogger(array ('catalog' => 'update' , 'intro' => '更新接口:' . $model->name )); 
				$this->redirect(array ('apiindex&pid='.$model->pid ));
			}
		}
		$this->render('api_update', array ('model' => $model ));
	}
	
	/**
	 * 批量操作
	 *
	 */
	public function actionApiBatch ($tid)
	{
		if (XUtils::method() == 'GET') {
			$command = trim($_GET['command']);
			$ids = intval($_GET['id']);
		} elseif (XUtils::method() == 'POST') {
			$command = trim($_POST['command']);
			$ids = $this->_gets->getPost('id');
			is_array($ids) && $ids = implode(',', $ids);
		} else {
			XUtils::message('errorBack', '只支持POST,GET数据');

		}
		empty($ids) && XUtils::message('error', '未选择记录');
		switch ($command) {
			
			case 'delete':
				parent::_acl('memberpublicapi_delete');
				parent::_adminLogger(array ('catalog' => 'delete' , 'intro' => '删除接口,ID:' . $ids ));
				parent::_delete(new MemberpublicApi(), $ids, array ('apiindex&pid='.$tid ));
				break;
			default:
				throw new CHttpException(404, '错误的操作类型:' . $command);
				break;
		}   
	}
	
	/**
	 * 定义菜单
	 */
	public function actionMenu($tid)
	{
		$terminal = self::checktid($tid);
		//保存
		if ( Yii::app()->request->isPostRequest && isset($_POST['tid'])) {
			$save = true;
			if(is_array($_POST['TvMenu'])){
				foreach ($_POST['TvMenu'] as $key=>$attr){
					$tm = TvMenu::model()->findByPk($key);
					$tm->attributes = $attr;
					$result = $tm->save();
					if(!$result) $save=false;
				}
			}
			if(is_array($_POST['NewMenu'])){
				foreach($_POST['NewMenu'] as $attr){
					$attr['tvid'] = intval($_POST['tid']);
					$tm= new TvMenu();
					$tm->attributes = $attr;
					$result = $tm->save();
					if(!$result) $save=false;
				}
			}
			if($save){
				$this->redirect(array('menu','tid'=>$terminal->id));
			}else{
				XUtils::message('errorBack', '保存菜单失败!');
			}
		}
	
		//获取
		$all_menu = TvMenu::model()->findAll("tvid=".$terminal->id);
		$this->render('menu', array('models'=>$all_menu,'tid'=>$terminal->id));
	
	}
	
	/**
	 * 模板列表页
	 * @param int $tid 终端版本ID
	 */
	public function actionTplIndex($tid)
	{
		//$terminal = self::checktid($tid);
		$tempalte = TvTpl::model()->findAll('tvid='. $tid .' order by create_time desc');
		//var_dump($tempalte);
		$this->render('template_index',array('models'=>$tempalte,'tid'=>$tid));
	}
	
	public function actionTplCreate($tid)
	{
		//$terminal = self::checktid($tid);
		$model = new TvTpl();
		if (isset($_POST['TvTpl'])) {
			$model->attributes = $_POST['TvTpl'];
			if ($model->save()) {
				parent::_adminLogger(array ('catalog' => 'create' , 'intro' => '添加模板:' . $model->name.',来自终端:'.$model->tid ));
				$this->redirect(array ('tplindex','tid'=>$tid));
			}
		}
		$this->render('template_create', array ('model' => $model,'tid'=>$tid ));
	}
	
	/**
	 * EPG 可视化编辑页
	 * @param int $id 终端模板ID
	 */
	public function actionEpg($id)
	{
		$tpl = TvTpl::model()->find('id='.$id);
		if(!$tpl){
			XUtils::message('errorBack','模板获取失败！');
		}
		if(!in_array($tpl->name,array('panel'))){
			XUtils::message('errorBack','没有对应的模板编辑器！');
		}
		$terminal = self::checktid($tpl->tvid);
		$nav = TvMenu::model()->findAll('tvid='. $tpl->tvid .' order by sort desc');
		$this->render('tpl_'.$tpl->name, array('terminal'=>$terminal, 'nav'=>$nav,'template'=>$tpl));
	}
	
	/**
	 * 模板数据处理
	 */
	public function actionDealdata()
	{
		$type =  Yii::app()->request->getParam('type');
		$screen =  Yii::app()->request->getParam('screen');
		$panel =  Yii::app()->request->getParam('panel');
		$index =  Yii::app()->request->getParam('index');
		$id =  Yii::app()->request->getParam('id');
		$data =  Yii::app()->request->getParam('data');
		$post_data = $_POST;
		//print_r($post_data);
		switch ($type){
			case 'add_panel_option':
				if(!$data || !$id || !$panel){
					parent::error('params error',0,'',true);
				}
				$data = CJSON::decode($data,true);
				$model = TvTpl::model()->find("id=".$id);
				$m_data = CJSON::decode($model->data,true);
				foreach ($data as $key=>$value){
					$m_data[$panel][$key] = $value;
				}
				$model->data = CJSON::encode($m_data);
				$model->status = 'T';
				if(!$model->save()){
					parent::error('fail to save data',0,'',true);
				}
				parent::success('success','',true);
				break;
			case 'update_panel_index':
				if(!$data || !$id || !$panel || !$index){
					parent::error('params error',0,'',true);
				}
				$data = CJSON::decode($data,true);
				$model = TvTpl::model()->find("id=".$id);
				$m_data = CJSON::decode($model->data,true);
				if(!in_array($index,$m_data[$panel]['__sequence'])){
					$m_data[$panel]['__sequence'][] = $index;
				}
				$m_data[$panel][$index] = $data;
				$model->data = CJSON::encode($m_data);
				$model->status = 'T';
				if(!$model->save()){
					parent::error('fail to save data',0,'',true);
				}
				parent::success('success','',true);
				break;
			case 'update_panel_all':
				if(!$data || !$id || !$panel){
					parent::error('params error',0,'',true);
				}
				$datas = CJSON::decode($data,true);
				$model = TvTpl::model()->find("id=".$id);
				$m_data = CJSON::decode($model->data,true);
				foreach($datas as $index=>$data){
					if(!in_array($index,$m_data[$panel]['__sequence'])){
						$m_data[$panel]['__sequence'][] = $index;
					}
					$m_data[$panel][$index] = $data;
				}
				$model->data = CJSON::encode($m_data);
				$model->status = 'T';
				if(!$model->save()){
					parent::error('fail to save data',0,'',true);
				}
				parent::success('success','',true);
				break;
			case 'get_tpl_data':
				$model = TvTpl::model()->find("id=".$id);
				if(!$model){
					parent::error('fail to get data');
				}
				header('Content-Type:application/json; charset=utf-8');
				echo $model->data;
				break;
			case 'get_tpl_history':
				$criteria = new CDbCriteria();
				$criteria->condition = "status_is='Y'";
				$criteria->addCondition('tplid='.$id);
				$criteria->order = 'create_time DESC';
				$models = TvTplHistory::model()->findAll($criteria);
				$result = array();
				if(is_array($models)){
					foreach($models as $key=>$row){
						$result[$key] = $row->attributes;
						$result[$key][data_length] = strlen($row->data);
						$result[$key][create_time] = date('Y-m-d H:i:s',$row->create_time);
						unset($result[$key][data]);
						unset($result[$key][id]);
						unset($result[$key][status_is]);
					}
				}
				header('Content-Type:application/json; charset=utf-8');
				echo CJSON::encode($result);
				//echo $result;
				break;
			default:
				parent::error('params error',0,'',true);
				break;
		}
		exit;
	}
	
	/**
	 * 模板审核----直接通过审核（后期整体迁移到审核系统）
	 * @param int $id 模板ID
	 */
	public function actionAudit($id)
	{
		$tpl = TvTpl::model()->findByPk($id);
		if(!$tpl) XUtils::message('errorBack', '模板获取失败!');
		$tpl->status = 'P';
		$tpl->update();
		//审核通过写入版本库
		$res = TvTplHistory::model()->find('tplid='.$id.' order by create_time desc');
		$version = isset($res->version) ? $res->version : 0;
		$tplhistory = new TvTplHistory();
		$tplhistory->data = $tpl->data;
		$tplhistory->version = $version+1;
		$tplhistory->tplid = $tpl->id;
		$tplhistory->save();
		$this->redirect(array('tplindex','tid'=>$tpl->tvid));
	}
	
	/**
	 * 图片上传
	 */
	public function actionUpload()
	{
		$terminal = isset($_POST['terminal'])?$_POST['terminal']:'default';
		$screen = isset($_POST['screen'])?$_POST['screen']:'1080';
		$page = isset($_POST['page'])?$_POST['page']:'panel';
		$files = XUpload::upload($_FILES['image'],array('thumb'=>false,'saveRule'=>array('rule'=>'user','userPath'=>$terminal.'/'.$screen.'/'.$page,)));
		if(!is_array($files)){
			$files = array('error'=>$files);
		}
		header('Content-Type:application/json; charset=utf-8');
		echo CJSON::encode($files);
		exit;
	}
	
	/**
	 * 检查终端ID是否存在
	 * @param int $tid
	 * @return mixed $result
	 */
	protected function checktid($tid)
	{
		$result = Terminal::model()->findByPk($tid);
		if($result) return $result;
		XUtils::message('errorBack', '版本获取失败!');
	}
	

}