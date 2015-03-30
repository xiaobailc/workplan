<?php $this->renderPartial('/_include/header');?>

<div id="contentHeader">
	<h3>终端版本</h3>
	<div class="searchArea">
	<p class="left" >
		<a href="<?php echo $this->createUrl('create')?>" class="btn btn-success btn-sm"><b>添加终端版本</b></a>
	</p>
	<div class="search right">
<?php //$form = $this->beginWidget('CActiveForm',array('id'=>'searchForm','method'=>'get','action'=>array('index'),'htmlOptions'=>array('name'=>'xform'))); ?>
<?php echo CHtml::form(array('index'), 'get', array('class'=>'form-inline','name'=>'xfrom'));?>
		<select name="group" class="form-control input-sm">
			<option value="">=全部版本=</option>
<?php foreach($grouplist as $g):?>
			<option value="<?php echo $g['group']?>"><?php echo $g['group']?></option>
<?php endforeach;?>
		</select>
		<input type="submit" value="搜索" class="btn btn-primary btn-sm" />
<?php echo CHtml::endForm();?>
	</div>
	</div>
</div>
<table class="table table-bordered table-condensed">
	<thead>
	<tr class="active">
		<th style="width: 150px" >版本名称 </th>
		<th style="width: 150px" >版本代号 </th>
		<th style="width: " >所属公司 </th>
		<th style="width: 150px" >类型</th>
		<th style="width: 200px" >创建时间</th>
		<th style="width: 200px" >更新时间</th>
		<th style="width: 75px" >状态</th>
		<th style="width: 125px" >操作</th>
	</tr>
	</thead>
	<?php foreach ($datalist as $row):?>
	<tr class="tb_list">
		<td style="text-align:center;">
		<img width="100px" src="<?php echo $row->headface_url?>" /><br />
		<b><?php echo $row->name?></b>
		</td>
		<td ><?php echo $row->partial?></td>
		<td ><?php echo $row->group?></td>
		<td ><?php $typearr = array('1'=>'测试版','2'=>'正式版','3'=>'其他版本'); echo $typearr[$row->type];?></td>
		<td ><?php echo date('Y-m-d H:i:s', $row->create_time)?></td>
		<td ><?php echo date('Y-m-d H:i:s', $row->update_time)?></td>
		<td ><?php echo ($row->status_is == 'Y') ? '启用' : '禁用' ?></td>
		<td class="op">
		<a href="<?php echo	$this->createUrl('update',array('id'=>$row->id))?>" class="btn btn-default btn-xs btn-block"><i class="fa fa-pencil fa-fw"></i>编辑</a>
		<a href="<?php echo	$this->createUrl('batch',array('command'=>'delete','id'=>$row->id))?>" class="btn btn-danger btn-xs btn-block confirmSubmit"><i class="fa fa-trash-o fa-fw"></i>删除</a>
		<a onclick="parent.location.href='<?php echo $this->createUrl('default/tv',array('tid'=>$row->id))?>'" class="btn btn-primary btn-sm btn-xs btn-block"><i class="fa fa-cog fa-fw"></i>管理</a>
		</td>
	</tr>
	<?php endforeach;?>
	<tr>
		<td colspan="8"><div class="cuspages right">
			<?php $this->widget('CLinkPager',array('pages'=>$pagebar));?>
		</div></td>
	</tr>
</table>
<style type="text/css">
.op a{
	margin:0 auto;
	width:75px;
}
</style>
<?php $this->renderPartial('/_include/footer');?>
