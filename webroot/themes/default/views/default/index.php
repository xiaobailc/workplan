<!doctype html>
<html>
<head>
<title>iCC管理系统</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="renderer" content="webkit">
<link rel="stylesheet" type="text/css" href="<?php echo $this->_baseUrl?>/static/admin/css/manage.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->_baseUrl?>/static/lib/font-awesome/css/font-awesome.min.css">
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/js/jquery.min.js"></script>
</head>

<body scroll="no">
<div class="header">
	<div class="logo">icc.icntv.tv</div>
	<div class="nav">
		<ul class="nav nav-tabs" role="tablist">
<?php $i = 0; foreach($menu as $key=>$row):?>
			<li index="<?php echo $key ?>">
				<a href="<?php if($key==0){$row['url']='work/daily';} echo $this->createUrl($row['url']) ?>" target="win" >
<?php if(false !== strpos($row['tip'], 'fa')):?>
		<i class="<?php echo $row['tip']?>"></i>
<?php endif;?>
				<?php echo $row['title'] ?>
				</a>
			</li>
<?php $i++;endforeach;?>
		</ul>
	</div>
	<div class="logininfo"><span class="welcome"><img src="<?php echo $this->_baseUrl?>/static/admin/images/user_edit.png" align="absmiddle"> 欢迎, <em><?php echo $this->_xsession['_adminUserName']?></em> </span> <a href="<?php echo $this->createUrl('admin/ownerUpdate')?>" target="win">修改密码</a> <a href="<?php echo $this->createUrl('public/logout')?>" target="_top">退出登录</a></div>
</div>
<div class="main" id="main">
	<div class="mainA">
		<div id="leftmenu" class="menu">
<?php foreach($menu as $key=>$row):?>
			<ul index="<?php echo $key ?>" class="left_menu">
<?php foreach((array)$row['child'] as $k=>$rc):?>
				<li index="<?php echo $k ?>"><a href="<?php echo $this->createUrl($rc['url'])?>" target="win"><?php echo $rc['title'] ?></a></li>
<?php endforeach;?>
			</ul>
<?php endforeach;?>
		</div>
	</div>
	<div class="mainB" id="mainB">
		<iframe src="javascript:void(0)" name="win" id="win" width="100%" frameborder="0" onload="javascript:reSetIframe()"></iframe>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	var s=document.location.hash;
	if(s==undefined||s==""){s="#0_0";}
	s=s.slice(1);
	var navIndex=s.split("_");
		
	$(".logo").click(function(){
		location.href="<?php echo $this->createUrl('default/index')?>";
	});
	$(".nav").find("li:eq("+navIndex[0]+")").addClass("active");
	var targetLink=$(".menu").find("ul").hide().end()
		.find(".left_menu:eq("+navIndex[0]+")").show()
		.find("li:eq("+navIndex[1]+")").addClass("active")
		.find("a").attr("href");
	$("#win").attr("src",targetLink);
	$(".nav").find("li").click(function(){
		$(this).parent().find("li").removeClass("active").end().end()
			 .addClass("active");
		var index=$(this).attr("index");
		$(".menu").find(".left_menu").hide();
		$(".menu").find(".left_menu:eq("+index+")").show()
			.find("li").removeClass("active").first().addClass("active");
		document.location.hash=index+"_0";
	});
	$(".left_menu").find("li").click(function(){
		$(this).parent().find("li").removeClass("active").end().end()
			.addClass("active");
		document.location.hash=$(this).parent().attr("index")+"_"+$(this).attr("index");
	});
});

function reSetIframe(){
    var iframe = document.getElementById("win");
    try{
        var bHeight = iframe.contentWindow.document.body.scrollHeight;
        var dHeight = iframe.contentWindow.document.documentElement.scrollHeight;
        var height = Math.max(bHeight, dHeight);
        iframe.height = height;
    }catch (ex){}
}
</script>
</body>
</html>
