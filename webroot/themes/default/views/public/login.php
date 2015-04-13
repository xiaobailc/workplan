<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="renderer" content="webkit">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>登录 - 员工日志管理系统</title>
<link rel="stylesheet" type="text/css" href="<?php echo $this->_baseUrl?>/static/admin/css/login-style.css" />
<script type="text/javascript" language="javascript">
    //<![CDATA[
    // show login form in top frame
    if (top != self) {
        window.top.location.href = location;
    }
    //]]>
</script>
</head>
<body>
<div id="login">
  <div class="wrapper">
    <div class="alert error" >&nbsp;</div>
    <div class="logo"><h1>ICNTV工作日志系统</h1></div>
    <div class="form">
      <?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-wrap',
	'enableAjaxValidation'=>true,
)); ?>
      <dl>
        <dt>用户名</dt>
        <dd> <?php echo $form->textField($model,'username', array('class'=>'input-password')); ?> <?php echo $form->error($model,'username'); ?> </dd>
        <dt>密&nbsp;&nbsp;&nbsp;&nbsp;码</dt>
        <dd> <?php echo $form->passwordField($model,'password', array('class'=>'input-password')); ?> <?php echo $form->error($model,'password'); ?> </dd>
        <dd>
          <input type="submit" name="login" class="input-login" value=""/>
          <input type="reset" name="login" class="input-reset" value=""/>
        </dd>
      </dl>
      <?php $this->endWidget(); ?>
    </div>
    <br class="clear-fix"/>
    <div class="copyright">Copyright&copy; <a title="WCPP" target="_blank" href="http://wcpp.icntv.tv">icc.com</a>. All Thrusts Reserved.</div>
  </div>
</div>
</body>
</html>
