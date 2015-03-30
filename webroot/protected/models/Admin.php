<?php
/**
 * "{{admin}}" 数据表模型类.
 *
 * @author        Shiliang <guan.shiliang@gmail.com>
 * @copyright     Copyright (c) 2007-2013 icntv. All rights reserved.
 * @link          http://www.icntv.tv
 * @package       iCC.Model
 * @license       http://www.icntv.tv/license
 * @version       v1.0.0
 */
class Admin extends XBaseModel
{
	public $captcha;
	/**
	 * @return string 相关的数据库表的名称
	 */
	public function tableName()
	{
		return '{{admin}}';
	}

	/**
	 * @return array 对模型的属性验证规则.
	 */
	public function rules()
	{
		return array(
			array('username, password', 'required', 'on'=>'create'),
			array('username, password', 'required', 'on'=>'login'),
			array('username', 'unique', 'on'=>'create'),
			array('username, password', 'required'),
			array('last_login_time, create_time', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>50),
			array('password', 'length', 'max'=>32),
			array('group_id', 'length', 'max'=>50),
			array('realname, email', 'length', 'max'=>100),
			array('qq, last_login_ip', 'length', 'max'=>15),
			array('mobile, telephone', 'length', 'max'=>20),
			array('login_count', 'length', 'max'=>10),
			array('status_is', 'length', 'max'=>1),
			array('notebook', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, username, password, realname, group_id, email, qq, notebook, mobile, telephone, last_login_ip, last_login_time, login_count, status_is, create_time', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array 自定义属性标签 (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => '用户',
			'password' => '密码',
			'realname' => '真实姓名',
			'group_id' => '用户组',
			'email' => '邮箱',
			'qq' => 'QQ',
			'notebook' => '备忘',
			'mobile' => '电话',
			'telephone' => '手机',
			'last_login_ip' => '最后登录ip',
			'last_login_time' => '最后登录时间',
			'login_count' => '登录次数',
			'status_is' => '用户状态',
			'create_time' => '添加时间',
			'captcha' => '验证码',
		);
	}


	/**
	 * 返回指定的AR类的静态模型.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Admin the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 数据保存前处理
	 * @return boolean.
	 */
	protected function beforeSave ()
	{
	
	    $this->last_login_time = time();
	    if ($this->isNewRecord) {
	        $this->password = md5($this->password);
	        $this->create_time = time();
	    }
	    return true;
	}
}
