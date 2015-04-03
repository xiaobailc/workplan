<?php
class StructureController extends XAdminBase
{
    public function actionIndex(){
        $models = Structure::model()->findAll();
        $output = [];
        foreach ($models as $model){
            $array['id'] = $model->id;
            $array['name'] = $model->user_name;
            $array['pId'] = $model->pid;
            $output[] = $array;
        }
        $output_str = json_encode($output);
        $c = new CDbCriteria();
        $c->addCondition("realname<>''");
        $c->order = "email asc";
        $users = Admin::model()->findAll($c);
        
        $tree = new XTree();
        $menustructure = $tree->toFormatTree($output,'name','id','pId');
        $menustructure = array_merge(array(0=>array('id'=>0,'title_show'=>'一级领导')), $menustructure);
        $this->render('index',array('zNodes'=>$output_str,'users'=>$users,'menus'=>$menustructure));
    }
    
    public function actionEdit(){
        $type = $this->_gets->getPost('type');
        if($type=='new'){
            $structure = new Structure();
            $structure->user_id = $this->_gets->getPost('user_id');
            $structure->user_name = $this->_gets->getPost('user_name');
            $structure->pid = $this->_gets->getPost('pid');
            if($structure->pid==0){
                $structure->deep = 1;
            }else{
                $res = Structure::model()->find('id='.$structure->pid);
                $structure->deep = $res->deep+1;
            }
            if($structure->insert()){
                $res = ['success'=>true];
            }else{
                parent::error('insert error',0,'',true);
            }
        }elseif($type=='edit'){
            $id = $this->_gets->getPost('id');
            $structure = Structure::model()->find('id='.$id);
            $structure->pid = $this->_gets->getPost('pid');
            if($structure->update()){
                $res = ['success'=>true];
            }else{
                parent::error('update error',0,'',true);
            }
        }else{
            parent::error('system error',0,'',true);
        }
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode($res);exit;
    }
}