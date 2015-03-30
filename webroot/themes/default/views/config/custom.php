<?php echo $this->renderPartial('/_include/header')?>

<div id="contentHeader">
  <h3>自定义属性</h3>
</div>
<?php if($attrModel):?>
<form action="" method="post"  enctype="multipart/form-data" >
  <?php $this->renderPartial('/_include/attr2content', array('attrModel'=>$attrModel, 'attrData'=>$attrData));?>
  <div class="submit" style="padding-top:10px">
    <input type="submit" name="editsubmit" value="提交" class="btn btn-primary btn-sm" tabindex="3" />
    <a href="<?php echo $this->createUrl('attr/create')?>" class="actionLink"><span>添加更多属性</span></a> </div>
</form>
<?php else:?>
<a href="<?php echo $this->createUrl('attr/create')?>" class="actionLink"><span>请先添加属性</span></a>

<?php endif?>
<?php echo $this->renderPartial('/_include/footer')?>