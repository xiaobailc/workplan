<?php $this->renderPartial('/_include/header');?>
<div id="contentHeader">
  <h3>用户组</h3>
  <div class="searchArea">
    <p class="left" >
      <a href="<?php echo $this->createUrl('group')?>" class="btn btn-success btn-sm"><span>管理</span></a>
      <a href="<?php echo $this->createUrl('groupCreate')?>" class="btn btn-success btn-sm"><span>添加</span></a>
    </p>
    <div class="search right"> </div>
  </div>
</div>
<?php $this->renderPartial('_group_form',array('model'=>$model,'node_list' => $node_list))?>
<?php $this->renderPartial('/_include/footer');?>
