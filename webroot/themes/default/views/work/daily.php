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
            <div class="form-group">
                <label for="dtp_input3" class="control-label">开始时间</label>
                <div class="input-group date form_date " data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                    <input class="form-control" size="16" type="text" value="" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove fa fa-times"></span></span>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar fa fa-calendar"></span></span>
                </div>
                <input type="hidden" id="dtp_input3" value="" /><br/>
            </div>
            <div class="form-group">
                <label for="dtp_input3" class="control-label">结束时间</label>
                <div class="input-group date form_date " data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                    <input class="form-control" size="16" type="text" value="" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove fa fa-times"></span></span>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar fa fa-calendar"></span></span>
                </div>
                <input type="hidden" id="dtp_input3" value="" /><br/>
            </div>
            <input type="submit" value="搜索" class="btn btn-primary btn-sm btn-sm" />
<?php echo CHtml::endForm();?>
        </div>
    </div>
</div>

<table class="table table-bordered table-condensed">
    <thead>
        <tr class="active">
            <th style="width: 50px" >周</th>
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
                $num_w = date('w',strtotime($model->date_time));
                $num_W = date('W',strtotime($model->date_time));
                echo $num_W;
            ?></td>
            <td><?php echo "星期".$week[$num_w];?></td>
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
    $('.form_date').datetimepicker({
        weekStart: 1,
        todayBtn:  1,
    	autoclose: 1,
    	todayHighlight: 1,
    	startView: 2,
    	minView: 2,
    	forceParse: 0
    });
</script>
<?php $this->renderPartial('/_include/footer');?>