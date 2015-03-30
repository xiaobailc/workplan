<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>错误：<?php echo $code; ?></title>
<link rel="stylesheet" href="<?php echo $this->_theme->baseUrl?>/css/style.css">
</head>
<body>
<div id="error">
  <h1><?php echo $code; ?>错误</h1>
  <div class="message"><?php echo nl2br(CHtml::encode($message)); ?></div>
 <div class="redirect"><a href="<?php echo $redirect?>">返回上页</a>&nbsp;&nbsp; <a>3秒后自动跳转</a></div>
</div>
<script>  
function jumpurl(){  
  location='<?php echo $redirect?>';  
}  
setTimeout('jumpurl()',2000);  
</script>  
</body>
</html>