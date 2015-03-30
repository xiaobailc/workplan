<?php if (CHtml::errorSummary($model)):?>

<table id="tips">
  <tr>
    <td><div class="erro_div"><span class="error_message">
       <?php echo CHtml::errorSummary($model); ?>
        </span></div></td>
  </tr>
</table>
<?php endif?>
<?php $form=$this->beginWidget('CActiveForm',array('id'=>'xform','htmlOptions'=>array('name'=>'xform'))); ?>
<table class="form_table">
  <tr>
    <td class="tb_title">用户：</td>
  </tr>
  <tr >
    <td ><?php if ($model->isNewRecord):?>
      <?php echo $form->textField($model,'username',array('size'=>30,'maxlength'=>128, 'class'=>'validate[required]')); ?>
      <?php else:?>
      <?php echo $model->username?>
      <?php endif?></td>
  </tr>
  <tr>
    <td class="tb_title">密码：</td>
  </tr>
  <tr >
    <td ><?php echo $form->textField($model,'password',array('size'=>30, 'maxlength'=>50, 'class'=>'validate[required]')); ?></td>
  </tr>
  <tr>
    <td class="tb_title">邮箱：</td>
  </tr>
  <tr >
    <td ><?php echo $form->textField($model,'email',array('size'=>30, 'maxlength'=>50, 'class'=>'validate[required]')); ?></td>
  </tr>
  <tr>
    <td class="tb_title">用户组：</td>
  </tr>
  <tr >
    <td >
<?php foreach ($this->group_list as $group):?>
		<label class="checkbox-inline">
		<input type="checkbox" class="auth_groups validate[minCheckbox[1]]" name="Admin[group_id][]" value="<?php echo $group->id;?>"> <?php echo $group->group_name;?>
		</label>
<?php endforeach;?>
</td>
  </tr>
  <tr>
    <td class="tb_title">真实姓名：</td>
  </tr>
  <tr >
    <td ><?php echo $form->textField($model,'realname',array('size'=>30,'maxlength'=>128)); ?></td>
  </tr>
  <tr>
    <td class="tb_title">电话：</td>
  </tr>
  <tr >
    <td ><?php echo $form->textField($model,'telephone',array('size'=>30,'maxlength'=>128)); ?></td>
  </tr>
   <tr>
    <td class="tb_title">手机：</td>
  </tr>
  <tr >
    <td ><?php echo $form->textField($model,'mobile',array('size'=>30,'maxlength'=>128)); ?></td>
  </tr>
   <tr>
    <td class="tb_title">QQ：</td>
  </tr>
  <tr >
    <td ><?php echo $form->textField($model,'qq',array('size'=>30,'maxlength'=>128)); ?></td>
  </tr>
  <tr>
    <td class="tb_title">备忘录：</td>
  </tr>
  <tr >
    <td ><?php echo $form->textArea($model,'notebook',array('rows'=>5, 'cols'=>70,'ondblclick'=>'textareasize(this, 1)','onkeyup'=>'textareasize(this, 0)')); ?></td>
  </tr>
  <tr>
    <td class="tb_title">状态：</td>
  </tr>
  <tr >
    <td ><?php echo $form->dropDownList($model,'status_is',array('Y'=>'已激活', 'N'=>'未激活')); ?></td>
  </tr>
  <tr>
    <td class="tb_title">最后登录时间：</td>
  </tr>
  <tr >
    <td ><?php echo date('Y-m-d H:i:s', $model->last_login_time) ?></td>
  </tr>
  <tr>
    <td class="tb_title">最后登录IP：</td>
  </tr>
  <tr >
    <td ><?php echo $model->last_login_ip ?></td>
  </tr>
  <tr>
    <td class="tb_title">登录次数：</td>
  </tr>
  <tr >
    <td ><?php echo $model->login_count ?></td>
  </tr>
   <tr class="submit">
      <td > <input name="submit" type="submit" id="submit" value="提交" class="btn btn-primary btn-sm" /></td>
    </tr>
</table>

<script type="text/javascript">
$(function(){
	var group = [<?php echo isset($model->group_id)?$model->group_id:'';?>];
	$('.auth_groups').each(function(){
		if( $.inArray( parseInt(this.value,10),group )>-1 ){
			$(this).prop('checked',true);
		}
	});
});

$(function(){
	$("#xform").validationEngine();	
});
</script>
<?php $form=$this->endWidget(); ?>
