<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Metro Controller</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->_baseUrl?>/static/lib/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->_baseUrl?>/static/lib/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->_baseUrl?>/static/lib/gridster/jquery.gridster.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->_baseUrl?>/static/lib/fileupload/css/jquery.fileupload.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->_baseUrl?>/static/lib/jcrop/css/jquery.Jcrop.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->_baseUrl?>/static/admin/css/app.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->_baseUrl?>/static/admin/css/1common.css" />
</head>
<body>
<!-- <div class="tv tv720"></div> -->
<nav class="navbar navbar-default navbar-static-top" role="navigation">
	<div class="container-fluid">
		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
<?php foreach ($nav as $key=>$row):?>
				<li class="<?php echo ($key==0)?'active':''?>" data-name="<?php echo $row->title?>" data-slug="<?php echo $row->key?>">
					<a href="javascript:;"><?php echo $row->title?></a>
				</li>
<?php endforeach;?>
			</ul>
			<div class="navbar-form navbar-left">
				<a onclick="window.open('<?php echo Yii::app()->request->getUrl();?>')" class="btn btn-default " style="float:#left;">新窗口编辑</a>
				<label>【友情提示：按<kbd>F11</kbd>进入全屏模式】</label>
			</div>
			<form class="navbar-form navbar-right" role="search">
				<label>标签：</label>
				<input type="hidden" id="tplid" value="<?php echo $template->id?>" >
				<input type="button" class="btn btn-default disabled" id="terminal" value="<?php echo $terminal->partial?>" disabled>
				<input type="button" class="btn btn-default disabled" id="screen" value="<?php echo $template->screen?>" disabled>
				<input type="button" class="btn btn-default disabled" id="page" value="panel" disabled>
			</form>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>
<!-- TV border -->
<div id="wrap">
	<div class="container-x-bs"></div>
</div>
<!-- 分组控制模板页 -->
<div class="group-controls-template hide">
	<ul class="group-controls dropdown-menu" role="menu">
		<li class="btn-add">
			<a href="javascript:;">添加分组</a>
		</li>
		<li class="btn-opt">
			<a href="javascript:;">设置参数</a>
		</li>
		<li class="btn-save-all" style="display: none;">
			<a href="javascript:;">全部保存</a>
		</li>
		<li class="btn-save-seq" style="display: none;">
			<a href="javascript:;">保存排序</a>
		</li>
	</ul>
</div>
<!-- 分组控制按钮组：添加 删除 修改 保存 -->
<div class="controls-template hide">
	<div class="controls-bar">
		<div class="controls" style="display: none;">
			<span class="btn btn-success btn-add">
				<i class="fa fa-plus"></i>
				<span>添加</span>
			</span>
			<span class="btn btn-danger btn-remove">
				<i class="fa fa-trash-o"></i>
				<span>删除</span>
			</span>
			<span class="btn btn-info btn-edit">
				<i class="fa fa-edit"></i>
				<span>修改</span>
			</span>
			<span class="btn btn-primary btn-save" style="visibility: hidden;">
				<i class="fa fa-save"></i>
				<span>保存</span>
			</span>
		</div>
	</div>
</div>
<!-- gridster参数设置模板页 -->
<div class="gridster-option-template hide">
	<form class="form-horizontal fileupload" role="form" method="POST" enctype="multipart/form-data">
		<div class="form-group has-feedback">
			<label class="col-md-2 control-label">元素尺寸</label>
			<label for="widget_base_dimensions-0" class="col-md-1 control-label">宽</label>
			<div class="col-md-4">
				<input type="text" class="form-control" id="widget_base_dimensions-0" placeholder="ep: 195" />
				<span class="form-control-feedback">px</span>
			</div>
			<label for="widget_base_dimensions-1" class="col-md-1 control-label">高</label>
			<div class="col-md-4">
				<input type="text" class="form-control" id="widget_base_dimensions-1" placeholder="ep: 260" />
				<span class="form-control-feedback">px</span>
			</div>
		</div>
		<div class="form-group has-feedback">
			<label class="col-md-2 control-label">元素边缘</label>
			<label for="widget_margins-0" class="col-md-1 control-label">水平</label>
			<div class="col-md-4">
				<input type="text" class="form-control" id="widget_margins-0" placeholder="ep: 5" />
				<span class="form-control-feedback">px</span>
			</div>
			<label for="widget_margins-1" class="col-md-1 control-label">垂直</label>
			<div class="col-md-4">
				<input type="text" class="form-control" id="widget_margins-1" placeholder="ep: 5" />
				<span class="form-control-feedback">px</span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-2 control-label">调整最大值</label>
			<label for="resize-max_size-0" class="col-md-1 control-label">列</label>
			<div class="col-md-4">
				<input type="text" class="form-control" id="resize-max_size-0" placeholder="ep: 2" />
			</div>
			<label for="resize-max_size-1" class="col-md-1 control-label">行</label>
			<div class="col-md-4">
				<input type="text" class="form-control" id="resize-max_size-1" placeholder="ep: 2" />
			</div>
		</div>
	</form>
