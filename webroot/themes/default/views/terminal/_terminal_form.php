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
		<td class="tb_title"><?php echo CHtml::activeLabel($model, 'partial');?>：</td>
	</tr>
	<tr >
		<td >
<?php if ($model->isNewRecord):?>
		<?php echo $form->textField($model,'partial',array('size'=>30,'maxlength'=>20, 'value'=>$model->partial, 'class'=>'validate[required,maxSize[20]]')); ?>
<?php else:?>
		<?php echo $form->textField($model,'partial',array('size'=>30,'maxlength'=>20, 'value'=>$model->partial, 'disabled'=>'true')); ?>
<?php endif;?>
		</td>
	</tr>
	<tr>
		<td class="tb_title"><?php echo CHtml::activeLabel($model, 'group');?>：</td>
	</tr>
	<tr >
		<td>
		<?php echo $form->textField($model,'group',array('size'=>30,'maxlength'=>50, 'value'=>$model->group, 'class'=>'validate[required,maxSize[50]]')); ?>
		</td>
	</tr>
	<tr>
		<td class="tb_title"><?php echo CHtml::activeLabel($model, 'headface_url');?>：</td>
	</tr>
	<tr >
		<td>
		<input name="headface_url" type="file" id="headface_url" />
		<?php if ($model->headface_url):?>
		<a href="<?php echo $this->_baseUrl.'/'. $model->headface_url?>" target="_blank"><img src="<?php echo $this->_baseUrl.'/'. $model->headface_url?>" width="150" align="absmiddle" /></a>
		<?php endif?>
		</td>
	</tr>
	<tr>
		<td class="tb_title"><?php echo CHtml::activeLabel($model, 'type');?>：</td>
	</tr>
	<tr >
		<td>
		<?php
			$is_blank = CHtml::activeRadioButtonList($model,'type',array('1'=>'测试版本','2'=>'正式版本','3'=>'其他版本'), array('class'=>'validate[required]','template'=>'{input}{label}','separator'=>"    "));
			$is_blank= str_replace("<label", "<span", $is_blank);  
			$is_blank= str_replace("</label", "</span", $is_blank);  
			echo $is_blank;
		?>
		</td>
	</tr>
	<tr class="submit">
		<td colspan="2">
			<button type="submit" id="submit" class="btn btn-primary btn-sm" ><b>提交</b></button>
			<a href="<?php echo $this->createUrl('index')?>" class="btn btn-success btn-sm"><b>返回</b></a>
		</td>
	</tr>
</table>

<script type="text/javascript">
$(function(){
	$("#xform").validationEngine();	
});
</script>
<?php $form=$this->endWidget(); ?>
