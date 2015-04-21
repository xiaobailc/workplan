<style type="text/css">
.datetimepicker{
    float:left;
}
</style>
<h3><?php echo $date_time;?>日报</h3>
<?php $form = $this->beginWidget('CActiveForm',array('id'=>'xform','htmlOptions'=>array('name'=>'xform', 'enctype'=>'multipart/form-data'))); ?>
<input type="hidden" value="<?php echo $date_time;?>" name="time_date"/>
<table class="table table-bordered table-condensed">
    <thead>
        <tr class="active">
            <th style="width: 50px" >编号</th>
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
            <th class="no">1</th>
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
            <th class="no"><?php echo $k+1?></th>
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
        $('.datetimepicker').datetimepicker({
            pickDate: false,
            pickSeconds: false
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
