<?php $this->renderPartial('/_include/header');?>
<div id="contentHeader">
	<h3>添加终端版本</h3>
	<div class="searchArea">
		<p class="left" >
			<a href="<?php echo $this->createUrl('tplindex',array('tid'=>$tid))?>" class="btn btn-success btn-sm"><b>返回</b></a>
		</p>
		<div class="search right"> </div>
	</div>
</div>
<?php if (CHtml::errorSummary($model)):?>
<table id="tips">
  <tr>
    <td><div class="erro_div"><span class="error_message">
       <?php echo CHtml::errorSummary($model); ?>
        </span></div></td>
  </tr>
</table>
<?php endif?>
<?php $form=$this->beginWidget('CActiveForm',array('id'=>'xform','htmlOptions'=>array('name'=>'xform','enctype'=>'multipart/form-data'))); ?>
<table class="form_table">
	<tr>
		<td class="tb_title"><?php echo CHtml::activeLabel($model, 'name');?>：</td>
	</tr>
	<tr >
		<td >
		<?php echo $form->textField($model,'name',array('size'=>30,'maxlength'=>50, 'value'=>$model->name, 'class'=>'validate[required,maxSize[50]]')); ?>
		</td>
	</tr>
	<tr>
		<td class="tb_title"><?php echo CHtml::activeLabel($model, 'screen');?>：</td>
	</tr>
	<tr >
		<td>
		<?php echo $form->dropDownList($model,'screen',array('720'=>'720', '1080'=>'1080'),array('class'=>'test'))?>
		<?php echo $form->hiddenField($model,'tvid',array('value'=>$tid))?>
		</td>
	</tr>
	<tr class="submit">
		<td colspan="2">
			<button type="submit" id="submit" class="btn btn-primary btn-sm" ><b>提交</b></button>
			<a href="<?php echo $this->createUrl('tplindex')?>" class="btn btn-success btn-sm"><b>返回</b></a>
		</td>
	</tr>
</table>

<script type="text/javascript">
$(function(){
	$("#xform").validationEngine();	
});
</script>
<?php $form=$this->endWidget(); ?>

<?php $this->renderPartial('/_include/footer');?>
