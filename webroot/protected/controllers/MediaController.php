<?php
class MediaController extends XAdminBase
{
	public function actionIndex($tid,$path=''){
		$terminal = Terminal::model()->findByPk($tid);
		empty($path) && $path = './uploads/'.$terminal->partial;
		$fs = new XFile($path);
		$file_list = $fs->fileList();
		print_r($file_list);exit;
		$this->render('index',array('filelist'=>$file_list,'tid'=>$tid));
	}
}