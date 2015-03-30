<?php $this->renderPartial('/_include/header');?>

<div id="contentHeader">
	<h3>管理日志</h3>
	<div class="searchArea">
	<p class="left" >
		<a href="<?php echo $this->createUrl('config/index')?>" class="btn btn-success btn-sm"><b>开启/关闭</b></a>
	</p>
	<div class="search right">
		<?php $form = $this->beginWidget('CActiveForm',array('id'=>'searchForm','method'=>'get','action'=>array('admin'),'htmlOptions'=>array('name'=>'xform'))); ?>
		<select name="catalog" id="catalog">
		<option value="">==操作类型==</option>
<?php foreach(XParams::$adminLoggerType as $key=>$val):?>
		<option value="<?php echo $key?>"><?php echo $val?></option>
<?php endforeach?>
		</select>
		用户:
		<input id="username" type="text" name="username" value="" class="txt" size="15"/>
		<input name="searchsubmit" type="submit" value="查询" class="btn btn-success btn-sm"/>
		<?php $form=$this->endWidget(); ?>
		<script type="text/javascript">
$(document).ready(function(){
	$("#catalog").val('<?php echo $this->_gets->getParam('catalog')?>');
	$("#username").val('<?php echo $this->_gets->getParam('username')?>');
});
</script> 
	</div>
	</div>
</div>
<form method="post" action="<?php echo $this->createUrl('batch')?>" name="cpform" >
<table class="table table-bordered">
	<thead>
	<tr class="active">
		<th style="width:75px" >ID</th>
		<th style="width:75px" >类型</th>
		<th style="width:100px" >用户</th>
		<th style="width:" >动作</th>
		<th style="width:150px" >IP</th>
		<th style="width:150px" >操作时间</th>
		<th style="width:100px" >操作</th>
	</tr>
	</thead>
	<?php foreach ($datalist as $row):?>
	<tr class="tb_list">
		<td ><input type="checkbox" name="id[]" value="<?php echo $row->id?>">
		<?php echo $row->id?></td>
		<td ><?php echo XParams::get($row->catalog,'adminLoggerType')?></td>
		<td ><?php echo $row->admin->username?></td>
		<td ><?php echo $row->intro?><br />
		<span style="color:#999"><?php echo $row->url?></span></td>
		<td ><span ><?php echo $row->ip?></span></td>
		<td ><?php echo date('Y-m-d H:i',$row->create_time)?></td>
		<td >
			<a href="<?php echo $this->createUrl('batch',array('command'=>'adminLoggerDelete', 'id'=>$row->id))?>" class="btn btn-danger btn-xs confirmSubmit"><i class="fa fa-trash-o"></i> 删除</a>
		</td>
	</tr>
	<?php endforeach;?>
	<tr class="submit">
		<td colspan="8"><div class="cuspages right">
			<?php $this->widget('CLinkPager',array('pages'=>$pagebar));?>
		</div>
		<div class="fixsel" >
			<input type="checkbox" name="chkall" id="chkall" onclick="checkAll(this.form, 'id')" />
			<label for="chkall">全选</label>
			<select name="command">
			<option value="">选择操作</option>
			<option value="adminLoggerDelete">删除</option>
			</select>
			<input id="submit_maskall" class="btn btn-primary btn-sm confirmSubmit" type="submit" value="提交" name="maskall" />
		</div></td>
	</tr>
</table>
</form>
<?php $this->renderPartial('/_include/footer');?>
