<?php echo $this->renderPartial('/_include/header')?>

<div id="contentHeader">
  <h3>数据库管理</h3>
  <div class="searchArea">
    <p class="left" >
      <a href="<?php echo $this->createUrl('query')?>"  class="btn btn-info btn-sm">执行SQL</a>
      <a href="<?php echo $this->createUrl('index')?>" class="btn btn-success btn-sm">常规管理</a>
      <a href="<?php echo $this->createUrl('database/export')?>" class="btn btn-success btn-sm">数据库备份</a>
      <a href="<?php echo $this->createUrl('database/import')?>" class="btn btn-success btn-sm">数据库还原</a>
    </p>
    <div class="search right"> </div>
  </div>
</div>
<form action="<?php echo $this->createUrl('execute')?>" method="post" id="queryForm">
  <table class="content_list">
    <tr>
      <td class="tb_title">输入SQL：</td>
    </tr>
    <tr >
      <td ><textarea name="command" cols="100" rows="8" id="command"  ></textarea></td>
    </tr>
    <tr >
      <td >每行一条SQL语句</td>
    </tr>
    <tr class="submit">
      <td ><input name="execute" type="submit" id="execute" value="提交" class="btn btn-primary btn-sm" /></td>
    </tr>
  </table>
</form>
<div id="_tips"></div>
<script type='text/javascript'>
<!--
$('#queryForm').ajaxForm({
    beforeSubmit: function() {
		if($("#command").val() == ''){
			alert("SQL不能为空");
			return false;
		}
    },
    success: function(html) {
    	if(html.length > 0){
			$("#_tips").html(html);
    	}
    }
});
//-->
</script> 
<?php echo $this->renderPartial('/_include/footer')?>