<?php $this->renderPartial('/_include/header');?>
<div id="contentHeader">
	<h3>组织架构管理</h3>
	<div class="searchArea">
		<p class="left" >
			<button type="button" class="btn btn-success btn-sm" onclick="addNode()">添加组织</button>
			<button type="button" class="btn btn-info btn-sm" onclick="editNode()">编辑组织</button>
			<button type="button" class="btn btn-danger btn-sm" onclick="deleteNode()">删除组织</button>
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
<!-- 新建成员模板 -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                <option value="<?php echo $value['id']?>" <?php XUtils::selected($value['id']);?>><?php echo $value['title_show']?></option>
                <?php endforeach;?>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary" onclick="structuresubmit('new')">提交</button>
      </div>
    </div>
  </div>
</div>
<!-- 编辑成员模板 -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">编辑成员</h4>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="recipient-name" class="control-label">成员</label>
            <input class="form-control" type="text" id="editUser" disabled />
            <input type="hidden" id="editId" />
          </div>
          <div class="form-group">
            <label for="message-text" class="control-label">设置上级</label>
            <select class="form-control" id="editLeader">
                <?php foreach($menus as $index=>$value):?>
                <option value="<?php echo $value['id']?>"><?php echo $value['title_show']?></option>
                <?php endforeach;?>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary" onclick="structuresubmit('edit')">提交</button>
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
	},
	edit:{
		drag:{
			inner:true,
			prev:false,
			next:false,
			isCopy:false
		},
		enable: true,
		showRemoveBtn: false,
		showRenameBtn: false
	},
	callback: {
		onDrop: onDrop
	}
};
var zNodes = <?php echo $zNodes;?>;

$(document).ready(function(){
	$.fn.zTree.init($("#treeDemo"), setting, zNodes);
	$.fn.zTree.getZTreeObj("treeDemo").expandAll(true);
});

function onDrop(event, treeId, treeNodes, targetNode, moveType, isCopy) {
 	$.ajax({
		url: webUrl+currentScript+'?r=structure/edit',
		data: {
			type:'edit',
			id:treeNodes[0].id,
			user_id:null,
			user_name:null,
			pid:targetNode.id
		},
		type: 'POST',
		dataType: 'text',
		success: function(data, textStatus, jqXHR) {
			$('#myModal').modal('hide');
			//location.href = webUrl+currentScript+'?r=structure/index';
			return;
		},
		error: function(jqXHR, textStatus, errorThrown) {
			alert((jqXHR.responseJSON ? jqXHR.responseJSON.message : 'Error') + '\n\n' + jqXHR.status + (errorThrown ? ' ' + errorThrown : ''));
		}
	}); 
}

function expandNode(type) {
	var zTree = $.fn.zTree.getZTreeObj("treeDemo");
	if (type == "expandAll") {
		zTree.expandAll(true);
	} else {
		zTree.expandAll(false);
	}
	var nodes = zTree.transformToArray(zTree.getNodes());
}

function addNode(){
	$('#createModal').modal({
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
	$('#editModal').modal({
		keyboard:true
	});
	$('#editId').val(treeNode.id);
	$('#editUser').val(treeNode.name);
	$('#editLeader').val(treeNode.pId);
	
}

function deleteNode(){
	var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
	nodes = zTree.getSelectedNodes(),
	treeNode = nodes[0];
	if (nodes.length == 0) {
		alert("请先选择一个组织成员");
		return;
	}
	if (nodes[0].isParent) {
		alert("请先选择一个子节点");
		return;
	}
	$.ajax({
		url: webUrl+currentScript+'?r=structure/edit',
		data: {
			type:'delete',
			id:treeNode.id
		},
		type: 'POST',
		dataType: 'text',
		success: function(data, textStatus, jqXHR) {
			location.href = webUrl+currentScript+'?r=structure/index';
			return;
		},
		error: function(jqXHR, textStatus, errorThrown) {
			alert((jqXHR.responseJSON ? jqXHR.responseJSON.message : 'Error') + '\n\n' + jqXHR.status + (errorThrown ? ' ' + errorThrown : ''));
		}
	});
}

function structuresubmit(type){
	if(type=='new'){
		var id = null;
		var user_id = $('#selectUser').val();
		var user_name = $('#selectUser').find("option:selected").text();
		var pid = $('#selectLeader').val();
	}else{
		var id = $('#editId').val();
		var user_id = null;
		var user_name = null;
		var pid = $('#editLeader').val();
	}
	$.ajax({
		url: webUrl+currentScript+'?r=structure/edit',
		data: {
			type:type,
			id:id,
			user_id:user_id,
			user_name:user_name,
			pid:pid
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