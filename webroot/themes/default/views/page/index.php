<?php $this->renderPartial('/_include/header');?>

<div id="contentHeader">
  <h3>单页</h3>
  <div class="searchArea">
    <ul class="action left" >
      <li class="current"><a href="<?php echo $this->createUrl('create')?>" class="actionBtn"><span>添加</span></a></li>
    </ul>
    <div class="search right">
      <?php $form = $this->beginWidget('CActiveForm',array('id'=>'searchForm','method'=>'get','action'=>array('page'),'htmlOptions'=>array('name'=>'xform'))); ?>
      名称
      <input id="title" type="text" name="title" value="" class="txt" size="15"/>
      别名
      <input id="titleAlias" type="text" name="titleAlias" value="" class="txt" size="15"/>
      <input name="searchsubmit" type="submit" value="查询" class="btn btn-primary btn-sm"/>
      <script type="text/javascript">
$(function(){
	$("#xform").validationEngine();	
});
</script>
      <?php $form=$this->endWidget(); ?>
      <script type="text/javascript">
$(document).ready(function(){
	$("#titleAlias").val('<?php echo Yii::app()->request->getParam('titleAlias')?>');
	$("#title").val('<?php echo Yii::app()->request->getParam('title')?>');
});
</script> </div>
  </div>
</div>
<table class="content_list">
  <form method="post" action="<?php echo $this->createUrl('batch')?>" name="cpform" >
    <tr class="tb_header">
      <th width="8%">ID</th>
      <th width="25%">标题</th>
      <th width="15%">添加时间</th>
      <th >操作</th>
    </tr>
    <?php foreach ($datalist as $row):?>
    <tr class="tb_list">
      <td ><input type="checkbox" name="id[]" value="<?php echo $row->id?>">
        <?php echo $row->id?></td>
      <td ><?php echo $row->title?><br />
        <?php echo $row->title_alias?></td>
      <td ><?php echo date('Y-m-d H:i',$row->create_time)?></td>
      <td ><a href="<?php echo  $this->createUrl('update',array('id'=>$row->id))?>"><img src="<?php echo $this->_baseUrl?>/static/admin/images/update.png" align="absmiddle" /></a>&nbsp;&nbsp;<a href="<?php echo  $this->createUrl('batch',array('command'=>'delete', 'id'=>$row->id))?>" class="confirmSubmit"><img src="<?php echo $this->_baseUrl?>/static/admin/images/delete.png" align="absmiddle" /></a></td>
    </tr>
    <?php endforeach;?>
    <tr>
      <td colspan="5"><div class="cuspages right">
          <?php $this->widget('CLinkPager',array('pages'=>$pagebar));?>
        </div>
        <div class="fixsel" >
          <input type="checkbox" name="chkall" id="chkall" onclick="checkAll(this.form, 'id')" />
          <label for="chkall">全选</label>
          <select name="command">
            <option value="">选择操作</option>
            <option value="delete">删除</option>
          </select>
          <input id="submit_maskall" class="btn btn-primary btn-sm confirmSubmit" type="submit" value="提交" name="maskall" />
        </div></td>
    </tr>
  </form>
</table>
<?php $this->renderPartial('/_include/footer');?>
