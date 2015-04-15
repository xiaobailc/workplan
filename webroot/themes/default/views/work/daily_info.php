<?php $this->renderPartial('/_include/header');?>
<div id="contentHeader">
  <h3>查看日报</h3>
  <div class="searchArea">
    <p class="left" >
      <a href="javascript:history.go(-1)" class="btn btn-success btn-sm">返回上页</a>
    </p>
    <div class="search right"> </div>
  </div>
</div>
<h3><?php echo $model->date_time;?>日报</h3>
<p>员工：<?php echo $model->user_name;?></p>
<table class="table table-bordered table-condensed">
	<thead>
		<tr class="active">
			<th style="width: 50px" >编号</th>
			<th style="width: 175px" >事项</th>
			<th style="" >内容</th>
			<th style="width: 125px" >结果</th>
			<th style="width: 100px" >开始时间</th>
			<th style="width: 100px" >结束时间</th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($report_arr as $k=>$value):?>
		<tr>
		    <th><?php echo $k+1?></th>
<?php foreach ($value as $item):?>
			<td><?php echo $item?></td>
<?php endforeach;?>
		</tr>
<?php endforeach;?>
	</tbody>
	<!-- 
	<tfoot>
	<tr>
	   <td colspan="4"> </td>
	   <td colspan="2">累计时间：</td></tr>
	</tfoot>
	 -->
</table>
<?php $form = $this->beginWidget('CActiveForm',array('id'=>'xform','htmlOptions'=>array('name'=>'xform', 'enctype'=>'multipart/form-data'))); ?>
<table class="form_table">
    <tr><td class="tb_title">添加评论:</td></tr>
    <tr>
        <td>
        <?php echo $form->textArea($comm,'comment', array('rows'=>2, 'cols'=>90,'maxlength'=>200,'class'=>'validate[required]')); ?>
        </td>
    </tr>
    <tr>
        <td><input type="submit" name="editsubmit" value="保存" class="btn btn-primary btn-sm" tabindex="3" /></td>
    </tr>
    <tr><td class="tb_title">评论列表：</td></tr>
<?php foreach ($commlist as $commitem):?>
<tr><td><?php echo date('Y-m-d H:i:s',$commitem->create_time).' | '.$commitem->user_name.' | '.$commitem->comment;?></td></tr>
<?php endforeach;?>
</table>
<script type="text/javascript">
$(function(){
	$("#xform").validationEngine();	
});
</script>
<?php $form=$this->endWidget(); ?>
<?php $this->renderPartial('/_include/footer');?>