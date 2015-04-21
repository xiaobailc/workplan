<?php
class WorkController extends XAdminBase
{
    public function actionDaily(){
        $lowerdaily = false;
        $id = $this->_gets->getQuery('id',false);
        $auth = $this->_gets->getQuery('auth',false);
        if($id && $id!=$this->_adminUserId){
            if($auth != md5($id.$this->_adminUserName.'icntv')){
                $this->error("没有权限查看日报");
            }else{
                $lowerdaily = true;
            }
        }else{
            $id = intval($this->_adminUserId);
        }
        $user = Admin::model()->findByPk($id);
        $user_info = $user->attributes;
        $daily = new Daily();
        $criteria = new CDbCriteria();
        $criteria->addCondition('user_id='.$id);
        $keyword = $this->_gets->getQuery('keyword');
        if($keyword){
            $criteria->addSearchCondition('report_info', $keyword);
        }
        $criteria->order='date_time desc';
        $count = $daily->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = 20;
        $criteria->limit = $pages->pageSize;
        $criteria->offset = $pages->currentPage * $pages->pageSize;
        //print_r($models);
        $models = $daily->findAll($criteria);
        $this->render('daily',array('userinfo'=>$user_info,'models'=>$models,'keyword'=>$keyword,'pagebar' => $pages,'lowerdaily'=>$lowerdaily));
    }
    
    public function actionDailyCreate($date_time=''){
        if(isset($_POST['Daily']) || isset($_POST['NewDaily'])){
            $info = new Daily();
            $info->date_time = $_POST['time_date'];
            $info->user_id = $this->_adminUserId;
            $info->user_name = $this->_adminUserName;
            $info->create_time = time();
            $report = is_array($_POST['Daily'])?$_POST['Daily']:[];
            $report_new = is_array($_POST['NewDaily'])?$_POST['NewDaily']:[];
            $report_info = array_merge($report, $report_new);
            $info->report_info = json_encode($report_info);
            if($info->save()){
                $this->redirect(array('daily'));
            }else{
                $this->error(json_encode($info->errors));
            }
        }
        if(empty($date_time) || !strtotime($date_time)){
            $date_time = date('Y-m-d');
        }
        $info_exist = Daily::model()->find('date_time=\''.$date_time.'\' and user_id='.$this->_adminUserId);
        $daily_exist = ($info_exist->status==1) ? true : false;
        if(!$info_exist){
            $this->render('daily_edit',array('date_time'=>$date_time));
        }else if(!$daily_exist) {
            $this->redirect(array('dailyedit','id'=>$info_exist->id));
        }else {
            $this->redirect(array('dailyinfo','id'=>$info_exist->id,'auth'=>md5($info_exist->id.$this->_adminUserName.'icntv')));
        }
    }
    
    public function actionDailyEdit($id=0){
        if(empty($id) || !$info = Daily::model()->findByPk($id)){
            $this->error("ID=$id 的数据不存在");
        }
        elseif($info->user_id != $this->_adminUserId){
            $this->error("只能编辑自己的日报");
        }elseif($info->status==1){
            $this->redirect(array('dailyinfo','id'=>$id));
        }
        if(isset($_POST['Daily'])){
            $report = is_array($_POST['Daily'])?$_POST['Daily']:[];
            $report_new = is_array($_POST['NewDaily'])?$_POST['NewDaily']:[];
            $report_info = array_merge($report, $report_new);
            $info->report_info = json_encode($report_info);
            if($info->save()){
                $this->redirect(array('daily'));
            }else{
                $this->error("编辑失败！");
            }
        }
        $report_info = json_decode($info->report_info,true);
        $this->render('daily_edit',array('report_info'=>$report_info));
    }
    
    public function actionDailyPush($id=0){
        if(empty($id) || !$info = Daily::model()->findByPk($id)){
            $this->error("ID=$id 的数据不存在");
        }
        if($info->user_id != $this->_adminUserId){
            $this->error("只能提交自己的日报");
        }
        if($info->status==1){
            $this->error("已经提交过");
        }
        $info->status = 1;
        $info->update();
        $this->redirect(array('daily'));
    }
    
    public function actionDailyInfo($id=0,$auth=''){
        if(empty($id) || !$info = Daily::model()->findByPk($id)){
            $this->error("ID=$id 的数据不存在");
        }
        elseif($auth != md5($id.$this->_adminUserName.'icntv')){
            $this->error("没有权限查看日报");
        }
        elseif($info->status==0){
            //$this->redirect(array('dailyedit','id'=>$id));
        }
        $comment = new DailyComment();
        if(isset($_POST['DailyComment'])){
            $comment->attributes = $this->_gets->getPost('DailyComment');
            $comment->daily_id = $id;
            $comment->user_id = $this->_adminUserId;
            $comment->user_name = $this->_adminRealName;
            $comment->create_time = time();
            if($comment->save()){
                $this->redirect(array('dailyinfo','id'=>$id, 'auth'=>$auth));
            }else{
                $this->error(json_encode($comment->errors));
            }
        }
        $criteria =new CDbCriteria;
        $criteria->addCondition('daily_id='.$id);
        $criteria->order = 'create_time desc';
        $comment_list = DailyComment::model()->findAll($criteria);
        $report_info = json_decode($info->report_info,true);
        $this->render('daily_info',array('model'=>$info,'report_arr'=>$report_info,'comm'=>$comment,'commlist'=>$comment_list));
    }
    
