<style type="text/css">
.datetimepicker{
    float:left;
}
</style>
<h3><?php echo $date_time;?>日报</h3>
<?php $form = $this->beginWidget('CActiveForm',array('id'=>'xform','htmlOptions'=>array('name'=>'xform','class'=>'form-inline', 'enctype'=>'multipart/form-data'))); ?>
<input type="hidden" value="<?php echo $date_time;?>" name="time_date"/>
<table class="table table-bordered table-condensed">
    <thead>
        <tr class="active">
            <th style="width: 50px" >编号</th>
            <th style="width: 125px" >事项</th>
            <th style="" >内容</th>
            <th style="width: 125px" >结果</th>
            <th style="width: 350px" >时间</th>
            <th style="width: 100px" >操作</th>
        </tr>
    </thead>
    <tbody>
<?php if(!isset($report_info)):?>
        <tr>
            <th class="no">1</th>
            <td><input type="text" name="Daily[0][type]" class="validate[required]" style="width: 100%"/></td>
            <td><input type="text" name="Daily[0][content]" class="validate[required]" style="width: 100%"/></td>
            <td><input type="text" name="Daily[0][result]" class="validate[required]" style="width: 100%"/></td>
            <td>
            <div class="form-group">
                <label for="timestart_0" class="control-label"></label>
                <div class="input-group date form_time" data-date="" data-date-format="hh:ii" data-link-field="timestart_0" data-link-format="hh:ii">
                    <input class="form-control validate[required]" type="text" size="5"  value="" id="timestart_0" name="Daily[0][timestart]" readonly>
                    <span class="input-group-addon"><i class="glyphicon glyphicon-time fa fa-clock-o"></i></span>
                </div> —
            </div>
            <div class="form-group">
                <label for="timeend_0" class="control-label"></label>
                <div class="input-group date form_time" data-date="" data-date-format="hh:ii" data-link-field="timeend_0" data-link-format="hh:ii">
                    <input class="form-control validate[required]" type="text" size="5"  value="" id="timeend_0" name="Daily[0][timeend]" readonly>
                    <span class="input-group-addon"><i class="glyphicon glyphicon-time fa fa-clock-o"></i></span>
                </div>
            </div>
            </td>
            <td class="group-btn">
                <button class="btn btn-danger btn-xs confirmSubmit" onclick="removeone(this)"><i class="fa fa-trash-o"></i> 删除</button>
            </td>
        </tr>
<?php else:?>
<?php foreach ($report_info as $k=>$v):?>
        <tr>
            <th class="no"><?php echo $k+1?></th>
            <td><input type="text" name="Daily[<?php echo $k?>][type]" class="validate[required]" style="width: 100%" value="<?php echo $v['type']?>" /></td>
            <td><input type="text" name="Daily[<?php echo $k?>][content]" class="validate[required]" style="width: 100%" value="<?php echo $v['content']?>" /></td>
            <td><input type="text" name="Daily[<?php echo $k?>][result]" class="validate[required]" style="width: 100%" value="<?php echo $v['result']?>" /></td>
            <td>
            <div class="form-group">
                <label for="timestart_<?php echo $k?>" class="control-label"></label>
                <div class="input-group date form_time" data-date="" data-date-format="hh:ii" data-link-field="timestart_<?php echo $k?>" data-link-format="hh:ii">
                    <input class="form-control validate[required]" type="text" size="5"  value="<?php echo $v['timestart']?>" id="timestart_<?php echo $k?>" name="Daily[<?php echo $k?>][timestart]" readonly>
                    <span class="input-group-addon"><i class="glyphicon glyphicon-time fa fa-clock-o"></i></span>
                </div> —
            </div>
            <div class="form-group">
                <label for="timeend_<?php echo $k?>" class="control-label"></label>
                <div class="input-group date form_time" data-date="" data-date-format="hh:ii" data-link-field="timeend_<?php echo $k?>" data-link-format="hh:ii">
                    <input class="form-control validate[required]" type="text" size="5"  value="<?php echo $v['timeend']?>" id="timeend_<?php echo $k?>" name="Daily[<?php echo $k?>][timeend]" readonly>
                    <span class="input-group-addon"><i class="glyphicon glyphicon-time fa fa-clock-o"></i></span>
                </div>
            </div>
            </td>
            <td class="group-btn">
                <button class="btn btn-danger btn-xs confirmSubmit" onclick="removeone(this)"><i class="fa fa-trash-o"></i> 删除</button>
            </td>
        </tr>
