<?php echo $this->renderPartial('/_include/header')?>

<div id="contentHeader">
  <h3>数据库备份</h3>
  <div class="searchArea">
    <p class="left" >
      <a href="<?php echo $this->createUrl('database/export')?>" class="btn btn-info btn-sm">数据库备份</a>
      <a href="<?php echo $this->createUrl('database/import')?>" class="btn btn-success btn-sm">数据库还原</a>
      <a href="<?php echo $this->createUrl('index')?>" class="btn btn-success btn-sm">常规管理</a>
      <a href="<?php echo $this->createUrl('query')?>" class="btn btn-success btn-sm">执行SQL</a>
    </p>
    <div class="search right"> </div>
  </div>
</div>
<form action="<?php echo $this->createUrl('database/doExport')?>" method="post">
  <table class="content_list">
    <tr>
      <td class="tb_title">分卷大小：</td>
    </tr>
    <tr >
      <td ><input type="hidden" name="tabletype" value="icc" id="icc">
        大小
        <input name="sizelimit" type="text" id="sizelimit" value="2048" />
        kb<br /></td>
    </tr>
    <tr>
      <td class="tb_title">建表语句格式：</td>
    </tr>
    <tr >
      <td ><input type="radio" name="sqlcompat" value="" checked="">
        默认 &nbsp;
        <input type="radio" name="sqlcompat" value="MYSQL40">
        MySQL 3.23/4.0.x &nbsp;
        <input type="radio" name="sqlcompat" value="MYSQL41">
        MySQL 4.1.x/5.x &nbsp;</td>
    </tr>
    <tr>
      <td class="tb_title">强制字符集：</td>
    </tr>
    <tr >
      <td ><input type="radio" name="sqlcharset" value="" checked="">
        默认&nbsp;
        <input type="radio" name="sqlcharset" value="latin1">
        LATIN1 &nbsp;
        <input type="radio" name="sqlcharset" value="utf8">
        UTF-8 </td>
    </tr>
    <tr class="submit">
      <td ><input type="submit" name="dosubmit" value="开始备份" class="btn btn-primary btn-sm" tabindex="3" id="dosubmit" /></td>
    </tr>
  </table>
</form>
<?php echo $this->renderPartial('/_include/footer')?> 