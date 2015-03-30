<?php $this->renderPartial('/_include/header');?>
<div id="contentHeader">
  <h3>查看日报</h3>
  <div class="searchArea">
    <p class="left" >
      <a href="<?php echo $this->createUrl('daily')?>" class="btn btn-success btn-sm">返回</a>
    </p>
    <div class="search right"> </div>
  </div>
</div>
<?php $form = $this->beginWidget('CActiveForm',array('id'=>'xform','htmlOptions'=>array('name'=>'xform', 'enctype'=>'multipart/form-data'))); ?>
<table class="form_table">
    <tr>
        <td class="tb_title"><h3><?php echo date('Y-m-d');?>日报</h3></td>
    </tr>
    <tr>
    <td><pre style="font-size: 14px"><?php echo $model->report_info;?></pre></td>
    </tr>
    <tr><td class="tb_title">添加评论:</td></tr>
    <tr>
        <td>
        <?php echo $form->textArea($comm,'comment', array('rows'=>2, 'cols'=>90,'maxlength'=>200,'class'=>'validate[required]')); ?>
        </td>
    </tr>
    <tr>
        <td><input type="submit" name="editsubmit" value="提交" class="btn btn-primary btn-sm" tabindex="3" /></td>
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