<?php
/**
 * 用户
 * 
 * @author		Shiliang <guan.shiliang@gmail.com>
 * @copyright	 Copyright (c) 2007-2013 icntv. All rights reserved.
 * @link		  http://www.icntv.tv
 * @package	   iCC.admin.Controller
 * @license	   http://www.icntv.tv/license
 * @version	   v1.0.0
 */
class AdminController extends XAdminBase
{
	protected $group_list;
	
	/**
	 * 用户列表
	 *
	 */
	public function actionIndex ()
	{
		parent::_acl();
		$group = array();
		$model = new Admin();
		$criteria = new CDbCriteria();
		$criteria->order = 'id DESC';
		$keyword = $this->_gets->getQuery('keyword');
		if($keyword){
		    $criteria->addSearchCondition('realname', $keyword);
		    $criteria->addSearchCondition('username', $keyword,true,'OR');
		}
		$count = $model->count($criteria);
		$pages = new CPagination($count);
		$pages->pageSize = 20;
		$criteria->limit = $pages->pageSize;
		$criteria->offset = $pages->currentPage * $pages->pageSize;
		$result = $model->findAll($criteria);
		$this->group_list = AdminGroup::model()->findAll();
		$this->render('admin_index', array ('datalist' => $result , 'pagebar' => $pages ));
	}

	/**
	 * 用户添加
	 *
	 */
	public function actionCreate ()
	{
		parent::_acl(); 
		$model = new Admin('create');
		if (isset($_POST['Admin'])) {
			$model->attributes = $_POST['Admin'];
			$model->group_id = is_array($_POST['Admin']['group_id'])?implode(',', $_POST['Admin']['group_id']):(empty($_POST['Admin']['group_id'])?'':$_POST['Admin']['group_id']);
			$id = $model->save();
			if ($id) {
				parent::_adminLogger(array ('catalog' => 'create' , 'intro' => '添加用户:' . $model->username )); 
				$this->redirect(array ('index' ));
			}
		}
		$this->group_list = AdminGroup::model()->findAll();
		$model->create_time = date('Y-m-d');
		$model->last_login_time = date('Y-m-d');
		$this->render('admin_create', array ('model' => $model ));
	}

	/**
	 * 用户编辑
	 *
	 * @param  $id
	 */
	public function actionUpdate ($id)
	{
		parent::_acl(); 
		$model = parent::_dataLoad(new Admin(), $id);
		
		if (isset($_POST['Admin'])) {
			$password = $_POST['Admin']['password'];
			if (empty($password)) 
				$_POST['Admin']['password'] = $model->password;
			else 
				$_POST['Admin']['password'] = md5($password);
			
			$model->attributes = $_POST['Admin'];
			$model->group_id = is_array($_POST['Admin']['group_id'])?implode(',', $_POST['Admin']['group_id']):(empty($_POST['Admin']['group_id'])?'':$_POST['Admin']['group_id']);
			if ($model->save()) {
				parent::_adminLogger(array ('catalog' => 'update' , 'intro' => '更新用户资料:' . $model->username )); 
				$this->redirect(array ('index' ));
			}
		}
		$this->group_list = parent::_groupList('admin');
		$this->render('admin_create', array ('model' => $model ));
	
	}

	/**
	 * 用户组
	 *
	 */
	public function actionGroup ()
	{
		parent::_acl(); 
		$model = new AdminGroup();
		$criteria = new CDbCriteria();
		$criteria->order = 't.id DESC';
		$count = $model->count($criteria);
		$pages = new CPagination($count);
		$pages->pageSize = 13;
		$criteria->limit = $pages->pageSize;
		$criteria->offset = $pages->currentPage * $pages->pageSize;
		$result = $model->findAll($criteria);
		$this->render('admin_group', array ('datalist' => $result , 'pagebar' => $pages ));
	}