<?php endforeach;?>
<?php endif;?>
        <tr id="submit">
            <td colspan="4">
            <input type="button" id="addone" value="添加一行" class="btn btn-success btn-sm" tabindex="3" />
            <input type="submit" id="editsubmit" value="保存" class="btn btn-primary btn-sm" tabindex="3" />
            </td>
            <td><span id="total_time"></span></td>
            <td><input type="hidden" value="" /></td>
        </tr>
    </tbody>
</table>
<?php $form=$this->endWidget(); ?>
<table style="display:none" id="rowtemplate">
<tr>
    <th class="no">NNN</th>
    <td><input type="text" name="NewDaily[XXX][type]" class="validate[required]" style="width: 100%"/></td>
    <td><input type="text" name="NewDaily[XXX][content]" class="validate[required]" style="width: 100%"/></td>
    <td><input type="text" name="NewDaily[XXX][result]" class="validate[required]" style="width: 100%"/></td>
    <td>
    <div class="form-group">
        <label for="new_timestart_XXX" class="control-label"></label>
        <div class="input-group date form_time" data-date="" data-date-format="hh:ii" data-link-field="new_timestart_XXX" data-link-format="hh:ii">
            <input class="form-control validate[required]" type="text" size="5" id="new_timestart_XXX" name="NewDaily[XXX][timestart]" readonly>
            <span class="input-group-addon"><i class="glyphicon glyphicon-time fa fa-clock-o"></i></span>
        </div> —
    </div>
    <div class="form-group">
        <label for="new_timeend_XXX" class="control-label"></label>
        <div class="input-group date form_time" data-date="" data-date-format="hh:ii" data-link-field="new_timeend_XXX" data-link-format="hh:ii">
            <input class="form-control validate[required]" type="text" size="5" id="new_timeend_XXX" name="NewDaily[XXX][timeend]" readonly>
            <span class="input-group-addon"><i class="glyphicon glyphicon-time fa fa-clock-o"></i></span>
        </div>
    </div>
    </td>
    <td class="group-btn">
        <button class="btn btn-danger btn-xs confirmSubmit" onclick="removeone(this)"><i class="fa fa-trash-o"></i> 删除</button>
    </td>
</tr>
</table>


<script type="text/javascript">
$('.form_time').datetimepicker({
    language:  'fr',
    weekStart: 1,
    todayBtn:  1,
	autoclose: 1,
	todayHighlight: 1,
	startView: 1,
	minView: 0,
	maxView: 1,
	forceParse: 0
});
$(function(){
    var menu_index = 0;
    $("#xform").validationEngine();
    $('#addone').click(function(){
        var now_no = 0;
        $('.table .no').each(function(){
            now_no = $(this).text();
        });
        now_no = parseInt(now_no)+1;
        menu_index++;
        var dom = $("#rowtemplate").children().html();
        dom = dom.replace(/XXX/g,menu_index);
        dom = dom.replace(/NNN/g,now_no);
        //alert(dom);return;
        $('#submit').before(dom);
        $('.form_time').datetimepicker({
            language:  'fr',
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 1,
            minView: 0,
            maxView: 1,
            forceParse: 0
        });
    });
    $('.datetimepicker').on('changeDate', function(e) {
        //console.log(e.date.toString());
        //console.log(e.localDate.toString());
    	var d = e.date;
    });
});

function removeone(e){
    //alert(1);return;
    $(e).parent().parent().nextAll().each(function(){
        if($(this).find('.no').length!=0){
            var temp_no = $(this).find('.no').text();
            $(this).find('.no').text(parseInt(temp_no)-1);
        }
    });
    $(e).parent().parent().remove();
}
</script>
