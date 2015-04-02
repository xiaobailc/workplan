<?php $this->renderPartial('/_include/header');?>
<div id="contentHeader">
	<h3>组织架构管理</h3>
	<div class="searchArea">
		<p class="left" >
			<button type="button" class="btn btn-success btn-sm" onclick="addNode()">添加组织</button>
			<button type="button" class="btn btn-success btn-sm" onclick="editNode()">编辑组织</button>
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
<!-- 新建/编辑成员模板 -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">添加成员</h4>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="recipient-name" class="control-label">选择成员</label>
            <select class="form-control" id="selectUser">
              <?php foreach($users as $user):?>
                <option value="<?php echo $user->id;?>"><?php echo $user->realname.'('.$user->email.')';?></option>
                <?php endforeach;?>
            </select>
          </div>
          <div class="form-group">
            <label for="message-text" class="control-label">设置上级</label>
            <select class="form-control" id="selectLeader">
                <?php foreach($menus as $index=>$value):?>
                <option value="<?php echo $value['id']?>" <?php XUtils::selected($value['id'], $parentId);?>><?php echo $value['title_show']?></option>
                <?php endforeach;?>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary" onclick="structuresubmit()">提交</button>
      </div>
    </div>
  </div>
</div>


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
	$('#myModal').modal({
		keyboard:true
	});
}

function editNode(){
	var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
	nodes = zTree.getSelectedNodes(),
	treeNode = nodes[0];
	if (nodes.length == 0) {
		alert("请先选择一个组织成员");
		return;
	}
	$('#myModal').modal({
		keyboard:true
	});
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
			pid:$('#selectLeader').val()
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