<?php
/**
 * 问答
 *
 * @author        Shiliang <guan.shiliang@gmail.com>
 * @copyright     Copyright (c) 2007-2013 icntv. All rights reserved.
 * @link          http://www.icntv.tv
 * @package       iCC.Controller
 * @license       http://www.icntv.tv/license
 * @version       v1.0.0
 */

class QuestionController extends XFrontBase
{
    /**
     * 首页
     */
    public function actionIndex() {

        $iccQuestionModel = new Question();
        $iccQuestionCriteria = new CDbCriteria();
        $iccQuestionCriteria->condition = 'status_is=:status';
        $iccQuestionCriteria->params = array( 'status'=>'Y' );
        $iccQuestionCriteria->order = 't.id DESC';
        $iccQuestionCount = $iccQuestionModel->count( $iccQuestionCriteria );
        $iccQuestionPages = new CPagination( $iccQuestionCount );
        $iccQuestionPages->pageSize = 10;
        $iccQuestionPageParams = XUtils::buildCondition( $_GET, array () );
        $iccQuestionPageParams['#'] = 'list';
        $iccQuestionPages->params = is_array( $iccQuestionPageParams ) ? $iccQuestionPageParams : array ();
        $iccQuestionCriteria->limit = $iccQuestionPages->pageSize;
        $iccQuestionCriteria->offset = $iccQuestionPages->currentPage * $iccQuestionPages->pageSize;
        $iccQuestionList = $iccQuestionModel->findAll( $iccQuestionCriteria );
        $this->_seoTitle = '留言咨询 - '.$this->_conf['site_name'];
        $this->render( 'index', array( 'iccQuestionList'=>$iccQuestionList, 'pages'=>$iccQuestionPages ) );
    }

    /**
     * 提交留言
     */
    public function actionPost() {
        if ( $_POST['Question'] ) {
            try {
                $questionModel = new Question();
                $questionModel->attributes = $_POST['Question'];
                if ( $questionModel->save() ) {
                    $var['state'] = 'success';
                    $var['message'] = '提交成功';
                }else {
                    throw new Exception( CHtml::errorSummary( $questionModel, null, null, array ( 'firstError' => '' ) ) );
                }
            } catch ( Exception $e ) {
                $var['state'] = 'error';
                $var['message'] = $e->getMessage();
            }
        }
        exit( CJSON::encode( $var ) );
    }
}
