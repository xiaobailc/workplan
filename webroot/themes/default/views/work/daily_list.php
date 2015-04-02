<?php $this->renderPartial('/_include/header');?>
<div id="contentHeader">
	<h3>下属日报管理</h3>
	<div class="searchArea">
		<p class="left" >
			<button type="button" class="btn btn-default btn-sm" onclick="expandNode('expandAll')">全部展开</button>
			<button type="button" class="btn btn-default btn-sm" onclick="expandNode('collapseAll')">全部折叠</button>
		</p>
		<div class="right">
		</div>
	</div>
</div>
<!-- 
<div class="">
		<ul id="treeDemo" class="ztree"></ul>
</div>
 -->
<table class="form_table">
<?php foreach ($lower as $low):?>
  <tr>
    <td class="tb_title">
    <?php echo $low['user_name']?>
    <a href="<?php echo $this->createUrl('work/daily',array('id'=>$low['id'],'auth'=>md5($low['id'].$this->_xsession['_adminUserName'].'icntv')));?>" class="btn btn-success btn-sm" ">查看日报</a>
	<a href="#" class="btn btn-success btn-sm" ">查看计划</a>
    </td>
  </tr>
<?php endforeach;?>
</table>
<script type="text/javascript">
var setting = {
	data: {
		simpleData: {
			enable: true
		}
	}
};
var zNodes = <?php echo $zNodes;?>;
$(document).ready(function(){
	$.fn.zTree.init($("#treeDemo"), setting, zNodes);
});

function expandNode(type) {
	var zTree = $.fn.zTree.getZTreeObj("treeDemo");
	if (type == "expandAll") {
		zTree.expandAll(true);
	} else {
		zTree.expandAll(false);
	}
}

function addNode(){
	var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
	nodes = zTree.getSelectedNodes(),
	treeNode = nodes[0];
	if (nodes.length == 0) {
		alert("请先选择一个下属员工");
		return;
	}
	alert(nodes[0]);
}

function editNode(){
	var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
	nodes = zTree.getSelectedNodes(),
	treeNode = nodes[0];
	if (nodes.length == 0) {
		alert("请先选择一个下属员工");
		return;
	}
	alert(nodes[0]);
	//var callbackFlag = $("#callbackTrigger").attr("checked");
	//zTree.removeNode(treeNode, callbackFlag);
}

function structuresubmit(){
	$.ajax({
		url: webUrl+currentScript+'?r=structure/edit',
		data: {
			id:$('#InputTitle').val(),
			user_id:$('#selectUser').val(),
			user_name:$('#selectUser').find("option:selected").text(),
			leader_id:$('#selectLeader').val()
		},
		type: 'POST',
		dataType: 'text',
		success: function(data, textStatus, jqXHR) {
			$('#myModal').modal('hide');
			location.href = webUrl+currentScript+'?r=structure/index';
			return;
		},
		error: function(jqXHR, textStatus, errorThrown) {
			alert((jqXHR.responseJSON ? jqXHR.responseJSON.message : 'Error') + '\n\n' + jqXHR.status + (errorThrown ? ' ' + errorThrown : ''));
		}
	});
}
</script>
<?php $this->renderPartial('/_include/footer');?>