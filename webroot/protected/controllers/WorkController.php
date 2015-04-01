<?php
class WorkController extends XAdminBase
{
    public function actionDaily(){
        $keyword = $this->_gets->getQuery('keyword');
        $criteria =new CDbCriteria;
        $criteria->addCondition('user_id='.$this->_adminUserId);
        if($keyword){
            $criteria->addSearchCondition('report_info', $keyword);
        }
        $criteria->order='date_time desc';
        $models = Daily::model()->findAll($criteria);
        //print_r($models);
        $this->render('daily',array('models'=>$models,'keyword'=>$keyword));
    }
    
    public function actionDailyCreate(){
        $info = new Daily();
        if(isset($_POST['Daily']) || isset($_POST['NewDaily'])){
            //var_dump($_POST);exit;
            $info->date_time = $_POST['time_date'];
            $info->user_id = $this->_adminUserId;
            $info->user_name = $this->_adminUserName;
            $info->create_time = time();
            $report = is_array($_POST['Daily'])?$_POST['Daily']:[];
            $report_new = is_array($_POST['NewDaily'])?$_POST['NewDaily']:[];
            $report_info = '';
            foreach ($report as $inf){
                $text = implode('|||', $inf);
                $text = str_replace(',', '，', $text);
                $text = str_replace('|||', ',', $text);
                $text = $text."\n";
                $report_info .= $text;
            }
            foreach ($report_new as $newinf){
                $text = implode('|||', $newinf);
                $text = str_replace(',', '，', $text);
                $text = str_replace('|||', ',', $text);
                $text = $text."\n";
                $report_info .= $text;
            }
            $info->report_info = $report_info;
            if($info->save()){
                $this->redirect(array('daily'));
            }else{
                $this->error("添加失败！");
            }
        }
        $info_exist = Daily::model()->find('date_time=\''.date('Y-m-d').'\'');
        $daily_exist = ($info_exist->status==1) ? true : false;
        if(!$info_exist){
            $this->render('daily_edit',array('model'=>$info,'daily_exist'=>$daily_exist));
        }else if(!$daily_exist) {
            $this->redirect(array('dailyedit','id'=>$info_exist->id));
        }else {
            $this->redirect(array('dailyinfo','id'=>$info_exist->id));
        }
    }
    
    public function actionDailyEdit($id=0){
        if(empty($id) || !$info = Daily::model()->findByPk($id)){
			$this->error("ID=$id 的数据不存在");
		}
		if($info->user_id != $this->_adminUserId){
		    $this->error("只能编辑自己的日报");
		}
		if(isset($_POST['Daily'])){
			$info->attributes = $this->_gets->getPost('Daily');
			if($info->save()){
				$this->redirect(array('daily'));
			}else{
			    $this->error("编辑失败！");
			}
		}
		$this->render('daily_edit',array('model'=>$info));
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
    
    public function actionDailyInfo($id=0){
        if(empty($id) || !$info = Daily::model()->findByPk($id)){
            $this->error("ID=$id 的数据不存在");
        }
        if($info->user_id != $this->_adminUserId){
            $this->error("没有权限查看日报");
        }
        $comment = new DailyComment();
        if(isset($_POST['DailyComment'])){
            $comment->attributes = $this->_gets->getPost('DailyComment');
            $comment->daily_id = $id;
            $comment->user_id = $this->_adminUserId;
            $comment->user_name = $this->_adminUserName;
            $comment->create_time = time();
            if($comment->save()){
                $this->redirect(array('dailyinfo','id'=>$id));
            }else{
                $this->error("添加失败！");
            }
        }
        $criteria =new CDbCriteria;
        $criteria->addCondition('daily_id='.$id);
        $criteria->order = 'create_time desc';
        $comment_list = DailyComment::model()->findAll($criteria);
        $report_info = $info->report_info;
        $report_tmp = explode("\n", $report_info);
        $report_arr = [];
        foreach ($report_tmp as $k){
            $tmp_arr = explode(',', $k);
            $report_arr[] = $tmp_arr;
        }
        $this->render('daily_info',array('model'=>$info,'report_arr'=>$report_arr,'comm'=>$comment,'commlist'=>$comment_list));
    }
    
    public function actionDailyList(){
        $c = new CDbCriteria();
        $c->addCondition('leader_id='.$this->_adminUserId);
        $this->render('daily_list');
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
        	    $criteria = new CDbCriteria();
        	    $criteria->addCondition('user_id='.$this->_adminUserId);
        	    $criteria->addCondition("start < '$start' and end > '$end'");
        	    $criteria->addCondition("start > '$start' and start < '$end'", 'OR');
        	    $models = Plan::model()->findAll($criteria);
        	    $output_arrays = [];
        	    foreach ($models as $model){
        	        $array = $model->attributes;
        	        unset($array['allDay']);
        	        unset($array['editable']);
        	        unset($array['start']);
        	        unset($array['end']);
        	        $array['allDay'] = $model->allDay?true:false;
        	        if($model->editable) $array['editable'] = true;
        	        $array['start'] = $array['allDay']? substr($model->start, 0,strpos($model->start, ' ')):$model->start;
        	        if($model->end){
        	           $array['end'] = $array['allDay']? substr($model->end, 0,strpos($model->end, ' ')):$model->end;
        	        }
        	        $output_arrays[] = $array;
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
    	        $model->allDay = $allDay;
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
        $this->render('plan');
    }
}