<?php $this->renderPartial('/_include/header');?>
<div id="contentHeader">
  <h3>新建/编辑日报</h3>
  <div class="searchArea">
    <p class="left" >
      <a href="<?php echo $this->createUrl('daily')?>" class="btn btn-success btn-sm">返回</a>
    </p>
    <div class="search right"> </div>
  </div>
</div>
<?php $this->renderPartial('_daily_form',array('report_info'=>$report_info))?>
<?php $this->renderPartial('/_include/footer');?>
