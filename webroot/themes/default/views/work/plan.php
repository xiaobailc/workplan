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
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/lib/fullcalendar/lib/moment.min.js" ></script>
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/js/jquery.min.js" ></script>
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/lib/fullcalendar/fullcalendar.min.js" ></script>
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/lib/fullcalendar/lang-all.js" ></script>

<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/js/jquery.form.js" ></script>
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/admin/js/base.js" ></script>
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/lib/bootstrap/js/bootstrap.min.js" ></script>
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/lib/validationEngine/jquery.validationEngine.min.js" ></script>

<script>
	$(document).ready(function() {
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			defaultDate: '<?php echo date('Y-m-d')?>',
			lang: 'zh-cn',
			editable: true,
			eventLimit: true,
			weekNumbers: true,
			dayClick: function() {
		        alert('a day has been clicked!');
		    },
		    events: {
				url: webUrl+currentScript+'?r=work/dealdata',
				type:'POST',
				data:{type:"get_plan_data"},
				error: function() {
					$('#script-warning').show();
				}
			},
			loading: function(bool) {
				$('#loading').toggle(bool);
			}
		});
		
	});
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
    <div id="contentHeader">
    	<div class="searchArea">
    		<p class="" >
    			<a href="<?php echo $this->createUrl('dailycreate')?>" class="btn btn-success btn-sm">新建工作计划</a>
    		</p>
    		<div class="right">
    		</div>
    	</div>
    </div>
    <div id='script-warning'>载入失败!</div>
    <div id='loading'>载入中...</div>
    <div id='calendar'></div>
<?php $this->renderPartial('/_include/footer');?>