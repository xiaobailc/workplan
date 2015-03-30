<?php $this->renderPartial('/_include/header');?>

<div id="contentHeader">
  <h3>用户组</h3>
  <div class="searchArea">
	<p class="left" >
	 <a href="<?php echo $this->createUrl('groupCreate')?>" class="btn btn-success btn-sm"><b>添加</b></a>
	</p>
	<div class="search right">
	</div>
  </div>
</div>

<table class="table table-bordered table-condensed">
	<thead>
	<tr class="active">
		<th style="width: 50px" >ID</th>
		<th style="width: " >用户组 </th>
		<th style="width: 200px" >添加时间</th>
		<th style="width: 75px" >状态</th>
		<th style="width: 250px" >操作</th>
	</tr>
	</thead>
	<?php foreach ($datalist as $row):?>
	<tr class="tb_list">
		<td >
		<?php echo $row->id?></td>
		<td ><?php echo $row->group_name?></td>
		<td ><?php echo date('Y-m-d H:i',$row->create_time)?></td>
		<td ><?php echo ($row->status_is=='Y')?'启用':'禁用'?></td>
		<td >
<?php if(!in_array($row->id, array(1,2))):?>
			<a href="<?php echo  $this->createUrl('groupUpdate',array('id'=>$row->id))?>" class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> 编辑</a>
<?php if($row->status_is=='Y'):?>
			<a href="<?php echo  $this->createUrl('batch',array('command'=>'groupBan', 'id'=>$row->id))?>" class="btn btn-warning btn-xs"><i class="fa fa-ban"></i> 禁用</a>
<?php else:?>
			<a href="<?php echo  $this->createUrl('batch',array('command'=>'groupAllow', 'id'=>$row->id))?>" class="btn btn-success btn-xs"><i class="fa fa-check"></i> 启用</a>
<?php endif;?>
			<a href="<?php echo  $this->createUrl('batch',array('command'=>'groupDelete', 'id'=>$row->id))?>" class="btn btn-danger btn-xs confirmSubmit"><i class="fa fa-trash-o"></i> 删除</a>
<?php else:?>
			系统组
<?php endif?>
		</td>
	</tr>
	<?php endforeach;?>
	<tr>
		<td colspan="5"><div class="cuspages right">
			<?php $this->widget('CLinkPager',array('pages'=>$pagebar));?>
		</div></td>
	</tr>
</table>
<?php $this->renderPartial('/_include/footer');?>
