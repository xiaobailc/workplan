<?php
/**
 * 公共登录
 * 
 * @author        Shiliang <guan.shiliang@gmail.com>
 * @copyright     Copyright (c) 2007-2013 icntv. All rights reserved.
 * @link          http://www.icntv.tv
 * @package       iCC.admin.Controller
 * @license       http://www.icntv.tv/license
 * @version       v1.0.0
 */

class PublicController extends Controller
{

    /**
     * 会员登录
     */
    public function actionLogin ()
    {
        $model = new Admin('login');
        if (XUtils::method() == 'POST') {
            $model->attributes = $_POST['Admin'];
            if ($model->validate()) {
                $data = $model->find('username=:username', array ('username' => $model->username ));
                if ($data === null) {
                    $model->addError('username', '用户不存在');
                    parent::_adminLogger(array ('catalog' => 'login' , 'intro' => '登录失败，用户不存在:' . $model->username , 'user_id' => 0 ));
                } elseif (! $model->validatePassword($data->password)) {
                    $model->addError('password', '密码不正确');
                    parent::_adminLogger(array ('catalog' => 'login' , 'intro' => '登录失败，密码不正确:' . $model->username. '，使用密码：'.$model->password , 'user_id' => 0 ));
                } elseif ($data->group_id == 2) {
                    $model->addError('username', '用户已经锁定，请联系管理');
                } else {
                    $session = new XSession();
                    $session->set('_adminUserId', $data->id);
                    $session->set('_adminUserName', $data->username);
                    $session->set('_adminRealName', $data->realname);
                    $session->set('_adminGroupId', $data->group_id);
                    if ($data->group_id == 1)
                        $session->set('_adminPermission', 'adminstrator');
                    $data->last_login_ip = XUtils::getClientIP();
                    $data->last_login_time = time();
                    $data->login_count = $data->login_count+1;
                    $data->save();
                    parent::_adminLogger(array ('catalog' => 'login' , 'intro' => '用户登录成功:'.$model->username ));
                    $this->redirect(array('default/index'));
                    XUtils::message('success', '登录成功', $this->createUrl('default/index'),2 );
                }
            }
        }
        $this->render('login', array ('model' => $model ));
    }

    /**
     * 退出登录
     */
    public function actionLogout ()
    {
        parent::_sessionRemove('_adminUserId');
        parent::_sessionRemove('_adminUsername');
        parent::_sessionRemove('_adminGroupId');
        parent::_sessionRemove('_adminPermission');
        $this->redirect(array ('public/login' ));
    }

}

?>