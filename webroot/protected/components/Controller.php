<?php
/**
 * 控制器基类，前端，后端均需继承此类
 * 
 * @author        Shiliang <guan.shiliang@gmail.com>
 * @copyright     Copyright (c) 2007-2013 icntv. All rights reserved.
 * @link          http://www.icntv.tv
 * @package       iCC.Controller
 * @license       http://www.icntv.tv/license
 * @version       v1.0.0
 */

class Controller extends CController
{
    public $layout = 'main';
    protected $_gets;
    protected $_baseUrl;
    protected $_xsession;
    protected $_xcookies;
    protected $_conf;
    protected $_theme;
    protected $_themePath;
    protected $_icc = 'iCC V0.1';
    protected $_iccRelease = '20131219';

    /**
	 * 初始化
	 * @see CController::init()
	 */
    public function init ()
    {
        $this->_xsession = new CHttpSession();
        $this->_xsession->open();
        $this->_xcookies = Yii::app()->request->getCookies();
        $this->_gets = Yii::app()->request;
        $this->_baseUrl = Yii::app()->baseUrl;
        $this->_theme = Yii::app()->theme;
        $this->_themePath = str_replace(array('\\', '\\\\'), '/', Yii::app()->theme->basePath);
    }

    /*
      显示执行时间及内存
      @see CController::afterAction()
    
    protected function afterAction ($action)
    {
        $time = sprintf('%0.5f', Yii::getLogger()->getExecutionTime());
        $memory = round(memory_get_peak_usage() / (1024 * 1024), 2) . "MB";
        echo '<!-- Time: ' . $time . 'ms, memory: ' . $memory . '-->';
        parent::afterAction($action);
    } */

    /**
	 * 设置cookie
	 */
    protected function _cookiesSet ($name = '', $value = '', $expire = 3600, $path = '', $domain = '', $secure = false)
    {
        $cookieSet = new CHttpCookie($name, $value);
        $expire && $cookieSet->expire = $expire;
        $path && $cookieSet->path = $path;
        $domain && $cookieSet->domain = $domain;
        $secure && $cookieSet->secure = $secure;
        Yii::app()->request->cookies[$name] = $cookieSet;
    }

    /**
	 * 获取cookie
	 */
    protected function _cookiesGet ($name, $once = false)
    {
        $cookie = Yii::app()->request->getCookies();
        $data = $cookie[$name]->value;
        if ($once)
            unset($cookie[$name]);
        return $data;
    }

    /**
     * 清理cookie
     * @param  $name
     */
    protected function _cookiesRemove ($name)
    {
        $cookie = Yii::app()->request->getCookies();
        unset($cookie[$name]);
    }

    /**
	 * 设置session
	 *
	 * @param data 数据,可以是数组
	 */
    protected function _sessionSet ($name, $value = '', $expire = 0, $path = '')
    {
        $this->_xsession[$name] = $value;
    }

    /**
	 * 获取session
	 */
    protected function _sessionGet ($name, $once = false)
    {
        $data = $this->_xsession[$name];
        if ($once)
            $this->_xsession->remove($name);
        return $data;
    }

    /**
     * 清除session
     */
    protected function _sessionRemove ($name)
    {
        $this->_xsession->remove($name);
    }

    /**
    * 版本信息
    */
    public function actionVersion(){
        exit($this->_icc .' '. $this->_iccRelease);
    }

    /**
     * 载入项目
     */
    protected function _dataLoad ($model, $condition, $type = 'pk', array $params = array())
    {
        if ($type == 'attr') {
            $data = $model->findByAttributes($condition);
        } else 
            if ($type == 'string') 
                $data = $model->find($condition, $params);
            else 
                $data = $model->findByPk($condition);
        if ($data) 
            return $data;
         else 
            throw new CHttpException(404, '记录不存在');
    }

    /**
	 * 申明方法调用的类文件
	 */
    public function actions ()
    {
        return array (
            'captcha' => array (
                'class' => 'CCaptchaAction' ,
                'minLength' => 1 ,
                'maxLength' => 5 ,
                'backColor' => 0xFFFFFF ,
                'width' => 100 ,
                'height' => 40 
            )
         );
    }

    /**
     * 后台日志记录
     * @param  $intro
     */
    protected function _adminLogger (array $arr = array())
    {
        if(Config::get('admin_logger') == 'open'){
            $model = new AdminLogger();
            $model->attributes = $arr;
            !isset($arr['user_id']) && $model->user_id = intval(self::_sessionGet('_adminUserId'));
            $model->url = Yii::app()->request->getRequestUri();
            $model->ip = XUtils::getClientIP();
            $model->save();
        }
    }

    /**
     * 编辑器文件上传
     */
    public function actionUpload ()
    {
        if (XUtils::method() == 'POST') {
            $accountUserId = self::_sessionGet('accountUserId');
            //$adminUserId = self::_sessionGet('adminUserId');
            $file = XUpload::upload($_FILES['imgFile']);
            if (is_array($file)) {
                $model = new Upload();
                $model->user_id = intval($accountUserId);
                $model->file_name = $file['pathname'];
                $model->thumb_name = $file['paththumbname'];
                $model->real_name = $file['name'];
                $model->file_ext = $file['extension'];
                $model->file_mime = $file['type'];
                $model->file_size = $file['size'];
                $model->save_path = $file['savepath'];
                $model->hash = $file['hash'];
                $model->save_name = $file['savename'];
                $model->create_time = time();
                if ($model->save()) {
                    exit(CJSON::encode(array ('error' => 0 , 'url' => Yii::app()->baseUrl . '/' . $file['pathname'] )));
                } else {
                    @unlink($file['pathname']);
                    @unlink($file['paththumbname']);
                    exit(CJSON::encode(array ('error' => 1 , 'message' => '上传错误' )));
                }
            
            } else {
                exit(CJSON::encode(array ('error' => 1 , 'message' => '上传错误' )));
            }
        }
    }
}
