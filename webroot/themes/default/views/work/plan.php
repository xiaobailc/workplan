<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>iCC管理系统</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta content="icc.com." name="Copyright">
<script>
	var webUrl = '<?php echo Yii::app()->request->getHostInfo()?>';
	var staticPath = '<?php echo $this->_baseUrl?>/static/';
	var currentScript = '<?php echo Yii::app()->request->getScriptUrl()?>';
</script>

<link rel="stylesheet" type="text/css" href="<?php echo $this->_baseUrl?>/static/lib/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->_baseUrl?>/static/lib/font-awesome/css/font-awesome.min.css">

<link rel="stylesheet" type="text/css" href='<?php echo $this->_baseUrl?>/static/admin/css/common.css'>

<link rel="stylesheet" type="text/css" href='<?php echo $this->_baseUrl?>/static/lib/fullcalendar/fullcalendar.css' />
<link rel="stylesheet" type="text/css" href='<?php echo $this->_baseUrl?>/static/lib/fullcalendar/fullcalendar.print.css' media='print' />
<link rel="stylesheet" type="text/css" href='<?php echo $this->_baseUrl?>/static/lib/datetimepicker/css/bootstrap-datetimepicker.min.css' />

<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/lib/fullcalendar/lib/moment.min.js" ></script>
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/js/jquery.min.js" ></script>
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/lib/fullcalendar/fullcalendar.min.js" ></script>
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/lib/fullcalendar/lang-all.js" ></script>
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/lib/datetimepicker/js/bootstrap-datetimepicker.min.js" ></script>

<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/js/jquery.form.js" ></script>
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/admin/js/base.js" ></script>
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/lib/bootstrap/js/bootstrap.min.js" ></script>
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/lib/validationEngine/jquery.validationEngine.min.js" ></script>
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/lib/bootbox.js" ></script>

<script>
	
</script>
<style>
    #script-warning {
		display: none;
		background: #eee;
		border-bottom: 1px solid #ddd;
		padding: 0 10px;
		line-height: 40px;
		text-align: center;
		font-weight: bold;
		font-size: 12px;
		color: red;
	}
	#loading {
		display: none;
		position: absolute;
		top: 10px;
		right: 10px;
	}
	#calendar {
		max-width: 900px;
		margin: 0 auto;
	}
</style>
</head>
<body>
<div id="append_parent"></div>
<div class="" id="cpcontainer">

    <div id='script-warning'>载入失败!</div>
    <div id='loading'>载入中...</div>
    <div id='calendar'></div>
    <!-- 新建工作计划框 -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalTitle"></h4>
      </div>
      <div class="modal-body">
       <form>
      <div class="form-group">
        <label for="plantitle">工作计划</label>
        <textarea class="form-control" id="InputTitle"></textarea>
      </div>
      <div class="form-group">
        <label for="planstart">开始时间</label>
        <input type="text" id="InputStart" class="form-control" disabled></input>
<!--           <div class="datetimepicker" class="input-append date">
            <input data-format="yyyy-MM-dd hh:mm:ss" type="text" id="InputStart"></input>
            <span class="add-on">
              <i data-time-icon="fa fa-clock-o" data-date-icon="fa fa-clock-o">
              </i>
            </span>
          </div> -->
      </div>
      <div class="form-group">
        <label for="planstart">结束时间</label>
          <div class="datetimepicker" class="input-append date">
            <input data-format="yyyy-MM-dd hh:mm:ss" type="text" id="InputEnd"></input>
            <span class="add-on">
              <i data-time-icon="fa fa-clock-o" data-date-icon="fa fa-clock-o">
              </i>
            </span>
          </div>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" name="allDay" id="InputAllDay"> 全天计划
        </label>
      </div>
    </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary" onclick="plansubmit()">提交</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(function() {
	function createplan(moment){
		var data_time = moment.format();
		$('#myModal').modal();
		$('#myModalTitle').html(data_time);
		$('#InputTitle').val('');
		$('#InputStart').val(data_time);
		$('#InputEnd').val('');
		$('#InputAllDay').attr("checked","checked");
	}
	
	$('#calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		defaultDate: '<?php echo date('Y-m-d')?>',
		lang: 'zh-cn',
		//editable: true,
		eventLimit: true,
		weekNumbers: true,
		/*
		dayClick: function(data,jsEvent,view) {
			//小于当日-返回
			//大于等于当日-新建工作计划
		    createplan(data);
	    },*/
	    events: {
			url: webUrl+currentScript+'?r=work/dealdata&type=get_plan_data',
			error: function() {
				$('#script-warning').show();
			}
		},
		loading: function(bool) {
			$('#loading').toggle(bool);
		}
	});

	$('.datetimepicker').datetimepicker({
	      language: 'pt-BR'
	});
});

function plansubmit(){
	if($('#InputTitle').val()=='' || $('#InputStart').val()==''){
		alert('计划不能为空');
		return;
	}
	$.ajax({
		url: webUrl+currentScript+'?r=work/dealdata',
		data: {
			type:"post_plan_data",
			title:$('#InputTitle').val(),
			start:$('#InputStart').val(),
			end:$('#InputEnd').val(),
			allDay:$('#InputAllDay').is(':checked')
		},
		type: 'POST',
		dataType: 'json',
		success: function(data, textStatus, jqXHR) {
			//bootbox.alert("<br /><pre>"+data+"</pre>");
			if(data.success){
			    $('#myModal').modal('hide');
			    location.href = webUrl+currentScript+'?r=work/plan';
			}
			else{
				alert(data.success);
			}
			return;
		},
		error: function(jqXHR, textStatus, errorThrown) {
			alert((jqXHR.responseJSON ? jqXHR.responseJSON.message : 'Error') + '\n\n' + jqXHR.status + (errorThrown ? ' ' + errorThrown : ''));
		}
	});
}
</script>
<?php $this->renderPartial('/_include/footer');?>