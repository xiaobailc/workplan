<?php $this->renderPartial('/_include/header');?>
<div id="contentHeader">
	<h3>素材管理</h3>
	<div class="searchArea">
		<p class="left" >
		</p>
		<div class="right">
		</div>
	</div>
</div>

<table class="table table-bordered table-condensed">
	<thead>
		<tr class="active">
			<th style="width: 200px" >预览</th>
			<th style="" >名称</th>
			<th style="width: 125px" >大小</th>
			<th style="width: 200px" >创建时间</th>
			<th style="width: 200px" >修改时间</th>
			<th style="width: 200px" >最后访问时间</th>
			<th style="width: 100px" >类型</th>
			<th style="width: 150px" >操作</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($filelist as $index => $model):?>
		<tr>
			<td><?php echo $model['type']=='directory'?'--':'<img src="'.$model['filename'].'" />'?></td>
			<td><a href="<?php echo $this->createUrl('index',array('tid'=>$tid,'path'=>$model['filename']))?>"><?php echo $model['name'];?></a></td>
			<td><?php echo $model['size'];?></td>
			<td><?php echo $model['ctime'];?></td>
			<td><?php echo $model['mtime'];?></td>
			<td><?php echo $model['atime'];?></td>
			<td><?php echo $model['type'];?></td>
			<td class="group-btn">
				<a class="btn btn-default btn-xs" href="<?php echo $this->createUrl('edit',array('id'=>$model->id))?>"><i class="fa fa-pencil"></i> 编辑</a>
				<a class="btn btn-danger btn-xs confirmSubmit" href="<?php echo $this->createUrl('del',array('id'=>$model->id,'pid'=>$model->pid))?>"><i class="fa fa-trash-o"></i> 删除</a>
			</td>
		</tr>
		<?php endforeach;?>
	</tbody>
</table>
<?php $this->renderPartial('/_include/footer');?>