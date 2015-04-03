<?php
/**
 * 后台管理基础类，后台控制器必须继承此类
 * 
 * @author        Shiliang <guan.shiliang@gmail.com>
 * @copyright     Copyright (c) 2007-2013 icntv. All rights reserved.
 * @link          http://www.icntv.tv
 * @package       WCPP.admin.Controller
 * @license       http://www.icntv.tv/license
 * @version       v1.0.0
 */

class XAdminBase extends Controller
{
    protected $_adminUserId;
    protected $_adminUserName;
    protected $_adminRealName;
    protected $_adminGroupId;
    protected $_adminPermission;
    protected $_catalog;
    public function init ()
    {
        parent::init();
        if (isset($_POST['sessionId'])) {
            $session = Yii::app()->getSession();
            $session->close();
            $session->sessionID = $_POST['sessionId'];
            $session->open();
        }
        $this->_adminUserId = parent::_sessionGet('_adminUserId');
        $this->_adminUserName = parent::_sessionGet('_adminUserName');
        $this->_adminRealName = parent::_sessionGet('_adminRealName');
        $this->_adminGroupId = parent::_sessionGet('_adminGroupId');
        $this->_adminPermission = parent::_sessionGet('_adminPermission');
        
        if (empty($this->_adminUserId))
            $this->redirect(array ('public/login' ));
        //栏目
        //$this->_catalog = Catalog::model()->findAll('status_is=:status',array('status'=>'Y'));
        //系统配置
        //$this->_conf = self::_config();
        define('IS_ROOT',$this->isadminstrator());
    }
    
    protected function beforeAction($action){
    	$name = $this->getId().'/'.$action->id.'?'.Yii::app()->getRequest()->queryString;
    	if(strpos($name, 'default/index')!==false) return true;
    	if(strpos($name, 'admin/ownerUpdate')!==false) return true;
    	if(!$this->_check($name)){
    		self::error("没有权限浏览此页面或执行此操作，请联系管理员开通！",403);
    	}
    	return true;
    }

    /**
     * 配置文件中参数检测
     */
    protected function _configParams($params){
        if(Yii::app()->params[$params['action']] != $params['val'] && $params['response'] =='json'){
            exit(CJSON::encode(array('state'=>'error', 'message'=>$params['message'])));
        }elseif(Yii::app()->params[$params['action']] != $params['val']&& $params['response'] =='text'){
            exit($params['message']);
        }elseif(Yii::app()->params[$params['action']] != $params['val']){
            $tplVar = array(
                'code'=>'访问受限',
                'message'=>$params['message'],
                'redirect'=>$params['redirect'] ? $params['redirect'] : Yii::app()->request->urlReferrer ,
            );
            exit($this->render('/_include/_error', $tplVar));
        }
    }

    /**
     * 实时获取系统配置
     * @return [type] [description]
     */
    private function _config(){
        $model = Config::model()->findAll();
        foreach ($model as $key => $row) 
            $config[$row['variable']] = $row['value'];
        return $config;
    }

    /**
     * 自动获取关键词(调用第三方插件)
     * @return [type] [description]
     */
    public function actionKeyword()
    {
        $string = trim($this->_gets->getParam('string'));
        $return  = XAutoKeyword::discuz($string);
        if($return  == 'empty'){
            $data['state'] = 'error';
            $data['message'] = '未成功获取';
        }else{
            $data['state'] = 'success';
            $data['message'] = '成功获取';
            $data['datas'] = $return;
        }
        exit(CJSON::encode($data)); 
    }

    /**
     * 更新基类
     *
     * @param $model 模块
     * @param $field 字段
     * @param $redirect 跳转
     * @param $tpl 模板
     * @param $pkField 主键id
     */
    protected function _update ($model, $redirect = 'index', $tpl = '', $id = 0, $pkField = 'id', $field = '')
    {
        $modelName = ! $field ? get_class($model) : $field;
        $data = $model->findByPk($id);
        $data === null && XUtils::message('error', '记录不存在');
        if (isset($_POST[$modelName])) {
            $data->attributes = $_POST[$modelName];
            if ($data->save()) {
                self::_adminLogger(array ('catalog' => 'update' , 'intro' => '调用基类更新数据，来自模块：' . $this->id . '，方法：' . $this->action->id )); //日志
                $this->redirect($redirect);
            }
        }
        $this->render($tpl, array ('model' => $data ));
    
    }

