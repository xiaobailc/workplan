<?php if (CHtml::errorSummary($model)):?>

<table id="tips">
  <tr>
    <td><div class="erro_div"><span class="error_message"> <?php echo CHtml::errorSummary($model); ?> </span></div></td>
  </tr>
</table>
<?php endif?>

<?php $form = $this->beginWidget('CActiveForm',array('id'=>'xform','htmlOptions'=>array('name'=>'xform', 'enctype'=>'multipart/form-data'))); ?>
<table class="form_table">
  <tr>
    <td class="tb_title"><h3><?php echo date('Y-m-d');?>日报</h3>
    <?php echo $form->hiddenField($model,'date_time',array('value'=>date('Y-m-d')))?>
    </td>
  </tr>
  <tr >
<?php if($daily_exist):?>
    <td><pre><?php echo $model->report_info;?></pre></td>
<?php else:?>
    <td ><?php echo $form->textArea($model,'report_info', array('rows'=>15, 'cols'=>90,'class'=>'validate[required]')); ?>
<?php
/*
    $this->widget('application.widget.kindeditor.KindEditor',array(
        'target'=>array(
            '#Daily_report_info'=>array(
                'uploadJson'=>$this->createUrl('upload'),
                'extraFileUploadParams'=>array(
                    array('sessionId'=>session_id())
                )
            )
        )
    ));
    */
?>
    </td>
<?php endif;?>
  </tr>
  <tr class="submit">
    <td ><input type="submit" name="editsubmit" value="提交" class="btn btn-primary btn-sm <?php if($daily_exist) echo 'disabled';?>" tabindex="3" /></td>
  </tr>
</table>
<script type="text/javascript">
$(function(){
	$("#xform").validationEngine();	
});
</script>
<?php $form=$this->endWidget(); ?>
