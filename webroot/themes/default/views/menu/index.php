<?php $this->renderPartial('/_include/header');?>
<div id="contentHeader">
	<h3>菜单管理</h3>
	<div class="searchArea">
		<p class="left" >
			<a href="<?php echo $this->createUrl('create',array('pid'=>$pid))?>" class="btn btn-success btn-sm">添加</a>
			<a href="<?php echo $this->createUrl('index',array('pid'=>$data->pid))?>" class="btn btn-success btn-sm">返回上级</a>
		</p>
		<div class="right">
<?php echo CHtml::form('', 'get', array('class'=>'form-inline'));?>
			<?php echo CHtml::textField('title',$title,array('placeholder'=>'请输入菜单名称','class'=>'form-control input-sm'));?>
			<input type="submit" value="搜索" class="btn btn-primary btn-sm btn-sm" />
<?php echo CHtml::endForm();?>
		</div>
	</div>
</div>

<table class="table table-bordered table-condensed">
	<thead>
		<tr class="active">
			<th style="width: 50px" >ID</th>
			<th style="width: 125px" >名称</th>
			<th style="width: 125px" >上级菜单</th>
			<th style="width: 125px" >分组</th>
			<th style="" >URL</th>
			<th style="width: 75px" >排序</th>
			<th style="width: 100px" >开发者模式</th>
			<th style="width: 75px" >隐藏</th>
			<th style="width: 150px" >操作</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($models as $index => $model):?>
		<tr>
			<td class="ac"><?php echo $model->id;?></td>
			<td><?php echo CHtml::link($model->title,array('index','pid'=>$model->id));?></td>
			<td><?php echo isset($data->title)?$data->title:'无';?></td>
			<td><?php echo $model->group;?></td>
			<td><?php echo $model->url;?></td>
			<td><?php echo $model->sort;?></td>
			<td><?php echo $model->DevValue;?></td>
			<td><?php echo $model->HideValue;?></td>
			<td class="group-btn">
				<a class="btn btn-default btn-xs" href="<?php echo $this->createUrl('edit',array('id'=>$model->id))?>"><i class="fa fa-pencil"></i> 编辑</a>
				<a class="btn btn-danger btn-xs confirmSubmit" href="<?php echo $this->createUrl('del',array('id'=>$model->id,'pid'=>$model->pid))?>"><i class="fa fa-trash-o"></i> 删除</a>
			</td>
		</tr>
		<?php endforeach;?>
	</tbody>
</table>
<?php $this->renderPartial('/_include/footer');?>