	/**
	 * 管理组添加
	 *
	 */
	public function actionGroupCreate ()
	{
		parent::_acl(); 
		$model = new AdminGroup();
		if (isset($_POST['AdminGroup'])) {
			sort($_POST['AdminGroup']['rules']);
			$rules  = implode( ',' , array_unique($_POST['rules']));
			$model->attributes = $_POST['AdminGroup'];
			$model->rules = $rules;
			if ($model->save()) {
				parent::_adminLogger(array ('catalog' => 'create' , 'intro' => '添加用户组' . $model->group_name ));
				$this->redirect(array ('group' ));
			}
		}
		$node_list = parent::_getNodes();
		$this->render('group_create', array ('model' => $model, 'node_list' => $node_list ));
	}

	/**
	 * 用户组编辑
	 *
	 * @param  $id
	 */
	public function actionGroupUpdate ($id)
	{
		parent::_acl(); 
		parent::_groupPrivate($id);
		$data = parent::_dataLoad(new AdminGroup(), $id);
		if (isset($_POST['AdminGroup'])) {
			sort($_POST['rules']);
			$rules  = implode( ',' , array_unique($_POST['rules']));
			$data->attributes = $_POST['AdminGroup'];
			$data->rules = $rules;
			if ($data->save()) {
				parent::_adminLogger(array ('catalog' => 'update' , 'intro' => '编辑权限:' . $data->group_name ));
				$this->redirect(array ('group' ));
			}
		}
		$node_list = parent::_getNodes();
		$this->render('group_create', array ('model' => $data , 'node_list' => $node_list));
	}

	/**
	 * 修改密码
	 */
	public function actionOwnerUpdate ()
	{
		$model = parent::_dataLoad(new Admin(), $this->_adminUserId);
		
		if (isset($_POST['Admin'])) {
			$password = $_POST['Admin']['password'];
			if (empty($password))
				$_POST['Admin']['password'] = $model->password;
			 else 
				$_POST['Admin']['password'] = md5($password);
			$model->attributes = $_POST['Admin'];
			$model->password = empty($password) ? $model->password : md5($password);
			if ($model->save()) {
				parent::_adminLogger(array ('catalog' => 'update' , 'intro' => '修改密码:' . $model->username )); //日志
				XUtils::message('success', '修改完成', $this->createUrl('default/home'));
			}
		}
		$this->render('owner_update', array ('model' => $model ));
	
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
			case 'adminDelete':
				parent::_acl('admin_delete');
				parent::_adminLogger(array ('catalog' => 'delete' , 'intro' => '删除用户,ID:' . $ids ));
				parent::_delete(new Admin(), $ids, array ('index' ));
				break;
			case 'adminBan':
				parent::_adminLogger(array('catalog' => 'update' , 'intro' => '禁用用户,ID:' . $ids ));
				parent::_verify(new Admin(), 'unverify', $ids , array('index'));
				break;
			case 'adminAllow':
				parent::_adminLogger(array ('catalog' => 'update' , 'intro' => '启用用户,ID:' . $ids ));
				parent::_verify(new Admin(), 'verify', $ids, array('index'));
				break;
			case 'groupDelete':
				parent::_acl('admin_group_delete');
				parent::_groupPrivate($ids);
				parent::_adminLogger(array ('catalog' => 'delete' , 'intro' => '删除用户组,ID:' . $ids ));
				parent::_delete(new AdminGroup(), $ids, array ('group' ));
				break;
			case 'groupBan':
				parent::_adminLogger(array ('catalog' => 'update' , 'intro' => '禁用用户组,ID:' . $ids ));
				parent::_verify(new AdminGroup(), 'unverify', $ids , array('group'));
				break;
			case 'groupAllow':
				parent::_adminLogger(array ('catalog' => 'update' , 'intro' => '启用用户组,ID:' . $ids ));
				parent::_verify(new AdminGroup(), 'verify', $ids, array('group'));
				break;
			default:
				throw new CHttpException(404, '错误的操作类型:' . $command);
				break;
		}
	
	}
}