    public function actionDailyList(){
        //$c = new CDbCriteria();
        //$c->addCondition('leader_id='.$this->_adminUserId);

        if($this->isgroup('3')){
            $m = new Structure();
            $modols = $m->findAll();
            foreach ($modols as $model){
                $result[] = $model->attributes;
            }
        }else{
            $m = new Structure();
            $modols = $m->findAll('user_id='.$this->_adminUserId);
            $deep = 100;
            $id=0;
            foreach ($modols as $model){
                if($model->deep < $deep){
                    $deep = $model->deep;
                    $id = $model->id;
                }
            }
            if($id){
                $result = [];
                $m->getlower($id,$result);
            }else{
                $result = [];
            }
        }
        $output = [];
        foreach ($result as $item){
            $array['id'] = $item['id'];
            $array['name'] = $item['user_name'];
            $array['pId'] = $item['pid'];
            $array['userId'] = $item['user_id'];
            $array['auth'] = md5($item['user_id'].$this->_adminUserName.'icntv');
            $output[] = $array;
        }
        $output_str = json_encode($output); 
        
        $this->render('daily_list',array('lower'=>$result,'zNodes'=>$output_str));
    }
    
    /**
     * 模板数据处理
     */
    public function actionDealdata()
    {
        $type = $_REQUEST['type'];
        //print_r($post_data);
        switch ($type){
            case 'get_daily_data':
                $id = $this->_gets->getPost('id');
                $model = Daily::model()->find("id=".$id);
                if(!$model){
                    parent::error('fail to get data');
                }
                header('Content-Type:application/json; charset=utf-8');
                echo $model->report_info;
                break;
            case 'get_plan_data':
                $start = $this->_gets->getQuery('start');
                $end = $this->_gets->getQuery('end');
                $id = $this->_gets->getQuery('id');
                $auth = $this->_gets->getQuery('auth');
                if($id){
                    if($auth != md5($id.$this->_adminUserName.'icntv')){
                        parent::error('no permission',404,'',true);
                    }
                }else{
                    $id = $this->_adminUserId;
                }
                $output_arrays = [];
                //get plan data
                /*
                $criteria = new CDbCriteria();
                $criteria->addCondition('user_id='.$this->_adminUserId);
                $criteria->addCondition("start < '$start' and end >= '$start'");
                $criteria->addCondition("start >= '$start' and start <= '$end'", 'OR');
                $models = Plan::model()->findAll($criteria);
                foreach ($models as $model){
                    $array = $model->attributes;
                    unset($array['allDay']);
                    unset($array['editable']);
                    unset($array['start']);
                    unset($array['end']);
                    $array['allDay'] = $model->allDay?true:false;
                    $array['start'] = $array['allDay']? substr($model->start, 0,strpos($model->start, ' ')):$model->start;
                    if($model->end){
                       $array['end'] = $array['allDay']? substr($model->end, 0,strpos($model->end, ' ')):$model->end;
                    }
                    $output_arrays[] = $array;
                }
                */
                //get daily data
                $criteria = new CDbCriteria();
                $criteria->addCondition('user_id='.$id);
                $criteria->addCondition("date_time >= '$start' and date_time <= '$end'");
                $dailys = Daily::model()->findAll($criteria);
                foreach ($dailys as $daily){
                    $array = json_decode($daily->report_info,true);
                    foreach ($array as $item){
                        $tmp_arr['title'] = $item['content'];
                        $tmp_arr['start'] = $daily->date_time.' '.$item['timestart'];
                        $tmp_arr['end'] = $daily->date_time.' '.$item['timeend'];
                        $tmp_arr['borderColor'] = '#398439';
                        $tmp_arr['backgroundColor'] = '#449D44';
                        $output_arrays[] = $tmp_arr;
                    }
                }
                
                header('Content-Type:application/json; charset=utf-8');
                echo json_encode($output_arrays);
                break;
            case 'post_plan_data':
                $title = $this->_gets->getPost('title');
                $start = $this->_gets->getPost('start');
                $end = $this->_gets->getPost('end');
                $allDay = $this->_gets->getPost('allDay');
                $model = new Plan();
                $model->user_id = $this->_adminUserId;
                $model->title = $title;
                $model->start = $start;
                $model->end = $end;
                $model->allDay = $allDay=='true'?true:false;
                if($model->insert()){
                    $res = ['success'=>true];
                }else{
                    parent::error('system error',0,'',true);
                }
                header('Content-Type:application/json; charset=utf-8');
                echo json_encode($res);
                break;
            default:
                parent::error('params error',0,'',true);
                break;
        }
        exit;
    }
    
    public function actionPlan(){
        $id = $this->_gets->getQuery('id',false);
        $auth = $this->_gets->getQuery('auth',false);
        $this->render('plan',array('id'=>$id,'auth'=>$auth));
    }
}