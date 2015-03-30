<?php $this->renderPartial('/_include/header');?>

<div id="contentHeader">
  <h3>用户</h3>
  <div class="searchArea">
    <p class="left" >
      <a href="<?php echo $this->createUrl('index')?>" class="btn btn-success btn-sm"><span>返回</span></a>
      <a href="<?php echo $this->createUrl('create')?>" class="btn btn-success btn-sm"><span>添加</span></a>
    </p>
    <div class="right"> </div>
  </div>
</div>
<?php $this->renderPartial('_admin_form',array('model'=>$model))?>
<?php $this->renderPartial('/_include/footer');?>
