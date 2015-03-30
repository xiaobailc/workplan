<?php $this->renderPartial('/_include/header');?>
<div id="contentHeader">
  <h3>菜单管理</h3>
  <div class="searchArea">
    <p class="left" >
      <a href="<?php echo $this->createUrl('index',array('pid'=>$pid))?>" class="btn btn-success btn-sm">返回</a>
    </p>
    <div class="search right"> </div>
  </div>
</div>
<?php $this->renderPartial('_form',array('model'=>$model, 'menus'=>$menus, 'parentId'=>$pid))?>
<?php $this->renderPartial('/_include/footer');?>
