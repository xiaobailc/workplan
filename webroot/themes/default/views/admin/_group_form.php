<?php if (CHtml::errorSummary($model)):?>

<table id="tips">
	<tr>
	<td><div class="erro_div"><span class="error_message">
		<?php echo CHtml::errorSummary($model); ?>
		</span></div></td>
	</tr>
</table>
<?php endif?>
<script type="text/javascript">
	function checknode(obj) {
		var chk = $("input[type='checkbox']");
		var count = chk.length;
		var num = chk.index(obj);
		var level_top = level_bottom = chk.eq(num).attr('level');
		for (var i = num; i >= 0; i--) {
			var le = chk.eq(i).attr('level');
			if (eval(le) < eval(level_top)) {
				chk.eq(i).attr("checked", true);
				var level_top = level_top - 1
			}
		}
		for (var j = num + 1; j < count; j++) {
			var le = chk.eq(j).attr('level');
			if (chk.eq(num).attr("checked") == true) {
				if (eval(le) > eval(level_bottom)) chk.eq(j).attr("checked", true);
				else if (eval(le) == eval(level_bottom)) break
			} else {
				if (eval(le) > eval(level_bottom)) chk.eq(j).attr("checked", false);
				else if (eval(le) == eval(level_bottom)) break
			}
		}
	}
</script>
<?php $form=$this->beginWidget('CActiveForm',array('id'=>'xform','htmlOptions'=>array('name'=>'xform'))); ?>
<table class="form_table">
	<tr>
		<td  colspan="2" class="tb_title">用户组名称：</td>
	</tr>
	<tr >
		<td colspan="2" ><?php echo $form->textField($model,'group_name',array('size'=>50,'maxlength'=>128, 'class'=>'validate[required]')); ?></td>
	</tr>
	<tr>
		<td  colspan="2" class="tb_title">用户组权限：</td>
	</tr>
	<?php $rules = explode(',',$model->rules);?>
<?php foreach($node_list as $node):?>
	<tr>
		<td  colspan="2">
<?php if($node['id'] == 1):?>
			<input name="rules[]" type="hidden" value="<?php echo $node['id'] ?>" />
<?php else:?>
			<input name="rules[]" type="checkbox" value="<?php echo $node['id'] ?>" <?php if(in_array($node['id'],$rules)){echo 'checked="checked"';}?> level='0' onclick='javascript:checknode(this);'/>
<?php endif?>
			<?php echo $node['title'] ?></td>
	</tr>
<?php foreach((array)$node['child'] as $k=>$child):?>
	<tr >
		<td style="width: 200px;padding-left:50px">
			<input name="rules[]" type="checkbox" value="<?php echo $child['id'] ?>" <?php if(in_array($child['id'],$rules)){echo 'checked="checked"';}?> level='1' onclick='javascript:checknode(this);'/>
			<?php echo $child['title']?></td>
		<td class="vtop tips2">
<?php foreach((array)$child['operator'] as $op):?>
		<input name="rules[]" type="checkbox" value="<?php echo $op['id'] ?>" <?php if(in_array($op['id'],$rules)){echo 'checked="checked"';}?> level='2' onclick='javascript:checknode(this);'/>
		<?php echo $op['title']?>
<?php endforeach; ?>
		</td>
	</tr>
<?php endforeach;?>
<?php endforeach;?>
<tr class="submit">
	<td colspan="2" ><input type="checkbox" name="chkall" id="chkall" onClick="checkAll(this.form, 'rules')" />
<label for="chkall">全选</label></td> 
</tr>
<tr class="submit">
	<td colspan="2"><input name="submit" type="submit" id="submit" value="提交" class="btn btn-primary btn-sm" /></td>
	</tr>
</table>


<script type="text/javascript">
$(function(){
	$("#xform").validationEngine();	
});
</script>
<?php $form=$this->endWidget(); ?>
