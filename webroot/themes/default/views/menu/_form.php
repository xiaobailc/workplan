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
    <td class="tb_title">菜单名称：</td>
  </tr>
  <tr >
    <td ><?php echo $form->textField($model,'title',array('size'=>40,'maxlength'=>128, 'class'=>'validate[required]')); ?></td>
  </tr>
  <tr>
    <td class="tb_title">排序：</td>
  </tr>
  <tr >
    <td ><?php echo $form->textField($model,'sort',array('size'=>40,'maxlength'=>128)); ?></td>
  </tr>
  <tr>
    <td class="tb_title">链接地址：</td>
  </tr>
  <tr >
    <td ><?php echo $form->textField($model,'url',array('size'=>40,'maxlength'=>128, 'class'=>'validate[required]')); ?></td>
  </tr>
  <tr>
    <td class="tb_title">上级菜单：</td>
  </tr>
  <tr >
    <td ><select name="Menu[pid]">
        <?php foreach($menus as $index=>$value):?>
        <option value="<?php echo $value['id']?>" <?php XUtils::selected($value['id'], $parentId);?>><?php echo $value['title_show']?></option>
        <?php endforeach;?>
      </select></td>
  </tr>
  <tr>
    <td class="tb_title">分组：</td>
  </tr>
  <tr >
    <td class="tb_title"><?php echo $form->textField($model,'group',array('size'=>40,'maxlength'=>50)); ?></td>
  </tr>
  
  <tr>
    <td class="tb_title">描述(font-awesome)：</td>
  </tr>
  <tr >
    <td ><?php echo $form->textField($model,'tip',array('size'=>40,'maxlength'=>128)); ?></td>
  </tr>
  <tr >
    <td >隐藏：<?php echo $form->checkBox($model,'hide'); ?>开发者可见:<?php echo $form->checkBox($model,'is_dev'); ?></td>
  </tr>
  <tr class="submit">
    <td ><input type="submit" name="editsubmit" value="提交" class="btn btn-primary btn-sm" tabindex="3" /></td>
  </tr>
</table>
<script type="text/javascript">
$(function(){
	$("#xform").validationEngine();	
});
</script>
<?php $form=$this->endWidget(); ?>
