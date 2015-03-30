<?php
/**
 * 错误信息显示
 * 
 * @author        Shiliang <guan.shiliang@gmail.com>
 * @copyright     Copyright (c) 2007-2013 icntv. All rights reserved.
 * @link          http://www.icntv.tv
 * @package       iCC.Controller
 * @license       http://www.icntv.tv/license
 * @version       v1.0.0
 */

class ErrorController extends Controller
{
    public $layout = false;
    /**
     * 错误信息显示页
     */
    public function actionIndex ()
    {
        if ($error = Yii::app()->errorHandler->error) {
            switch ($error['code']) {
                case 404: $tpl = 'error404'; break;
                case 500: $tpl = 'error500'; break; 
                default: $tpl = 'error'; break;
            }
            $error['redirect'] = Yii::app()->request->urlReferrer ? Yii::app()->request->urlReferrer: Yii::app()->homeUrl;
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render($tpl, $error);
        }
    }
}