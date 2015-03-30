<?php 
	$iccCommentModel = new PostComment();
	$iccCommentCriteria = new CDbCriteria();
	$iccCommentCriteria->condition = 'post_id='.$iccShow['id'];
	$iccCommentCriteria->order = 't.id DESC';
	$iccCommentCount = $iccCommentModel->count( $iccCommentCriteria );
	$iccCommentPages = new CPagination( $iccCommentCount );
	$iccCommentPages->pageSize = 15;
	$iccCommentPageParams = XUtils::buildCondition( $_GET, array ( 'id'    ) );
	$iccCommentPageParams['#'] = 'commentList';
	$iccCommentPages->params = is_array( $iccCommentPageParams ) ? $iccCommentPageParams : array ();
	$iccCommentCriteria->limit = $iccCommentPages->pageSize;
	$iccCommentCriteria->offset = $iccCommentPages->currentPage * $iccCommentPages->pageSize;
	$iccCommentList = $iccCommentModel->findAll( $iccCommentCriteria );
?>
<div id="comment">
      <div class="boxTit ">
        <h3>最新评论</h3>
      </div>
      <div class="bmc">
      <?php foreach($iccCommentList  as $key=>$row):?>
        <dl class="item clear">
          <dt class="user"> <a class="title" ><?php echo $row->nickname?></a> <span class=" xw0"><?php echo date('Y-m-d H:i:s',$row['create_time'])?></span> </dt>
          <dd class="con"><?php echo CHtml::encode($row['content'])?></dd>
        </dl>
         <?php endforeach?>
         <div class="pagebar clear">
          <?php $this->widget('CLinkPager',array('pages'=>$iccPagebar));?>
        </div>
        <form id="commentForm" name="cform"  method="post" autocomplete="off">
          <div class="cForm">
            <div class="area">
              <textarea name="comment" rows="3" class="pt validate[required]" id="comment" ></textarea>
            </div>
           
          </div>
          <div> 昵称：<input name="nickname" type="text" id="nickname" class="validate[required]"/> 邮箱：<input name="email" type="text" id="email" class="validate[required]"/></div>
          <p class="ptn">
           <input type="hidden" name="postId" id="postId" value="<?php echo $iccShow['id']?>" />
            <button class="button" type="button" id="postComment">提交</button>
          </p>
          <div id="errorHtml"></div>
        </form>
      </div>
    </div>
<script type="text/javascript">
$("#postComment").click(
	function(){
		$.post("<?php echo $this->createUrl('post/postComment')?>",$("#commentForm").serializeArray(),function(res){
			if(res.state == 'success'){
				window.location.reload();
      }else{
        $("#errorHtml").html(res.message).show();
      }
	},'json');	
	}
);
</script>