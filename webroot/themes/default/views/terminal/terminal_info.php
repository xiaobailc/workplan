<?php $this->renderPartial('/_include/header');?>

<?php $form=$this->beginWidget('CActiveForm',array('id'=>'xform','htmlOptions'=>array('name'=>'xform','enctype'=>'multipart/form-data'))); ?>
<table class="table table-condensed">
	<tr>
		<td class="tb_title"><?php echo CHtml::activeLabel($model, 'default_reply_flag');?>：</td>
	</tr>
	<tr >
		<td>
		<?php  
			$is_blank = CHtml::activeRadioButtonList($model,'default_reply_flag',array('1'=>'开启','0'=>'关闭'),
			array('template'=>'{input}{label}','separator'=>"    "));  
			$is_blank= str_replace("<label", "<span", $is_blank);  
			$is_blank= str_replace("</label", "</span", $is_blank);  
			echo $is_blank;       
		?>
		</td>
	</tr>
	<tr>
		<td class="tb_title"><?php echo CHtml::activeLabel($model, 'default_reply');?>：</td>
	</tr>
	<tr >
		<td>
		<?php if ($model->isNewRecord):?>
		<?php echo $form->textField($model,'default_reply',array('size'=>80,'maxlength'=>80, 'class'=>'validate[required]')); ?>
		<?php else:?>
		<?php echo $form->textField($model,'default_reply',array('size'=>80,'maxlength'=>80, 'value'=>$model->default_reply, 'class'=>'validate[required]')); ?>
		<?php endif?>
		</td>
	</tr>
	<tr>
		<td class="tb_title"><?php echo CHtml::activeLabel($model, 'lbs_distance');?>：</td>
	</tr>
	<tr >
		<td>
		<?php if ($model->isNewRecord):?>
		<?php echo $form->textField($model,'lbs_distance',array('size'=>30,'maxlength'=>50, 'class'=>'validate[required]')); ?>
		<?php else:?>
		<?php echo $form->textField($model,'lbs_distance',array('size'=>30,'maxlength'=>128, 'value'=>$model->lbs_distance, 'class'=>'validate[required]')); ?>
		<?php endif?>
		单位（米）
		</td>
	</tr>
	<tr class="submit">
		<td colspan="2">
		<input type="hidden" name="pubid" value="<?php echo $pubid;?>">
		<input name="submit" type="submit" id="submit" value="提交" class="btn btn-primary btn-sm" /></td>
	</tr>
</table>
<script type="text/javascript">
$(function(){
	$("#xform").validationEngine();	
});
</script>
<?php $form=$this->endWidget(); ?>
<?php $this->renderPartial('/_include/footer');?>
