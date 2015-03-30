<?php
/**
 * 单页控制器
 *
 * @author        Shiliang <guan.shiliang@gmail.com>
 * @copyright     Copyright (c) 2007-2013 icntv. All rights reserved.
 * @link          http://www.icntv.tv
 * @package       iCC.Controller
 * @license       http://www.icntv.tv/license
 * @version       v1.0.0
 */
class PageController extends XFrontBase
{
  /**
  * 浏览
  */
  public function actionShow( $name ) {
    $iccPageModel = Page::model()->find('title_alias=:titleAlias', array('titleAlias'=>CHtml::encode(strip_tags($name))));
    if ( false == $iccPageModel )
      throw new CHttpException( 404, '内容不存在' );
    $this->_seoTitle = empty( $iccPageModel->seo_title ) ? $iccPageModel->title .' - '. $this->_conf['site_name'] : $iccPageModel->seo_title;
    $tpl = empty($iccPageModel->tpl) ? 'show': $iccPageModel->tpl ;
    $this->render( 'show', array( 'iccPage'=>$iccPageModel ) );
  }

}
