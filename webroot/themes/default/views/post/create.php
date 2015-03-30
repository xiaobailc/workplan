<?php $this->renderPartial('/_include/header');?>
<div id="contentHeader">
  <h3>主题管理</h3>
  <div class="searchArea">
    <ul class="action left" >
      <li class="current"><a href="<?php echo $this->createUrl('index')?>" class="actionBtn"><span>返回</span></a></li>
      <li class="current"><a href="<?php echo $this->createUrl('create')?>" class="actionBtn"><span>添加</span></a></li>
    </ul>
    
  </div>
</div>

<?php $this->renderPartial('_post_form',array('model'=>$model, 'imageList'=>$imageList, 'attrModel'=>$attrModel, 'attrData'=>$attrData, 'groupList'=>$groupList))?>

<?php $this->renderPartial('/_include/footer');?>