    /**
     * 添加基类
     *
     * @param $model 模块
     * @param $field 字段
     * @param $redirect 跳转
     * @param $tpl  模板
     */
    protected function _create ($model, $redirect = 'index', $tpl = '', $field = false)
    {
        $modelName = ! $field ? get_class($model) : $field;
        
        if (isset($_POST[$modelName])) {
            $model->attributes = $_POST[$modelName];
            $id = $model->save();
            if ($id) {
                self::_adminLogger(array ('catalog' => 'update' , 'intro' => '调用基类添加数据，来自模块：' . $this->id . '，方法：' . $this->action->id . ',ID:' . $id )); //日志
                $this->redirect($redirect);
            }
        }
        $this->render($tpl, array ('model' => $model ));
    }

    /**
     * 删除数据及附件
     *
     * @param $model  模型
     * @param $id  要删除的数据id
     * @param $redirect 跳转地址
     * @param $attach 附件字段
     * @param $conditionField 条件id
     */
    protected function _delete ($model = null, $id = '', $redirect = 'index', $attach = null, $pkField = 'id')
    {
        if ($attach) {
            $data = $model->findAll($pkField . ' IN(:id)', array (':id' => $id ));
            foreach ((array) $data as $row) {
                foreach ((array) $attach as $value) {
                    if (! empty($row[$value])) {
                        @unlink($row[$value]);
                    }
                }
            }
        }
        $result = $model->deleteAll(array ('condition' => 'id IN(' . $id . ')' ));
        //刷新缓存
        self::_refreshCache($model);
        $this->redirect($redirect);
    }

    /**
     * 审核基础类
     *
     * @param $model  模型
     * @param $type 审核类型
     * @param $id 要修改的ID
     * @param $redirect 调转地址
     * @param $attach 附件字段
     * @param $pkField
     */
    protected function _verify ($model = null, $type = 'verify', $id = '', $redirect = 'index', $cdField = 'status_is', $pkField = 'id')
    {
        $criteria = new CDbCriteria();
        $criteria->condition = $pkField . ' IN(' . $id . ')';
        $showStatus = $type == 'verify' ? 'Y' : 'N';
        $result = $model->updateAll(array ($cdField => $showStatus ), $criteria);
        //刷新缓存
        self::_refreshCache($model);
        $this->redirect($redirect);
    }

    /**
     * 推荐基础类
     *
     * @param $model
     * @param $type
     * @param $id
     * @param $redirect
     * @param $attach
     * @param $pkField
     */
    protected function _commend ($model = null, $type = 'commend', $id = '', $redirect = 'index', $pkField = 'id')
    {
        $criteria = new CDbCriteria();
        $criteria->condition = $pkField . ' IN(' . $id . ')';
        $commend = $type == 'commend' ? 'Y' : 'N';
        $result = $model->updateAll(array ('commend' => $commend ), $criteria);
        //刷新缓存
        self::_refreshCache($model);
        $this->redirect($redirect);
    }

     /**
     * 刷新内置缓存
     * @param  $model
     */
    protected function _refreshCache ($model)
    {
        if (is_object($model)) {
            $modelx = get_class($model);
        } else {
            $modelx = $model;
        }
        switch (strtolower($modelx)) {
            case 'link':
                XXcache::refresh('_link', 86400);
                break;
            case 'ad':
                XXcache::refresh('_ad', 86400);
                break;
            case 'catalog':
                XXcache::refresh('_catalog', 86400);
                break;
            case 'UserGroup':
                XXcache::refresh('_userGroup', 86400);
                break;
        }
    }

    /**
     * 系统组禁止操作
     * @param $group
     * @throws CHttpException
     */
    protected function _groupPrivate ($groupId = 0, $noAccess = array('1', '2'))
    {
    	return true;
        if(is_array($groupId)){
            foreach ($group as $value) {
               if (in_array($groupId, $noAccess))
                throw new CHttpException(404, '系统组不允许进行此操作');
            }
        }else{
             if (in_array($groupId, $noAccess))
                throw new CHttpException(404, '系统组不允许进行此操作');
        }
    }

    /**
     * 取用户组列表
     * @param $type
     */
    protected function _groupList ($type = 'admin')
    {
        switch ($type) {
            case 'admin':
                return AdminGroup::model()->findAll();
                break;
        }
    }
    /**
     * 权限检测
     * 超级用户组跳过检测
     * 附加 index_index 后台首页,防止重复验证权限
     * @param $action
     */
    
