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
        if(isset($_POST['Daily'])){
            $info->attributes = $this->_gets->getPost('Daily');
            $info->user_id = $this->_adminUserId;
            $info->user_name = $this->_adminUserName;
            $info->create_time = time();
            if($info->save()){
                $this->redirect(array('daily'));
            }else{
                $this->error("添加失败！");
            }
        }
        $info_exist = Daily::model()->find('date_time=\''.date('Y-m-d').'\'');
        $daily_exist = ($info_exist->status==1) ? true : false;
        if(!$info_exist)
            $this->render('daily_edit',array('model'=>$info,'daily_exist'=>$daily_exist));
        else if(!$daily_exist)    
            $this->redirect(array('dailyedit','id'=>$info_exist->id));
        else 
            $this->error("当日日报已经提交！");
            //$this->redirect(array('dailyinfo','id'=>$info_exist->id));
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
        
        $this->render('daily_info',array('model'=>$info,'comm'=>$comment,'commlist'=>$comment_list));
    }
    
    public function actionDailyList(){
        $this->render('daily_list');
    }
    
    /**
     * 模板数据处理
     */
    public function actionDealdata()
    {
        $type = $this->_gets->getPost('type');
        $screen = $this->_gets->getPost('screen');
        $panel = $this->_gets->getPost('panel');
        $index = $this->_gets->getPost('index');
        $id = $this->_gets->getPost('id');
        $data = $this->_gets->getPost('data');
        $post_data = $_POST;
        //print_r($post_data);
        switch ($type){
        	case 'get_daily_data':
        	    $model = Daily::model()->find("id=".$id);
        	    if(!$model){
        	        parent::error('fail to get data');
        	    }
        	    header('Content-Type:application/json; charset=utf-8');
        	    echo $model->report_info;
        	    break;
        	case 'get_plan_data':
        	    header('Content-Type:application/json; charset=utf-8');
        	    echo json_encode(['a'=>'b']);
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