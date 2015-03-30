<?php $this->renderPartial('/_include/header');?>
<div class="searchArea">
	<p>
		<a id="add_menu" class="btn btn-success btn-sm" href="javascript:void(0);"><i class="fa fa-plus"></i> 添加主菜单</a>
	</p>
</div>
<?php echo CHtml::form('', 'post', array('id'=>'xform','class'=>'form_table', 'enctype'=>'multipart/form-data'));?>
<?php echo CHtml::hiddenField('tid',$tid)?>
<table class="table table-bordered table-condensed">
	<thead>
		<tr class="active">
			<th style="width:150px" >显示顺序</th>
			<th style="width:200px" >栏目名称</th>
			<th style="width:" >关键词</th>
			<th style="width:150px" >启用</th>
			<th style="width:150px" >操作</th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($models as $model):?>
<?php if($model->pid == 0):?>
		<tr>
			<td><?php echo CHtml::activeTextField($model, "[".$model->id."]sort",array('class'=>'','size'=>'5'));?></td>
			<td>
				<?php echo CHtml::activeTextField($model, "[".$model->id."]title",array('class'=>'validate[required]','size'=>'15'));?>
			</td>
			<td>
				<?php echo CHtml::activeTextField($model, "[".$model->id."]key",array('class'=>'validate[required]','size'=>'50'));?>
				<?php echo CHtml::activeHiddenField($model, "[".$model->id."]pid")?>
			</td>
			<td><?php echo CHtml::activeCheckBox($model, "[".$model->id."]status_is",array('uncheckValue'=>'N','checked'=>'checked'))?></td>
			<td><a href="<?php echo  $this->createUrl('batch',array('command'=>'menuDelete', 'id'=>$model->id))?>" class="btn btn-danger btn-xs confirmSubmit"><i class="fa fa-trash-o "></i> 删除</a></td>
		</tr>
<?php endif;?>
<?php foreach ($models as $m):?>
<?php if($m->pid == $model->id):?>
		<tr>
			<td><?php echo CHtml::activeTextField($m, '['.$m->id.']sort',array('class'=>'','size'=>'5'));?></td>
			<td><i class="fa fa fa-level-up fa-rotate-90 "></i> <?php echo CHtml::activeTextField($m, '['.$m->id.']title',array('class'=>'validate[required]','size'=>'15'));?></td>
			<td>
				<?php echo CHtml::activeTextField($m, '['.$m->id.']key',array('class'=>'validate[required]','size'=>'50'));?>
				<?php echo CHtml::activeHiddenField($m, '['.$m->id.']pid')?>
			</td>
			<td><?php echo CHtml::activeCheckBox($m, '['.$m->id.']status_is')?></td>
			<td><a href="<?php echo  $this->createUrl('batch',array('command'=>'menuDelete', 'id'=>$m->id))?>" class="btn btn-danger btn-xs confirmSubmit"><i class="fa fa-trash-o "></i> 删除</a></td>
		</tr>
<?php endif;?>
<?php endforeach;?>
<?php endforeach;?>
	</tbody>
	<thead>
		<tr class="submit"><td colspan="5">
			<input class="btn btn-primary btn-sm btn-sm" type="submit" value="保存" />
			<button id="create_menu" class="btn btn-primary btn-sm btn-sm" type="btn btn-primary btn-sm">生成自定义菜单</button>
			<button id="remove_menu" class="btn btn-danger btn-sm" type="btn btn-primary btn-sm">撤销自定义菜单</button>
		</td></tr>
	</thead>
</table>
<?php echo CHtml::endForm();?>
<script type="text/javascript">
$(function(){
	$("#xform").validationEngine();	
});

$(function(){
	var $menu_index = 0;
	$("#add_menu").click(function(){
		$menu_index++;
		var _menuPtrtmp = '<tr>'
		+ '<td><input name="NewMenu['+$menu_index+'][sort]" size="5" type="text" value="0" class="" data-rule-number="true" /></td>'
		+ '<td><input name="NewMenu['+$menu_index+'][title]" size="15" type="text" class="validate[required]" data-rule-required="true" data-rule-maxlength="30" /></td>'
		+ '<td><input name="NewMenu['+$menu_index+'][key]" size="50" type="text" class="validate[required]" data-rule-required="true" data-rule-maxlength="100" /></td>'
		+ '<input type="hidden" name="NewMenu['+$menu_index+'][pid]" value="0" /></td>'
		+ '<td><input type="checkbox" name="NewMenu['+$menu_index+'][status_is]" checked="checked" value="Y"/></td>'
		+ '<td><a class="btn btn-danger btn-xs delete-tr" href="javascript:void(0)"><i class="fa fa-trash-o "></i> 删除</a>'
		+ '</tr> ';
		$("tbody").append(_menuPtrtmp);
	});
	
	$(document).on("click", '.delete-tr',function () {
		$(this).parents("tr").remove();
	});
	/*
	$("a.add").click(function(){
		var $pid = $(this).attr("rel");
		var $thistr = $(this).parent().parent();
		$menu_index++;
		var _menuPtrtmp = '<tr>'
		+ '<td><input name="NewMenu['+$menu_index+'][sort]" size="5" type="text" value="0" class="" data-rule-number="true" /></td>'
		+ '<td><i class="fa fa fa-level-up fa-rotate-90 "></i> <input name="NewMenu['+$menu_index+'][title]" size="15" type="text" class="validate[required]" data-rule-required="true" data-rule-maxlength="30" /></td>'
		+ '<input type="hidden" name="NewMenu['+$menu_index+'][pid]" value="'+$pid+'" /></td>'
		+ '<td><input type="checkbox" name="NewMenu['+$menu_index+'][status_is]" checked="checked" value="Y"/></td>'
		+ '<td><a class="btn btn-danger btn-xs delete-tr" href="javascript:void(0)"><i class="fa fa-trash-o "></i> 删除</a>'
		+ '</tr> ';
		$thistr.after(_menuPtrtmp);
	});
	*/
	$("#create_menu").click(function () {
		var $idsCheck = $("table :checkbox");
		var $isnew = false;
		$idsCheck.each(function () {
			var $hidden_name = $(this).parents("tr").find("input[type=hidden]").attr("name");
			if ($hidden_name.indexOf("New") >= 0) $isnew = true; return;
		});
		if ($isnew) {
			alert("请保存后生成!");return false;
		} else {
			$.get('pushmenu/tid/' + $('#tid').val(), {}, function (data) {
				alert(data.error);
			}, 'json');
		}
	});

	$("#remove_menu").click(function () {
		G.ui.tips.confirm_flag('确定要撤销您的自定义菜单吗？',function(){
			$.get('/wechat/removemenu/aid/' + $('#aid').val(), {}, function (d) {
				$.fallr('hide');
				//G.logic.form.tip(d);
				alert(d.error);
			}, 'json');
		});
	});
});
</script>
<?php $this->renderPartial('/_include/footer');?>