    protected function _acl ($action = false, $params = array('response'=>false, 'append'=>',default_index,default_home'))
    {
    	return true;
        $actionFormat = empty($action) ? strtolower($this->id . '_' . $this->action->id) : strtolower($action);
        $permission = self::_sessionGet('_adminPermission');
        if ($permission != 'adminstrator') {
            $adminGroup = self::_sessionGet('_adminGroupId');
            $aclDb = AdminGroup::model()->findByPk($adminGroup);
            try {
                if (! in_array($actionFormat, explode(',', strtolower($aclDb->acl) . $params['append']))) 
                    throw new Exception('当前角色组无权限进行此操作，请联系用户授权');
            } catch (Exception $e) {
                if($params['response'] == 'text'){
                    exit($e->getMessage()); 
                }elseif($params['response'] == 'json'){
                    $var['state'] = 'error';
                    $var['message'] = $e->getMessage();
                    exit(CJSON::encode($var)); 
                }else{
                    $referrer = Yii::app()->request->urlReferrer? Yii::app()->request->urlReferrer : $this->createUrl('default/home') ;
                    $tplVar = array(
                        'code'=>'访问受限',
                        'message'=>$e->getMessage(),
                        'redirect'=>$params['redirect'] ? $params['redirect'] : $referrer ,
                    );
                    exit($this->render('/_include/_error', $tplVar));
                }
            }
        }
    }
    
	/**
	 * 操作错误跳转的快捷方法
	 * @access protected
	 * @param string $message 错误信息
	 * @param string $jumpUrl 页面跳转地址
	 * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
	 * @return void
	 */
	protected function error($message='',$status=500, $jumpUrl='',$ajax=false) {
		$this->dispatchJump($message,$status,$jumpUrl,$ajax);
	}
	
	/**
	 * 操作成功跳转的快捷方法
	 * @access protected
	 * @param string $message 提示信息
	 * @param string $jumpUrl 页面跳转地址
	 * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
	 * @return void
	 */
	protected function success($message='',$jumpUrl='',$ajax=true) {
		$this->dispatchJump($message,1,$jumpUrl,$ajax);
	}
	
	/**
	 * 默认跳转操作 支持错误导向和正确跳转
	 * @param string $message 提示信息
	 * @param Boolean $status 状态
	 * @param string $jumpUrl 页面跳转地址
	 * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
	 * @access private
	 * @return void
	 */
	private function dispatchJump($message,$status=1,$jumpUrl='',$ajax=false) {
		if(true === $ajax || Yii::app()->request->isAjaxRequest) {// AJAX提交
			$data = array();
			if($status=='1') {
				$data['info'] = $message;
				$data['status'] = $status;
			}else{
				$data['error'] = $message;
				$data['error_code'] = $status;
			}
			!empty($jumpUrl) && $data['url'] = $jumpUrl;
			header('Content-Type:application/json; charset=utf-8');
			exit(CJSON::encode($data));
		}
		else{
			throw new CHttpException($status,$message);
 			//$this->render('/system/error',array('code'=>$status,'message'=>$message));
 			//exit;
		}
	}
	
	/**
	 * 获取菜单数组
	 * @param $type 获取菜单类型 全部子菜单all  当前子菜单 current
	 */
	protected function _getMenus($ids=array() , $type='all'){
		$menus = array();
		$criteria = new CDbCriteria();
		if(count($ids)){
			$criteria->addInCondition('id',$ids);
			$criteria->addCondition('pid=0');
		}else{
			$criteria->addCondition('pid=0');
			$criteria->addCondition('hide=0');
		}
		if(!YII_DEBUG){ // 是否开发者模式
			$criteria->addCondition('is_dev=0');
		}
		$criteria->order = 'sort asc';
		$menus_main = Menu::model()->findAll($criteria);
		foreach($menus_main as $model){
			$menus[] = $model->attributes;
		}
		//高亮主菜单
		$current = Menu::model()->find("pid>0 and url like '%".$this->getId()."/".$this->getAction()->getId()."%'");
		if(!empty($current->attributes)){
			$nav = Menu::model()->getPath($current->id);
		}
		$nav_first_title =  isset($nav[0]['title'])?$nav[0]['title']:'';
		$nav_second_title = isset($nav[1]['title'])?$nav[1]['title']:'';
		foreach ($menus as $key => $main) {
			if (!is_array($main) || empty($main['title']) || empty($main['url']) ) {
				$this->error("控制器基类$menus属性元素配置有误",500);
			}
			// 判断主菜单权限
			if ( !IS_ROOT && !$this->_check($main['url'])) {
				unset($menus[$key]);
				continue;
			}
			// 是否获取全部子菜单
			if($type == 'current' && $main['title'] != $nav_first_title){
				continue;
			}
	
			if($main['title'] == $nav_first_title){
				$menus[$key]['class']='active';
			}
	
			$criteria = new CDbCriteria();
			$criteria->addCondition('pid='.$main['id']);
			$criteria->addCondition('hide=0');
			if(!YII_DEBUG){ // 是否开发者模式
				$criteria->addCondition('is_dev=0');
			}
			$criteria->order = 'sort asc';
			$menus_child = Menu::model()->findAll($criteria);
			$childs = array();
			foreach($menus_child as $model){
				$childs[] = $model->attributes;
			}
	
			// 检测子菜单权限
			foreach ($childs as $k=>$child) {
				if(!IS_ROOT && !$this->_check($child['url'])){
					unset($childs[$k]);
					continue;
				}
				if($child['title'] == $nav_second_title){
					$childs[$k]['class']='active';
				}
			}
			$menus[$key]['child'] = $childs;
		}
		return $menus;
	}
	
