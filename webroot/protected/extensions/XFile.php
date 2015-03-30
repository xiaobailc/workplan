<?php
/**
 * 文件操作类
 *
 * @author        liu.chang@icntv.tv
 */
class XFile {
	private $serverpath;         //保存web服务器的文档根目录
	private $path;              //保存当前文件系统所操作的目录
	private $pagepath;          //当前脚本页面所在目录
	private $prevpath;          //保存所操作页面的上一级目录
	private $files=array();       //保存当前所操作目录下的文件和目录对象的数组
	private $filenum=0;         //用于统计当前所操作目录下的文件对象的个数
	private $dirnum=0;         //用于统计当前所操作目录下的目录对象的个数
	
	/**
	 * 构造方法，在创建文件系统对象时，初使化文件系统对象的成员属性
	 * 参数path：需要提供所操作目录的目录位置名称，默认为当前目录
	 */
	function __construct($path=".") {
		$this->serverpath = $_SERVER["DOCUMENT_ROOT"]."/";
		$this->path=$path;
		$this->prevpath=dirname($path);
		$this->pagepath=dirname($_SERVER["SCRIPT_FILENAME"]);
		$dir_handle=opendir($path);
		while(false !== ($file = readdir($dir_handle))) {
			if($file!="." && $file!="..") {
				if(is_dir($path.'/'.$file)){
					$tmp= $this->getDirInfo($path,$file);
					$this->dirnum++;
				}
				if(is_file($path.'/'.$file)){
					$tmp= $this->getFileInfo($path,$file);
					$this->filenum++;
				}
				array_push($this->files, $tmp);
				//array_push($this->files, $filename);
			}
		}
		closedir($dir_handle);
	}
	
	/**
	 * 返回根目录
	 * @return string
	 */
	public function getServerPath() {
		return $this->serverpath;
	}
	
	/**
	 * 返回当前目录
	 * @return string
	 */
	public function getPagePath(){
		return $this->pagepath;
	}
	
	/**
	 * 返回上级目录
	 * @return string
	 */
	public function getPrevPath(){
		return $this->prevpath;
	}
	
	/**
	 * 获取所操作目录所在的磁盘空间使用信息
	 * @return stdClass
	 */
	public function getDiskSpace() {
		$disk = new stdClass;
		$disk->total=round(disk_total_space($this->prevpath)/pow(1024,2),2);
		$disk->free=round(disk_free_space($this->prevpath)/pow(1024,2),2);
		$disk->used=$disk->total-$disk->free;
		return $disk;
	}
	
	/**
	 * 获取文件信息
	 * @param unknown $path
	 * @param unknown $file
	 * @return unknown
	 */
	private function getFileInfo($path,$file){
		$filename = $path.'/'.$file;
		$info['name'] = $file;
		$info['type'] = $this->getMime($filename);
		$info['size'] = $this->toSize(filesize($filename));
		$info['ctime'] = date("Y-m-d H:i:s", filectime($filename));
		$info['atime'] = date("Y-m-d H:i:s", fileatime($filename));
		$info['mtime'] = date("Y-m-d H:i:s", filemtime($filename));
		$info['filename'] = $filename;
		return $info;
	}
	
	private function getMime($filename){
		$finfo = finfo_open(FILEINFO_MIME);
		$mimetype = finfo_file($finfo, $filename);
		finfo_close($finfo);
		return $mimetype;
	}
	
	private function getDirInfo($path,$file){
		$filename = $path.'/'.$file;
		$info['name'] = $file;
		$info['type'] = 'directory';
		$info['size'] = $this->toSize($this->dirSize($filename));
		$info['ctime'] = date("Y-m-d H:i:s", filectime($filename));
		$info['atime'] = date("Y-m-d H:i:s", fileatime($filename));
		$info['mtime'] = date("Y-m-d H:i:s", filemtime($filename));
		$info['filename'] = $filename;
		return $info;
	}
	
	
	
	private function dirSize($directory) {
		$dir_size=0;
		if($dir_handle = opendir($directory)) {
			while(false !== ($filename = readdir($dir_handle))) {
				if($filename!="." && $filename!="..") {
					$subFile=$directory."/".$filename;
					if(is_dir($subFile))
						$dir_size+=$this->dirSize($subFile);
					if(is_file($subFile))
						$dir_size+=filesize($subFile);
				}
			}
			closedir($dir_handle);
		}
		return $dir_size;
	}
	
	protected function toSize($bytes) {
		if ($bytes >= pow(2,40)) {
			$return = round($bytes / pow(1024,4), 2);
			$suffix = "TB";
		} elseif ($bytes >= pow(2,30)) {
			$return = round($bytes / pow(1024,3), 2);
			$suffix = "GB";
		} elseif ($bytes >= pow(2,20)) {
			$return = round($bytes / pow(1024,2), 2);
			$suffix = "MB";
		} elseif ($bytes >= pow(2,10)) {
			$return = round($bytes / pow(1024,1), 2);
			$suffix = "KB";
		} else {
			$return = $bytes;
			$suffix = "Byte";
		}
		return $return ." " . $suffix;
	}
	
	/* 访问该方法获取文件系统所操作目录下的文件和目录对象列表，以表格形式输出 */
	public function fileList(){
		return $this->files;
		echo '<table border="0" cellspacing="1" cellpadding="1" width="100%">';
		echo '<tr bgcolor="#b0c4de">';
		echo '<th>类型</th> <th>名称</th> <th>大小</th> <th>修改时间</th> <th>操作</th>';
		echo '</tr>';
		if(is_array($this->files)) {          //判断所操作的目录下是否有文件或是目录存在
			$trcolor="#dddddd";          //初使用化单行背景颜色
			foreach($this->files as $file) {  //遍历数组输出目录和文件信息
				if($trcolor=="#dddddd")  //设置单双行交替背景颜色
					$trcolor="#ffffff";
				else
					$trcolor="#dddddd";
				echo '<tr style="font-size:14px;" bgcolor='.$trcolor.'>';
				echo '<td>'.$file->getType().'</td>';         //输出文件类型
				echo '<td>'.$file->getBaseName().'</td>';     //输出文件名称
				echo '<td>'.$file->getSize().'</td>';          //输出文件大小
				echo '<td>'.$file->getMtime().'</td>';        //输出文件的最后修改时间
				echo '<td>'.$this->operate("contral.php",$file).'</td>'; //输出文件的操作选项
				echo '</tr>';
			}
		}
		echo '</table>';
	}
}