<?php
/**
 * 系统配置
 * 
 * @author		Shiliang <guan.shiliang@gmail.com>
 * @copyright	 Copyright (c) 2007-2013 icntv. All rights reserved.
 * @link		  http://www.icntv.tv
 * @package	   Reta.Config
 * @license	   http://www.icntv.tv/license
 * @version	   v1.0.0
 */
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'workplan',
	'language'=>'zh_cn',
	'theme'=>'default',
	'timeZone'=>'Asia/Shanghai',
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.extensions.*',
	),
    'defaultController'=>'default',
	'modules'=>array(
		//'admin'=>array(
		//	'class'=>'application.modules.admin.AdminModule',
		//),
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'icc',
		)
	),
	'components'=>array(
		'cache'=>array(
			'class' => 'CMemCache',
			'servers' => array( array('host' => '127.0.0.1', 'port' => 11211)),
		),
		'db'=>array(
			'connectionString' => 'mysql:host=192.168.75.19;dbname=workplan',
			'emulatePrepare' => true,
			'enableParamLogging' => true,
			'enableProfiling'=>true,
			'username' => 'root',
			'password' => '654321',
			'charset' => 'utf8',
			'tablePrefix' => 'wp_',
		),
		'errorHandler'=>array(
			'errorAction'=>'error/index',
		), 
		'urlManager'=>array(
			//'urlFormat'=>'path',
			//'urlSuffix'=>'.html',
			'showScriptName'=>false,
			/*'rules'=>array(
				'post/<id:\d+>/*'=>'post/show',
				'post/<id:\d+>_<title:\w+>/*'=>'post/show',
				'post/catalog/<catalog:[\w-_]+>/*'=>'post/index',
				'page/show/<name:\w+>/*'=>'page/show',
				'special/show/<name:[\w-_]+>/*'=>'special/show',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),*/
		),
	),
	'params'=> require(dirname(__FILE__).DS.'params.php'),
);
