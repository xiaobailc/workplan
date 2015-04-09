<?php $this->renderPartial('/_include/header');?>
<div id="contentHeader">
	<h3>下属日报管理</h3>
	<div class="searchArea">
		<p class="left" >
			<button type="button" class="btn btn-success btn-sm" onclick="checkDaily()">查看日报</button>
			<button type="button" class="btn btn-success btn-sm" onclick="checkPlan()">查看汇总</button>
			<button type="button" class="btn btn-default btn-sm" onclick="expandNode('expandAll')">全部展开</button>
			<button type="button" class="btn btn-default btn-sm" onclick="expandNode('collapseAll')">全部折叠</button>
		</p>
		<div class="right">
		</div>
	</div>
</div>
<div class="">
        <ul id="treeDemo" class="ztree"></ul>
</div>
<!-- 
<table class="form_table">
<?php foreach ($lower as $low):?>
  <tr>
    <td class="tb_title">
    <?php echo $low['user_name']?>
    <a href="<?php echo $this->createUrl('work/daily',array('id'=>$low['user_id'],'auth'=>md5($low['user_id'].$this->_xsession['_adminUserName'].'icntv')));?>" class="btn btn-default btn-sm" >查看日报</a>
    <a href="<?php echo $this->createUrl('work/plan',array('id'=>$low['user_id'],'auth'=>md5($low['user_id'].$this->_xsession['_adminUserName'].'icntv')));?>" class="btn btn-default btn-sm" >查看计划</a>
    </td>
  </tr>
<?php endforeach;?>
</table>
 -->
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
	$.fn.zTree.getZTreeObj("treeDemo").expandAll(true);
});

function expandNode(type) {
	var zTree = $.fn.zTree.getZTreeObj("treeDemo");
	if (type == "expandAll") {
		zTree.expandAll(true);
	} else {
		zTree.expandAll(false);
	}
}

function checkDaily(){
	var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
	nodes = zTree.getSelectedNodes(),
	treeNode = nodes[0];
	if (nodes.length == 0) {
		alert("请先选择一个组织成员");
		return;
	}
	location.href = webUrl+currentScript+'?r=work/daily&id='+treeNode.userId+'&auth='+treeNode.auth;
}

function checkPlan(){
	var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
	nodes = zTree.getSelectedNodes(),
	treeNode = nodes[0];
	if (nodes.length == 0) {
		alert("请先选择一个组织成员");
		return;
	}
	location.href = webUrl+currentScript+'?r=work/plan&id='+treeNode.userId+'&auth='+treeNode.auth;
	
}
</script>
<?php $this->renderPartial('/_include/footer');?>