</div>
<!-- 添加分组模板页 -->
<div class="group-add-template hide">
	<form class="form-horizontal fileupload" role="form" method="POST" enctype="multipart/form-data">
		<div class="form-group">
			<label for="inputName" class="col-md-2 control-label">分组名称</label>
			<div class="col-md-10">
				<input type="text" class="form-control" id="inputName" placeholder="ep: 电影" />
			</div>
		</div>
		<div class="form-group">
			<label for="inputKey" class="col-md-2 control-label">分组键值</label>
			<div class="col-md-10">
				<input type="text" class="form-control" id="inputKey" placeholder="ep: movie" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-2 control-label">分区尺寸</label>
			<label for="inputSizeW" class="col-md-1 control-label">列：</label>
			<div class="col-md-4">
				<input type="text" class="form-control" id="inputSizeC" placeholder="ep: 4" />
			</div>
			<label for="inputSizeH" class="col-md-1 control-label">行：</label>
			<div class="col-md-4">
				<input type="text" class="form-control" id="inputSizeR" placeholder="ep: 2" />
			</div>
		</div>
		<div class="form-group has-feedback">
			<label class="col-md-2 control-label">分区像素</label>
			<label for="inputSizeW" class="col-md-1 control-label">宽：</label>
			<div class="col-md-4">
				<input type="text" class="form-control" id="inputSizeW" disabled/>
				<span class="form-control-feedback">px</span>
			</div>
			<label for="inputSizeH" class="col-md-1 control-label">高：</label>
			<div class="col-md-4">
				<input type="text" class="form-control" id="inputSizeH" disabled/>
				<span class="form-control-feedback">px</span>
			</div>
		</div>
		<!-- <button type="button" class="btn btn-primary pull-right"> 提交 <i class="fa fa-check"></i></button> -->
	</form>
</div>
<!-- 条目编辑模板页 -->
<div class="item-modify-template hide">
	<p><img src="" alt="" class="image img-thumbnail-x-bs" width="500"/></p>
	<span class="fileupload-process"></span>
	<div class="alert alert-info" style="display: none;">&nbsp;</div>
	<div class="alert alert-danger" style="display: none;">&nbsp;</div>
	
	<form class="form-horizontal fileupload" role="form" method="POST" enctype="multipart/form-data">
		<div class="form-group">
			<div class="fileupload-progress-controls" style="display: none;padding-left:15px">
				<div class="progress progress-striped col-md-8">
					<div class="progress-bar progress-bar-success" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
						<span class="sr-only">0%</span>
					</div>
				</div>
				<div class="btn-upload-cancel-remove col-md-4" style="text-align: right;">
					<span class="btn btn-info btn-upload-cancel">
						<i class="fa fa-upload"></i>
						<span>上传</span>
					</span>
					<span class="btn btn-danger btn-delete">
						<i class="fa fa-trash-o"></i>
						<span>移除</span>
					</span>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="inputName" class="col-md-2 control-label">元素名称</label>
			<div class="col-md-10">
				<input type="text" class="form-control" name="name" id="inputName" />
			</div>
		</div>
		<div class="form-group">
			<label for="inputTarget" class="col-md-2 control-label">元素目标</label>
			<div class="col-md-10">
				<input type="text" class="form-control" name="target" id="inputTarget" placeholder="ep: 12345" />
			</div>
		</div>
		<div class="form-group">
			<label for="inputTarget" class="col-md-2 control-label">元素类型</label>
			<div class="col-md-10">
				<select class="form-control" name="type" id="inputType" >
					<option value="detail">详情</option>
					<option value="class">分类</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="inputImage" class="col-md-2 control-label">图片</label>
			<div class="col-md-8">
				<input type="text" class="form-control" name="image" id="inputImage" placeholder="ep: path/file.png or http://a.b.c/path/file.jpg" readonly="readonly" />
				<input type="hidden" class="form-control" name="image_md5" id="inputImage_md5" />
			</div>
			<div class="col-md-2">
				<span class="btn btn-success fileinput-button">
					<i class="fa fa-plus"></i>
					<span>选取</span>
					<input id="fileupload" type="file" name="image" accept="image/gif, image/jpeg, image/png" />
				</span>
			</div>
			<span class="col-md-12">图像参考尺寸大小：<i class="remark"></i></span>
		</div>
		<input type="hidden" name="terminal" value="<?php echo $terminal->partial?>" />
		<input type="hidden" name="screen" value="<?php echo $template->screen?>" />
		<input type="hidden" name="page" value="panel" />
	</form>
</div>
<!-- 模态框模板 -->
<div id="defaultModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title">Modal title</h4>
			</div>
			<div class="modal-body">Model body</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Scripts, Placed at the end of the document so the pages load faster -->
<!-- jQuery && jQuery UI(sortable) -->
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/js/jquery.min.js" ></script>
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/lib/jquery-ui/ui/minified/jquery-ui.min.js" ></script>
<!-- Bootstrap -->
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/lib/bootstrap/js/bootstrap.min.js" ></script>
<!-- Bootbox -->
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/lib/bootbox.js" ></script>
<!-- Gridster -->
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/lib/gridster/jquery.gridster.js" ></script>
<!-- jQuery Query -->
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/lib/jquery.query.js"></script>
<!-- Drag scrollable -->
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/lib/dragscrollable.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/lib/loadimage/js/load-image.min.js"></script>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/lib/fileupload/js/vendor/jquery.ui.widget.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/lib/fileupload/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/lib/fileupload/js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/lib/fileupload/js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/lib/fileupload/js/jquery.fileupload-image.js"></script>
<!-- The File Upload validation plugin -->
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/lib/fileupload/js/jquery.fileupload-validate.js"></script>
<!-- Query Jcrop -->
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/lib/jcrop/js/jquery.Jcrop.min.js"></script>
<!-- App -->
<script type="text/javascript" src="<?php echo $this->_baseUrl?>/static/admin/js/app.js"></script>
</body>
</html>
