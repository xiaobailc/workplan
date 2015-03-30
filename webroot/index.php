<?php
/**
 * 入口
 *
 * @author        Shiliang <guan.shiliang@gmail.com>
 * @copyright     Copyright (c) 2007-2013 icntv. All rights reserved.
 * @link          http://www.icntv.tv
 * @package       iCC
 * @license       http://www.icntv.tv/license
 * @version       v1.0.0
 */

define('WEBROOT', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);
defined('YII_DEBUG') or define('YII_DEBUG', true);

YII_DEBUG or error_reporting(0);

$yii    = WEBROOT . '/../framework/yiilite.php';
$config = WEBROOT . '/protected/config/main.php';

require_once ($yii);

Yii::createWebApplication($config)->run();
