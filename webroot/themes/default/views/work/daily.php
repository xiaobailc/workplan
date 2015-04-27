<?php $this->renderPartial('/_include/header');?>
<div id="contentHeader">
    <h3>日报管理-<?php echo $userinfo['realname']?></h3>
    <div class="searchArea">
        <p class="left" >
            <a href="javascript:history.go(-1)" class="btn btn-success btn-sm">返回上页</a>
<?php if(!$lowerdaily):?>
            <a href="<?php echo $this->createUrl('dailycreate')?>" class="btn btn-success btn-sm">添加当日日报</a>
            <a href="<?php echo $this->createUrl('dailycreate',array('date_time'=>date('Y-m-d',strtotime('-1 day'))))?>" class="btn btn-success btn-sm">添加昨日日报</a>
            <a href="<?php echo $this->createUrl('dailycreate',array('date_time'=>date('Y-m-d',strtotime('-2 day'))))?>" class="btn btn-success btn-sm">添加前日日报</a>
<?php endif;?>
        </p>
        <div class="right">
<?php echo CHtml::form('', 'get', array('class'=>'form-inline'));?>
            <div class="datetimepicker" class="input-append date">
                <input data-format="hh:mm" type="text" name="data_start" value="" />
                <span class="add-on"><i data-time-icon="fa fa-clock-o" data-date-icon="fa fa-clock-o"></i></span>
            </div>
            <div class="datetimepicker" class="input-append date">
                <input data-format="hh:mm" type="text" name="data_end" value="" />
                <span class="add-on"><i data-time-icon="fa fa-clock-o" data-date-icon="fa fa-clock-o"></i></span>
            </div>
            <input type="submit" value="搜索" class="btn btn-primary btn-sm btn-sm" />
<?php echo CHtml::endForm();?>
        </div>
    </div>
</div>

<table class="table table-bordered table-condensed">
    <thead>
        <tr class="active">
            <th style="width: 100px" >星期</th>
            <th style="width: 150px" >日期</th>
            <th style="width: 150px" >状态</th>
            <th style="" >操作</th>
        </tr>
    </thead>
    <tbody>
<?php foreach ($models as $index => $model):?>
        <tr>
            <td><?php
                $week = ['日','一','二','三','四','五','六'];
                $num = date('w',strtotime($model->date_time));
                echo "星期".$week[$num];
            ?></td>
            <td >
<?php if($model->status==1):?>
                <a href="<?php echo $this->createUrl('dailyinfo',array('id'=>$model->id,'auth'=>md5($model->id.$this->_xsession['_adminUserName'].'icntv')))?>"><?php echo $model->date_time;?></a>
<?php else :?>
                <?php echo $model->date_time;?>

<?php endif;?>
            </td>
            <td><?php echo $model->status==0?"未提交":'已提交';?></td>
            <td class="group-btn">
<?php if(!$lowerdaily):?>
                <a class="btn btn-default btn-xs <?php echo $model->status==0?'':'disabled';?>" href="<?php echo $this->createUrl('dailyedit',array('id'=>$model->id))?>"><i class="fa fa-pencil"></i> 编辑</a>
                <a class="btn btn-info btn-xs confirmSubmit <?php echo $model->status==0?'':'disabled';?>" href="<?php echo $this->createUrl('dailypush',array('id'=>$model->id))?>"><i class="fa fa-check"></i> 提交</a>
<?php endif;?>
            </td>
        </tr>
<?php endforeach;?>
    </tbody>
</table>
<script type="text/javascript">
$(function(){
	$('.datetimepicker').datetimepicker({
	    pickTime: false
	});
});
</script>
<?php $this->renderPartial('/_include/footer');?>