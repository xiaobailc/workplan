<?php $this->renderPartial('/_include/header');?>
<div id="contentHeader">
	<h3>添加终端版本</h3>
	<div class="searchArea">
		<p class="left" >
			<a href="<?php echo $this->createUrl('index')?>" class="btn btn-success btn-sm"><b>返回</b></a>
		</p>
		<div class="search right"> </div>
	</div>
</div>
<?php $this->renderPartial('_terminal_form',array('model'=>$model))?>
<?php $this->renderPartial('/_include/footer');?>
