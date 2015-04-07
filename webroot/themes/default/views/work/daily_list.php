<?php $this->renderPartial('/_include/header');?>
<div id="contentHeader">
	<h3>下属日报管理</h3>
	<div class="searchArea">
		<p class="left" >
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
    <a href="<?php echo $this->createUrl('work/daily',array('id'=>$low['user_id'],'auth'=>md5($low['user_id'].$this->_xsession['_adminUserName'].'icntv')));?>" class="btn btn-default btn-sm" >查看日报</a>
    <a href="<?php echo $this->createUrl('work/plan',array('id'=>$low['user_id'],'auth'=>md5($low['user_id'].$this->_xsession['_adminUserName'].'icntv')));?>" class="btn btn-default btn-sm" >查看计划</a>
    </td>
  </tr>
<?php endforeach;?>
</table>
<?php $this->renderPartial('/_include/footer');?>