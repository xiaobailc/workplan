<?php $this->renderPartial('/_include/header');?>
<div id="contentHeader">
	<h3>日报管理</h3>
	<div class="searchArea">
		<p class="left" >
			<a href="<?php echo $this->createUrl('dailycreate')?>" class="btn btn-success btn-sm">添加当日日报</a>
		</p>
		<div class="right">
<?php echo CHtml::form('', 'get', array('class'=>'form-inline'));?>
			<?php echo CHtml::textField('keyword',$keyword,array('placeholder'=>'请输关键词','class'=>'form-control input-sm'));?>
			<input type="submit" value="搜索" class="btn btn-primary btn-sm btn-sm" />
<?php echo CHtml::endForm();?>
		</div>
	</div>
</div>

<table class="table table-bordered table-condensed">
	<thead>
		<tr class="active">
			<th style="width: 100px" >星期</th>
			<th style="width: 150px" >日期</th>
			<th style="width: 150px" >状态</th>
			<th style="" >操作</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($models as $index => $model):?>
		<tr>
			<td><?php
			$week = ['日','一','二','三','四','五','六'];
			$num = date('w',strtotime($model->date_time));
			echo "星期".$week[$num];
			?></td>
			<td ><a href="<?php echo $this->createUrl('dailyinfo',array('id'=>$model->id))?>">
			<?php echo $model->date_time;?>
			</a></td>
			<td><?php echo $model->status==0?"未提交":'已提交';?></td>
			<td class="group-btn">
				<a class="btn btn-default btn-xs <?php echo $model->status==0?'':'disabled';?>" href="<?php echo $this->createUrl('dailyedit',array('id'=>$model->id))?>"><i class="fa fa-pencil"></i> 编辑</a>
				<a class="btn btn-info btn-xs confirmSubmit <?php echo $model->status==0?'':'disabled';?>" href="<?php echo $this->createUrl('dailypush',array('id'=>$model->id))?>"><i class="fa fa-trash-o"></i> 提交</a>
			</td>
		</tr>
		<?php endforeach;?>
		<tr>
		<td colspan="4">
		<div class="cuspages right">
			<?php $this->widget('CLinkPager',array('pages'=>$pagebar));?>
		</div></td>
	</tr>
	</tbody>
</table>
<?php $this->renderPartial('/_include/footer');?>