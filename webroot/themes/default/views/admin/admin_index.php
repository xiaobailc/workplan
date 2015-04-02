<?php $this->renderPartial('/_include/header');?>

<div id="contentHeader">
	<h3>用户</h3>
	<div class="searchArea">
		<p class="left" >
			<a href="<?php echo $this->createUrl('create')?>" class="btn btn-success btn-sm">添加</a>
		</p>
		<div class="search right">
<?php echo CHtml::form('', 'get', array('class'=>'form-inline'));?>
			<?php echo CHtml::textField('keyword','',array('placeholder'=>'请输用户名','class'=>'form-control input-sm'));?>
			<input type="submit" value="搜索" class="btn btn-primary btn-sm btn-sm" />
<?php echo CHtml::endForm();?>
		</div>
	</div>
</div>
<table class="table table-bordered table-condensed">
	<thead>
	<tr class="active">
		<th style="width: " >用户 </th>
		<th style="width: 100px" >姓名</th>
		<th style="width: " >组</th>
		<th style="width: " >邮箱</th>
		<th style="width: 200px" >最后登录</th>
		<th style="width: 75px" >状态</th>
		<th style="width: 200px" >操作</th>
	</tr>
	</thead>
<?php foreach ($datalist as $row):?>
	<tr class="tb_list">
		<td ><?php echo $row->username?></td>
		<td ><?php echo $row->realname?></td>
		<td ><?php
		foreach($this->group_list as $v){
			$group[$v->id] = $v->group_name;
		}
		$gids = explode(',',$row->group_id);
		$result = '';
		foreach($gids as $gid){
			$result .= $group[$gid].',';
		}
		echo rtrim($result,',');
		?></td>
		<td><span ><?php echo $row->email?></span></td>
		<td ><?php echo date('Y-m-d H:i',$row->last_login_time)?></td>
		<td ><?php echo ($row->status_is=='Y')?'启用':'禁用'?></td>
		<td >
			<a href="<?php echo  $this->createUrl('update',array('id'=>$row->id))?>" class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> 编辑</a>
<?php if($row->status_is=='Y'):?>
			<a href="<?php echo  $this->createUrl('batch',array('command'=>'adminBan', 'id'=>$row->id))?>" class="btn btn-warning btn-xs"><i class="fa fa-ban"></i> 禁用</a>
<?php else:?>
			<a href="<?php echo  $this->createUrl('batch',array('command'=>'adminAllow', 'id'=>$row->id))?>" class="btn btn-success btn-xs"><i class="fa fa-check"></i> 启用</a>
<?php endif;?>
			<a href="<?php echo  $this->createUrl('batch',array('command'=>'adminDelete', 'id'=>$row->id))?>" class="btn btn-danger btn-xs confirmSubmit"><i class="fa fa-trash-o"></i> 删除</a>
		</td>
	</tr>
<?php endforeach;?>
	<tr>
		<td colspan="8">
		<div class="cuspages right">
			<?php $this->widget('CLinkPager',array('pages'=>$pagebar));?>
		</div></td>
	</tr>
</table>
<?php $this->renderPartial('/_include/footer');?>