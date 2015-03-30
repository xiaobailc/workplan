<?php $this->renderPartial('/_include/header');?>

<div id="contentHeader">
  <h3>会员组管理</h3>
  <div class="searchArea">
    <ul class="action left" >
      <li ><a href="<?php echo $this->createUrl('group')?>" class="actionBtn"><span>返回</span></a></li>
      <li class="current"><a href="<?php echo $this->createUrl('groupCreate')?>" class="actionBtn"><span>添加</span></a></li>
    </ul>
    <div class="search right"> </div>
  </div>
</div>
<?php $this->renderPartial('_user_group_form',array('model'=>$model))?>
<?php $this->renderPartial('/_include/footer');?>