	/**
	 * 获取菜单节点
	 * @return Ambigous <>|Ambigous <unknown, multitype:, multitype:array >
	 */
	protected function _getNodes(){
		static $tree_nodes = array();
		if ( !empty($tree_nodes) ) {
			return $tree_nodes;
		}
		$list = Menu::model()->findAll(array('order'=>'sort asc'));
		$list_arr = array();
		if(count($list)>0){
			foreach($list as $model){
				$list_arr[] = $model->attributes;
			}
		}
		$nodes = XTree::list_to_tree($list_arr,$pk='id',$pid='pid',$child='operator',$root=0);
		foreach ($nodes as $key => $value) {
			if(!empty($value['operator'])){
				$nodes[$key]['child'] = $value['operator'];
				unset($nodes[$key]['operator']);
			}
		}
		$tree_nodes = $nodes;
		return $nodes;
	}
	
	/**
	 * 检测用户是否在超级管理员组 group_id=1
	 * @return boolean
	 */
	private function isadminstrator(){
		$group_ids = $this->_adminGroupId;
		return (in_array('1', explode(',', $group_ids)))?true:false;
	}
	
	/**
	 * 获取用户组权限ID  array($rules)
	 * @return array
	 */
	final public function getRules(){
		$rules = array();
		$models = AdminGroup::model()->findAll('id in ('.$this->_adminGroupId.') and status_is=\'Y\'');
		foreach($models as $model){
			$rules = array_merge($rules, explode(',', trim($model->rules, ',')));
		}
		return $rules;
	}
	
	/**
	 * 获取当前用户所有规则ID
	 * @return Ambigous <>|unknown|multitype:
	 */
	final protected function getAuthList(){
		$uid = $this->_adminUserId;
		static $_authList = array();
		if (isset($_authList[$uid])) {
			return $_authList[$uid];
		}
		/*
		if( isset($_SESSION['_AUTH_LIST_'.$uid]) ){
			return $_SESSION['_AUTH_LIST_'.$uid];
		}*/
		
		//读取用户所属用户组权限
		$rule_ids = $this->getRules();
		$rule_ids = array_unique($rule_ids);
		if (empty($rule_ids)) {
			$_authList[$uid] = array();
			return array();
		}
		//获取所有规则
		$criteria = new CDbCriteria();
		$criteria->addInCondition('id', $rule_ids);
		//$criteria->addCondition('hide=0');
		$models = Menu::model()->findAll($criteria);
		$authList = array();
		foreach ($models as $model) {
			$authList[] = strtolower($model->url);
		}
		$_authList[$uid] = $authList;
		//$_SESSION['_AUTH_LIST_'.$uid]=$authList;
		return array_unique($authList);

	}
	
	/**
	 * 判断该控制器下的方法是否有权限  controller/action?key1=value1&key2=value2
	 * @param string $name
	 * @return boolean
	 */
	protected function _check($name=''){
		if(IS_ROOT) return true;
		$debug = false;
		//获取传入的控制器和参数
		$needle = strpos($name, '?');
		if($needle===false){
			$name_path = $name;
			$name_param = array();
		}else{
			$name_path = substr($name,0,$needle);
			parse_str(substr($name, $needle+1),$name_param);
		}
		
		$authList = $this->getAuthList();
		foreach($authList as $auth){
			$needle = strpos($auth, '?');
			if($needle===false){
				$auth_path = $auth;
				$auth_param = array();
			}else{
				$auth_path = substr($auth,0,$needle);
				parse_str(substr($auth, $needle+1),$auth_param);
			}
			if($debug){
				echo "<pre>";
				echo "/*********\n[path]:\n";
				echo "[name]:\t";var_dump($name_path);
				echo "[auth]:\t";var_dump($auth_path);
				echo "/*********\n[parame]:\n";var_dump($name_param);var_dump($auth_param);
				echo "/*********\n[diff]:\n";var_dump(array_diff($auth_param,$name_param));
				echo "</pre>";
				exit;
			}
			$diff = array_diff($auth_param,$name_param);
			if((strpos($name_path,$auth_path)!==false) && !count($diff)){
				return true;
			}
		}
		return false;
	}
}

?>