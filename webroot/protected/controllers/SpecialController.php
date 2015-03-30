<?php
/**
 * 专题控制器
 *
 * @author        Shiliang <guan.shiliang@gmail.com>
 * @copyright     Copyright (c) 2007-2013 icntv. All rights reserved.
 * @link          http://www.icntv.tv
 * @package       iCC.Controller
 * @license       http://www.icntv.tv/license
 * @version       v1.0.0
 */
class SpecialController extends XFrontBase
{
  /**
   * 专题首页
   */
  public function actionIndex(){
    $specialModel = new Special();
    $specialCriteria = new CDbCriteria();
    $specialCriteria->addCondition ( 't.status_is=:status');
    $specialCriteria->params[':status'] = 'Y';
    $specialCriteria->order = 't.id DESC';
    $iccSpecialCount = $specialModel->count( $specialCriteria );
    $specialPages = new CPagination( $iccSpecialCount );
    $specialPages->pageSize = 15;
    $specialPageParams = XUtils::buildCondition( $_GET, array (  ) );
    $specialPageParams['#'] = 'list';
    $specialPages->params = is_array( $specialPageParams ) ? $specialPageParams : array ();
    $specialCriteria->limit = $specialPages->pageSize;
    $specialCriteria->offset = $specialPages->currentPage * $specialPages->pageSize;
    $specialList = $specialModel->findAll( $specialCriteria );
    $this->_seoTitle = '专题 - '.$this->_conf['site_name'];
    $this->render( 'index', array( 'iccDataList'=>$specialList, 'iccPagebar'=>$specialPages ) );
  }

  /**
   * 查看专题
   */
  public function actionShow($name){
    $specialModel = Special::model()->find('title_alias=:titleAlias', array('titleAlias'=>CHtml::encode(strip_tags($name))));
    if ( false == $specialModel )
      throw new CHttpException( 404, '专题不存在' );
    //更新浏览次数
    $specialModel->updateCounters(array ('view_count' => 1 ), 'id=:id', array ('id' => $specialModel->id ));
    $specialPostModel = new Post();
    $criteria = new CDbCriteria();
    $criteria->addCondition ( 't.status_is=:status AND special_id=:specialId');
    $criteria->params = array('status'=>'Y', 'specialId'=>$specialModel->id);
    $criteria->order = 't.id DESC';
    $iccSpecialCount = $specialPostModel->count( $criteria );
    $postPage = new CPagination( $iccSpecialCount );
    $postPage->pageSize = 10;
    $postPageParams = XUtils::buildCondition( $_GET, array ( ) );
    $postPageParams['#'] = 'list';
    $postPage->params = is_array( $postPageParams ) ? $postPageParams : array ();
    $criteria->limit = $postPage->pageSize;
    $criteria->offset = $postPage->currentPage * $postPage->pageSize;
    $specialPostList = $specialPostModel->findAll( $criteria );
    $this->_seoTitle = empty( $specialModel->seo_title ) ? $specialModel->title .' - '. $this->_conf['site_name'] : $specialModel->seo_title;
    $tpl = empty($specialModel->tpl) ? 'show': $specialModel->tpl ;

    $data = array(
        'specialShow'=>$specialModel,
        'specialPostList'=>$specialPostList,
        'iccPagebar'=>$postPage,
     );
    $this->render($tpl, $data);
  }
}
