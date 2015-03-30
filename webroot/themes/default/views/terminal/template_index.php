<?php $this->renderPartial('/_include/header');?>

<div id="contentHeader">
	<h3>模板列表</h3>
	<div class="searchArea">
		<p class="left" >
			<a href="<?php echo $this->createUrl('tplcreate',array('tid'=>$tid))?>" class="btn btn-success btn-sm"><b>添加模板</b></a>
		</p>
		<div class="search right"></div>
	</div>
</div>
<table class="table table-bordered table-condensed">
	<thead>
	<tr class="active">
		<th style="width: 50px" >ID </th>
		<th style="width: " >模板名称 </th>
		<th style="width: 100px" >屏幕尺寸 </th>
		<th style="width: 100px" >模板数据 </th>
		<th style="width: 200px" >创建时间</th>
		<th style="width: 200px" >更新时间</th>
		<th style="width: 100px" >状态</th>
		<th style="width: 350px" >操作</th>
	</tr>
	</thead>
<?php foreach ($models as $row):?>
	<tr class="tb_list">
		<td ><?php echo $row->id?></td>
		<td ><?php echo $row->name?></td>
		<td ><?php echo $row->screen?></td>
		<td ><a href="javascript:void(0);" onclick="gettpldata(<?php echo $row->id?>,this)">点击查看</a></td>
		<td ><?php echo date('Y-m-d H:i:s', $row->create_time)?></td>
		<td ><?php echo date('Y-m-d H:i:s', $row->update_time)?></td>
		<td >
			<?php 
				$status = array('T'=>'草稿','A'=>'审核中','P'=>'审核通过','F'=>'审核失败');
				echo isset($status[$row->status]) ? $status[$row->status] : '草稿'; 
			?>
		</td>
		<td>
		<a href="<?php echo	$this->createUrl('epg',array('id'=>$row->id))?>" class="btn btn-default btn-xs "><i class="fa fa-pencil fa-fw"></i>编辑预览</a>
		<a href="<?php echo	$this->createUrl('batch',array('command'=>'tpldelete','id'=>$row->id))?>" class="btn btn-danger btn-xs  confirmSubmit disabled"><i class="fa fa-trash-o fa-fw"></i>删除</a>
		<a href="<?php echo $this->createUrl('audit',array('id'=>$row->id))?>" class="btn btn-primary btn-sm btn-xs <?php if($row->status=='P'){echo 'disabled';}?>"><i class="fa fa-arrow-circle-o-up fa-fw"></i>提交审核</a>
		<a href="javascript:void(0);" onclick ="gethistory(<?php echo $row->id?>,this)" class="btn btn-default btn-sm btn-xs "><i class="fa fa-caret-down fa-fw"></i>历史版本</a>
		</td>
	</tr>
	<?php endforeach;?>
	<tr>
		<td colspan="8"><div class="cuspages right">
			<?php $this->widget('CLinkPager',array('pages'=>$pagebar));?>
		</div></td>
	</tr>
</table>
<script type="text/javascript">
function gethistory(id,tthis){
	if($(tthis).parent().parent().next().attr('class') == 'history'){
		if($(tthis).children('i').hasClass('fa-caret-up')){
			$(tthis).children('i').removeClass('fa-caret-up').addClass('fa-caret-down');
		}else{
			$(tthis).children('i').removeClass('fa-caret-down').addClass('fa-caret-up');
		}
		$(tthis).parent().parent().next().toggle("normal");
		return;
	}
	$.ajax({
		url: webUrl+currentScript+'?r=admin/terminal/dealdata',
		data:{
			type:"get_tpl_history",
			id:id
		},
		type: 'POST',
		success: function(data, textStatus, jqXHR) {
			//bootbox.alert(data);return;
			$(tthis).html("<i class=\"fa fa-caret-up fa-fw\"></i> 历史版本");
			var html = '';
			$.each(data,function(){
				for(var key in this){
					html += '['+key+'] : __'+this[key]+'__';
				}
				html +='<br />'; 
			});
			var new_tr = $('<tr class="history" style="display:none"><td colspan="8">'+ html +'</td></tr>');
			$(tthis).parent().parent().after(new_tr);
			new_tr.toggle("normal");
		},
		error: function(jqXHR, textStatus, errorThrown) {
			alert((jqXHR.responseJSON ? jqXHR.responseJSON.message : 'Error') + '\n\n' + jqXHR.status + (errorThrown ? ' ' + errorThrown : ''));
		}
	});
}
function gettpldata(id,tthis){
	$.ajax({
		url: webUrl+currentScript+'?r=admin/terminal/dealdata',
		data: {
			type:"get_tpl_data",
			id:id
		},
		type: 'POST',
		dataType: 'text',
		success: function(data, textStatus, jqXHR) {
			bootbox.alert("<br /><pre>"+data+"</pre>");return;
		},
		error: function(jqXHR, textStatus, errorThrown) {
			alert((jqXHR.responseJSON ? jqXHR.responseJSON.message : 'Error') + '\n\n' + jqXHR.status + (errorThrown ? ' ' + errorThrown : ''));
		}
	});
}
</script>
<!-- Bootbox -->
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/lib/bootbox.js" ></script>
<?php $this->renderPartial('/_include/footer');?>
