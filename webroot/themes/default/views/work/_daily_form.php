<style type="text/css">
.datetimepicker{
	float:left;
}
</style>
<h3><?php echo date('Y-m-d');?>日报</h3>
<?php $form = $this->beginWidget('CActiveForm',array('id'=>'xform','htmlOptions'=>array('name'=>'xform', 'enctype'=>'multipart/form-data'))); ?>
<input type="hidden" value="<?php echo date('Y-m-d');?>" name="time_date"/>
<table class="table table-bordered table-condensed">
	<thead>
		<tr class="active">
			<th style="width: 175px" >事项</th>
			<th style="" >内容</th>
			<th style="width: 125px" >结果</th>
			<th style="width: 250px" >时间</th>
			<th style="width: 100px" >操作</th>
		</tr>
	</thead>
	<tbody>
<?php if(!isset($report_info)):?>
		<tr>
			<td><input type="text" name="Daily[0][type]" class="validate[required]" size="15"/></td>
			<td><input type="text" name="Daily[0][content]" class="validate[required]" size="60"/></td>
			<td><input type="text" name="Daily[0][result]" class="validate[required]" size="10"/></td>
			<td>
			<div class="datetimepicker" class="input-append date">
                <input data-format="hh:mm" type="text" name="Daily[0][timestart]" class="validate[required]" size="5" />
                <span class="add-on"><i data-time-icon="fa fa-clock-o" data-date-icon="fa fa-clock-o"></i></span> -
            </div>
            <div class="datetimepicker" class="input-append date">
                - <input data-format="hh:mm" type="text" name="Daily[0][timeend]" class="validate[required]" size="5" />
                <span class="add-on"><i data-time-icon="fa fa-clock-o" data-date-icon="fa fa-clock-o"></i></span>
            </div>
			</td>
			<td class="group-btn">
				<button class="btn btn-danger btn-xs confirmSubmit" onclick="removeone(this)"><i class="fa fa-trash-o"></i> 删除</button>
			</td>
		</tr>
<?php else:?>
<?php foreach ($report_info as $k=>$v):?>
		<tr>
			<td><input type="text" name="Daily[<?php echo $k?>][type]" class="validate[required]" size="15" value="<?php echo $v['type']?>" /></td>
			<td><input type="text" name="Daily[<?php echo $k?>][content]" class="validate[required]" size="60" value="<?php echo $v['content']?>" /></td>
			<td><input type="text" name="Daily[<?php echo $k?>][result]" class="validate[required]" size="10" value="<?php echo $v['result']?>" /></td>
			<td>
			<div class="datetimepicker" class="input-append date">
                <input data-format="hh:mm" type="text" name="Daily[<?php echo $k?>][timestart]" class="validate[required]" size="5" value="<?php echo $v['timestart']?>" />
                <span class="add-on"><i data-time-icon="fa fa-clock-o" data-date-icon="fa fa-clock-o"></i></span> -
            </div>
            <div class="datetimepicker" class="input-append date">
                - <input data-format="hh:mm" type="text" name="Daily[<?php echo $k?>][timeend]" class="validate[required]" size="5" value="<?php echo $v['timeend']?>" />
                <span class="add-on"><i data-time-icon="fa fa-clock-o" data-date-icon="fa fa-clock-o"></i></span>
            </div>
			</td>
			<td class="group-btn">
				<button class="btn btn-danger btn-xs confirmSubmit" onclick="removeone(this)"><i class="fa fa-trash-o"></i> 删除</button>
			</td>
		</tr>
<?php endforeach;?>
<?php endif;?>
		<tr id="submit">
            <td colspan="6">
            <input type="button" id="addone" value="添加一行" class="btn btn-success btn-sm" tabindex="3" />
            <input type="submit" id="editsubmit" value="提交" class="btn btn-primary btn-sm" tabindex="3" />
            </td>
        </tr>
	</tbody>
</table>
<?php $form=$this->endWidget(); ?>
<table style="display:none" id="rowtemplate">
<tr>
	<td><input type="text" name="NewDaily[XXX][type]" class="validate[required]" size="15"/></td>
	<td><input type="text" name="NewDaily[XXX][content]" class="validate[required]" size="60"/></td>
	<td><input type="text" name="NewDaily[XXX][result]" class="validate[required]" size="10"/></td>
	<td>
	<div class="datetimepicker" class="input-append date">
        <input data-format="hh:mm" type="text" name="NewDaily[XXX][timestart]" class="validate[required]" size="5" />
        <span class="add-on"><i data-time-icon="fa fa-clock-o" data-date-icon="fa fa-clock-o"></i></span> -
    </div>
    <div class="datetimepicker" class="input-append date">
        - <input data-format="hh:mm" type="text" name="NewDaily[XXX][timeend]" class="validate[required]" size="5" />
        <span class="add-on"><i data-time-icon="fa fa-clock-o" data-date-icon="fa fa-clock-o"></i></span>
    </div>
	</td>
	<td class="group-btn">
		<button class="btn btn-danger btn-xs confirmSubmit" onclick="removeone(this)"><i class="fa fa-trash-o"></i> 删除</button>
	</td>
</tr>
</table>


<script type="text/javascript">
$(function(){
	var menu_index = 0;
	$("#xform").validationEngine();
	$('.datetimepicker').datetimepicker({
      pickDate: false,
      pickSeconds: false
    });
    $('#addone').click(function(){
    	menu_index++;
    	var dom = $("#rowtemplate").children().html();
    	dom = dom.replace(/XXX/g,menu_index); 
    	//alert(dom);return;
    	$('#submit').before(dom);
    	$('.datetimepicker').datetimepicker({
    	    pickDate: false,
    	    pickSeconds: false
    	});
    });
});

function removeone(e){
	//alert(1);return;
	$(e).parent().parent().remove();
}
</script>
