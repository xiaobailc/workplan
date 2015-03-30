<?php

class MenuController extends XAdminBase
{
	public function actionIndex(){
		$pid = isset($_GET['pid'])?intval($_GET['pid']):0;
		$title = isset($_GET['title'])?trim($_GET['title']):null;
		$up_title = array();
		$data=null;
		
		if($pid){
			$data = Menu::model()->find('id='.$pid);
		}
		
		$criteria =new CDbCriteria;
		$criteria->addCondition('pid='.$pid);
		$criteria->order='sort asc,id asc';
		if($title){
			$criteria->addSearchCondition('title', $title);
		}
		$list = Menu::model()->findAll($criteria);
		$this->render('index',array('data'=>$data,'models'=>$list,'up_title'=>$up_title,'title'=>$title,'pid'=>$pid));
	}
	
	public function actionCreate($pid=0){
		$info = new Menu();
		if(isset($_POST['Menu'])){
			$info->attributes = $_POST['Menu'];
			if($info->save()){
				$this->redirect(array('index','pid'=>$pid));
			}else{
// 				user()->setFlash('menu_edit_result', '新建失败');
				$this->error("菜单添加失败！");
			}
		}
		$all_menus = Menu::model()->findAll();
		foreach($all_menus as $menu){
			$menus[] = $menu->attributes;
		}
		
		$tree = new XTree();
		$menus = $tree->toFormatTree($menus);
		$menus = array_merge(array(0=>array('id'=>0,'title_show'=>'顶级菜单')), $menus);
		$this->render('edit',array('model'=>$info,'menus'=>$menus,'pid'=>$pid));
	}
	
	public function actionEdit($id=0){
		if(empty($id) || !$info = Menu::model()->findByPk($id)){
			$this->error("ID=$id 的数据不存在");
		}
		if(isset($_POST['Menu'])){
			$info->attributes = $_POST['Menu'];
			if($info->save()){
				$this->redirect(array('index','pid'=>$info->pid));
			}else{
			}
		}
		$all_menus = Menu::model()->findAll();
		foreach($all_menus as $menu){
			$menus[] = $menu->attributes;
		}
		
		$tree = new XTree();
		$menus = $tree->toFormatTree($menus);
		$menus = array_merge(array(0=>array('id'=>0,'title_show'=>'顶级菜单')), $menus);
		$this->render('edit',array('model'=>$info,'menus'=>$menus,'pid'=>$info->pid));
	}
	
	public function actionDel($id=0,$pid=0){
		if(empty($id)){
			$this->error('请选择要操作的数据!');
		}
		$count = Menu::model()->deleteByPk($id);
		if($count>0){
			$this->redirect(array('index','pid'=>$pid));
		}
		else{
			$this->error('内容不存在或删除失败！');
		}
	}
}