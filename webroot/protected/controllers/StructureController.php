<?php
class StructureController extends XAdminBase
{
    public function actionIndex(){
        $models = Structure::model()->findAll();
        $output = [];
        foreach ($models as $model){
            $array['id'] = $model->user_id;
            $array['name'] = $model->user_name;
            $array['pId'] = $model->leader_id;
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
        $structure = new Structure();
        $structure->user_id = $this->_gets->getPost('user_id');
        $structure->user_name = $this->_gets->getPost('user_name');
        $structure->leader_id = $this->_gets->getPost('leader_id');
        if($structure->insert()){
            $res = ['success'=>true];
        }else{
            parent::error('system error',0,'',true);
        }
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode($res);exit;
    }
}