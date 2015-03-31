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
<link href='<?php echo $this->_baseUrl?>/static/lib/fullcalendar/fullcalendar.css' rel='stylesheet' />
<link href='<?php echo $this->_baseUrl?>/static/lib/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='<?php echo $this->_baseUrl?>/static/lib/fullcalendar/lib/moment.min.js'></script>
<script src='<?php echo $this->_baseUrl?>/static/lib/fullcalendar/lib/jquery.min.js'></script>
<script src='<?php echo $this->_baseUrl?>/static/lib/fullcalendar/fullcalendar.min.js'></script>

<script>

	$(document).ready(function() {

		$('#calendar').fullCalendar({
			defaultDate: '2015-02-12',
			editable: true,
			eventLimit: true, // allow "more" link when too many events
			events: [
				{
					title: 'All Day Event',
					start: '2015-02-01'
				},
				{
					title: 'Long Event',
					start: '2015-02-07',
					end: '2015-02-10'
				},
				{
					id: 999,
					title: 'Repeating Event',
					start: '2015-02-09T16:00:00'
				},
				{
					id: 999,
					title: 'Repeating Event',
					start: '2015-02-16T16:00:00'
				},
				{
					title: 'Conference',
					start: '2015-02-11',
					end: '2015-02-13'
				},
				{
					title: 'Meeting',
					start: '2015-02-12T10:30:00',
					end: '2015-02-12T12:30:00'
				},
				{
					title: 'Lunch',
					start: '2015-02-12T12:00:00'
				},
				{
					title: 'Meeting',
					start: '2015-02-12T14:30:00'
				},
				{
					title: 'Happy Hour',
					start: '2015-02-12T17:30:00'
				},
				{
					title: 'Dinner',
					start: '2015-02-12T20:00:00'
				},
				{
					title: 'Birthday Party',
					start: '2015-02-13T07:00:00'
				},
				{
					title: 'Click for Google',
					url: 'http://google.com/',
					start: '2015-02-28'
				}
			]
		});
		
	});

</script>
<style>

	body {
		margin: 40px 10px;
		padding: 0;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		font-size: 14px;
	}

	#calendar {
		max-width: 900px;
		margin: 0 auto;
	}

</style>
</head>
<body>
<div id="contentHeader">
	<div class="searchArea">
		<p class="left" >
			<a href="<?php echo $this->createUrl('dailycreate')?>" class="btn btn-success btn-sm">新建工作计划</a>
		</p>
		<div class="right">
		</div>
	</div>
</div>

	<div id='calendar'></div>
	
	
	
</body>
</html>