<?php $this->renderPartial('/_include/header')?>

<div id="contentHeader">
  <h3>数据库管理</h3>
  <div class="searchArea">
    <p class="left" >
      <a href="<?php echo $this->createUrl('index')?>" class="btn btn-info btn-sm">常规管理</a>
      <a href="<?php echo $this->createUrl('query')?>" class="btn btn-success btn-sm"><span>执行SQL</a>
      <a href="<?php echo $this->createUrl('database/export')?>" class="btn btn-success btn-sm">数据库备份</a>
      <a href="<?php echo $this->createUrl('database/import')?>" class="btn btn-success btn-sm">数据库还原</a>
    </p>
    <div class="search right"> </div>
  </div>
</div>
<form action="<?php echo $this->createUrl('database/doQuery')?>" method="post" id="queryForm">

	<p class="text-left">当前数据库尺寸：<?php echo XUtils::byteFormat($dataSize)?></p>
  <table cellspacing="0" cellpadding="0" class="table table-bordered table-condensed">
    <thead>
      <tr class="active">
        <th style="width:5%">&nbsp;</th>
        <th>名称</th>
        <th style="width:10%">类型</th>
        <th style="width:15%">字符集</th>
        <th style="width:8%">记录数</th>
        <th style="width:8%">大小</th>
        <th style="width:8%">碎片</th>
        <th style="width:15%">注释</th>
      </tr>
    </thead>
    <?php foreach((array)$dataList as $row ):?>
    <tr class="tb_list">
      <td><input type="checkbox" name="table[]" value="<?php echo $row['Name']?>" id="<?php echo $row['Name']?>"></td>
      <td><label for="<?php echo $row['Name']?>"><?php echo $row['Name']?></label></td>
      <td><?php echo $row['Engine']?></td>
      <td><?php echo $row['Collation']?></td>
      <td><?php echo $row['Rows']?></td>
      <td><?php echo XUtils::byteFormat($row['Data_length'])?></td>
      <td><?php echo $row['Data_free']?></td>
      <td><?php echo $row['Comment']?></td>
    </tr>
    <?php endforeach?>
    <tr class="submit">
      <td colspan="8">&nbsp;
        <input type="checkbox" id="chkall" name="chkall" onclick="checkAll(this.form, 'table')">
        全选
        <select name="command">
          <option value="optimzeTable">优化表</option>
          <option value="showTable">查看表结构</option>
          <option value="checkTable">检查</option>
          <option value="analyzeTable">分析</option>
          <option value="repairTable">修复</option>
        </select>
        <input name="submit" type="submit" id="submit" value="执行" class="btn btn-primary btn-sm"/></td>
    </tr>
  </table>
</form>
<div id="_tips"></div>
<script type='text/javascript'>
<!--
$('#queryForm').ajaxForm({
    beforeSubmit: function() {
    },
    success: function(html) {
    	if(html.length > 0){
			$("#_tips").html(html);
    	}
    }
});
//-->
</script>
<?php $this->renderPartial('/_include/footer')?>
