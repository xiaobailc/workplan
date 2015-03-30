/**
 * @author liu.chang@icntv.tv
 * app.js
 */
function com_addCSS(path) {
	var link = document.createElement('link');
	link.type = 'text/css';
	link.rel = 'stylesheet';
	link.href = path;
	document.body.appendChild(link);
}
function com_addJS(path) {
	if (unique && attr.id && $('#' + attr.id).length) {
		if (callback) {
			callback();
		}
		return;
	}
	var script = document.createElement('script');
	script.type = 'text/javascript';
	script.src = path;
	document.body.appendChild(script);
}
function com_hasKey(obj, key) {
	if (key) {
		if (obj[key]) {
			return true;
		}
		return false;
	}
	for (var k in obj) {
		return true;
	}
	return false;
}
function com_formatFileSize(byte) {
	var str = byte + 'B';
	if (byte > 1024 * 1024 * 1024) {
		str = (byte / 1024 / 1024 / 1024).toFixed(2) + 'GB';
	} else if (byte > 1024 * 1024) {
		str = (byte / 1024 / 1024).toFixed(2) + 'MB';
	} else if (byte > 1024) {
		str = (byte / 1024).toFixed(2) + 'KB';
	}
	return str;
}

$(function() {
	/* 定义变量 */
	window.app_currentTemplate = $('#terminal').val();
	window.app_currentScreen = $("#screen").val();
	window.app_currentPanel = '';
	window.app_gridsterChanged = {};
	window.app_gridsterObjects = {};
	window.app_panelDatas = {};
	window.app_tplPanels = {};
	window.app_gridsterOptions = {
		resize: {
			enabled: true,
			start: function(e, ui, $w) {
				this.orgiSize = {
					size_x: $w.attr('data-sizex'),
					size_y: $w.attr('data-sizey')
				};
			},
			stop: function(e, ui, $w) {
				if (this.orgiSize.size_x != $w.attr('data-sizex') || this.orgiSize.size_y != $w.attr('data-sizey')) {
					var gridsterNode = $w.closest('.gridster');
					var gridsterIndex = gridsterNode.data('index');
					app_gridsterChanged[app_currentScreen + '.' + app_currentPanel + '.' + gridsterIndex] = true;
					gridsterNode.find('.btn-save').css('visibility', '').removeAttr('disabled');
					$('.navbar .btn-save-all').show();
				}
			}
		},
		draggable: {
			start: function(e, ui) {
				this.orgiPos = ui.$player.offset();
			},
			stop: function(e, ui) {
				var pos = $('.preview-holder').offset();
				if (this.orgiPos.left != pos.left || this.orgiPos.top != pos.top) {
					var gridsterNode = ui.$player.closest('.gridster');
					var gridsterIndex = gridsterNode.data('index');
					app_gridsterChanged[app_currentScreen + '.' + app_currentPanel + '.' + gridsterIndex] = true;
					gridsterNode.find('.btn-save').css('visibility', '').removeAttr('disabled');
					$('.navbar .btn-save-all').show();
				}
			}
		},
		serialize_params: function($w, wgd) {
			var obj = {
				col: wgd.col,
				row: wgd.row,
				size_x: wgd.size_x,
				size_y: wgd.size_y,
				data: JSON.parse($w.children('input').val())
			};
			return obj;
		}
	};
	
	
	/* 浏览器兼容性判断 */
	var userAgent = navigator.userAgent.toLowerCase();
	if (/msie 8/.test(userAgent)) {
		$('html').addClass('ie8');
		com_myCustomAlert('不怎么支持IE8，微软的浏览器太不靠谱。请使用Chrome、Firefox、IE9+、Chrome内核的浏览器获取更好的体验！');
	} else if (/msie 7/.test(userAgent)) {
		$('html').addClass('ie7');
		com_myCustomAlert('不怎么支持IE7，别让它搞坏了好的心情。请使用Chrome、Firefox、IE9+、Chrome内核的浏览器获取更好的体验！');
	} else if (/msie 6/.test(userAgent)) {
		$('html').addClass('ie6');
		window.alert('不支持IE6，它老爸都已放弃对它的治疗。请使用Chrome、Firefox、IE9+、Chrome内核的浏览器获取更好的体验！');
	}
	
	/*把 data 对象转换成li元素的 data 属性*/
	function createLi(data){
		var cls = '';
		if(!data) return '';
		for(var key in data){
			cls += 'data-__' + key + '="' + data[key] + '" ';
		}
		return cls;
	}
	
	/*更新元素信息，名称，目标，类型，图片，MD5*/
	function updateItemInfo(){
		
	}
	
	/*更新图片信息：大小，像素，名称*/
	function updateImageInfo(box,url,size,w,h){
		box.find('.alert.alert-info').html('大小：' + (size ? com_formatFileSize(size) : 'Unknown') + ' &nbsp; 尺寸：' + w + 'px X ' + h + 'px &nbsp; 名称：' + url.substr(url.lastIndexOf('/') + 1, url.length).split('?_t=')[0]).show();
		if (!size) { // .fileSize only IE supported
			$.ajax({ // Could not cross domain
				url: url,
				async:false,
				type: 'HEAD',
				success: function(data, textStatus, jqXHR) {
					box.find('.alert.alert-info').html(box.find('.alert.alert-info').html().replace('Unknown', com_formatFileSize(jqXHR.getResponseHeader('Content-Length'))));
				}
			});
		}
	}
	
	/* 给每个gridster条目绑定事件 */
	function gridsteraddEvent(gridsterNode, gridsterIndex){
		//鼠标进出事件&双击事件
		$('.gs-w', gridsterNode).off('mouseenter').on('mouseenter', function(e) {
			$(this).addClass('hover');
		}).off('mouseleave').on('mouseleave', function(e) {
			$(this).removeClass('hover');
		}).off('dblclick').on('dblclick', function(e) {
			var tthis = $(this);
			bootbox.dialog({
				title:"元素属性",
				message:function(){
					var domModal = $('.item-modify-template').clone();
					domModal.find('#inputName').attr("value",tthis.attr('data-__name'));
					domModal.find('#inputTarget').attr("value",tthis.attr('data-__target'));
					domModal.find('#inputImage').attr("value",tthis.attr('data-__image'));
					domModal.find('#inputImage_md5').attr("value",tthis.attr('data-__image_md5'));
					var image = tthis.find('img').attr('src') || tthis.attr('data-__image');
					if(tthis.attr('data-__type')){	
						domModal.find('#inputType').find("option[value="+tthis.attr('data-__type')+"]").attr("selected",true);//siblings().attr("selected",false);
					}
					if(image){
						domModal.find('.image.img-thumbnail-x-bs').show().attr({src: image,image_md5: tthis.attr('data-__image_md5')});
						domModal.find('.fileupload-progress-controls').hide();
						var img = tthis.find('img').get(0);
						domModal.find('.alert.alert-info').html('大小：' + (img.fileSize ? com_formatFileSize(img.fileSize) : 'Unknown') + ' &nbsp; 尺寸：' + img.width + 'px X ' + img.height + 'px &nbsp; 名称：' + image.substr(image.lastIndexOf('/') + 1, image.length).split('?_t=')[0]).show();
						if (!img.fileSize) { // .fileSize only IE supported
							$.ajax({ // Could not cross domain
								url: image,
								async:false,
								type: 'HEAD',
								success: function(data, textStatus, jqXHR) {
									domModal.find('.alert-info').html(domModal.find('.alert-info').html().replace('Unknown', com_formatFileSize(jqXHR.getResponseHeader('Content-Length'))));
								}
							});
						}
					}else{
						domModal.find('.image.img-thumbnail-x-bs').hide();
					}
					return domModal.html();
				},
				buttons:{
					main:{
						label: "提交",
						className: "btn-primary",
						callback: function() {
							if ($('.bootbox .fileupload-progress-controls').css('display') == 'block') {
								if ($('.bootbox .btn-upload-cancel').length) {
									//needUpdate = true;
									$('.bootbox .btn-upload-cancel').click();
								}
								return false;
							}
							//更新gridsterIndex
							var inputs = $('.bootbox .form-control');
							
							var tmpdata = JSON.parse(tthis.children('input').val());
							for(var i=0,il=inputs.length;i<il;i++){
								var input = inputs.eq(i);
								var key = input.attr('name');
								tthis.attr('data-__'+key,input.val());
								tmpdata[key] = input.val();
								if(key=='name'){
									if(!input.val()){
										tthis.find('.item-name-bg, .item-name').remove();
									}else{
										if (tthis.find('.item-name').length) {
											tthis.find('.item-name').html(input.val());
										} else {
											tthis.append('<span class="item-name-bg"></span><span class="item-name">' + input.val() + '</span>');
										}
									}
								}
								if(key=='image'){
									if (!input.val()) {
										tthis.find('img').remove();
									}else{
										if (tthis.find('img').length) {
											tthis.find('img').attr('src', input.val());
										} else {
											tthis.append('<img src="' + input.val() + '" alt="" />');
										}
									}
								}
							}
							//更新data数据，gridster序列化时直接使用
							tthis.children('input').val(JSON.stringify(tmpdata));
							
							app_gridsterChanged[app_currentScreen + '.' + app_currentPanel + '.' + gridsterIndex] = true;
							gridsterNode.find('.btn-save').css('visibility', '').removeAttr('disabled');
							$('.navbar .btn-save-all').show();
							
							//bootbox.alert("coding");
							return true;
						}
					},
					cancel:{
						label:"取消",
						className:'btn-default',
					}
				}
			});
			//绑定选择图片
			$('.bootbox #fileupload').fileupload({
				url: window.location.href.split('?')[0]+'?r=admin/terminal/upload',
				dataType: 'json',
				autoUpload: false,
				acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
				maxFileSize: 2000000, // 2 MB
				change: function(e,data){
					//alert('Selected file: ' + data.files[0].name);
					if (data) { // Drop
						var file = data.files[0];
					} else {
						var file = e.target.files ? e.target.files[0] : this.value;
					}
					loadImage(file, function(img) {
						if (img.type == 'error') { // Event
							if (file != fileReference) { // Firefox bug, loadImage callback 3 time, 1 right, 2 error.
								alert('加载图片失败，请检查图片文件或图片链接！');
							}
							return;
						}
						$('.bootbox .preview.img-thumbnail-x-bs').remove();
						$('.bootbox .alert-info').html('大小：' + (file.size ? com_formatFileSize(file.size) : 'Unknown') + ' &nbsp; 尺寸：' + img.width + 'px X ' + img.height + 'px &nbsp; 名称：' + file.name).show();
						$('.bootbox .image.img-thumbnail-x-bs').hide().after($(img).attr({width:false,height:false}).width("500px").addClass('preview img-thumbnail-x-bs'));
						
					});
				},
				add: function(e, data) {
					$('.bootbox .alert-danger').hide();
					$('.bootbox .progress .progress-bar').css('width', '0%');
					$('.bootbox .progress .sr-only').html('0%');
					$('.bootbox .fileupload-progress-controls').show();
					//上传
					$('.bootbox .btn-upload-cancel').off('click').on('click', function(e) {
						$('.bootbox .alert-danger').hide();
						data.submit();
					});
					//删除
					
					$('.bootbox .btn-delete').off('click').on('click', function(e) {
						if (!data.files.length) {
							return;
						}
						data.abort();
						$(".bootbox").find('.alert, .fileupload-progress-controls').hide();
						$('.bootbox .preview.img-thumbnail-x-bs').remove();
						var img = $(".bootbox .image.img-thumbnail-x-bs");
						var image = img.attr('src');
						if (image) {
							img.show();
							$('.bootbox #inputImage').val(image);
							$('.bootbox #inputImage_md5').val(img.attr('image_md5'));
						}
						data.files = [];
						updateImageInfo($('.bootbox'),img.attr('src'),img.get(0).fileSize,img.get(0).width,img.get(0).height);
					});
				},
				done: function(e,data) {
					$('.bootbox .fileupload-progress-controls').hide();
					var file = data.result;
					if (file.error) {
						$('.bootbox .alert-danger').html(file.error).show();
						if (!file.url) {
							return;
						}
					}
					$('.bootbox #inputImage').val(file.pathname).data('d_file', file.pathname);
					$('.bootbox #inputImage_md5').val(file.hash);
				},
				fail: function(e, data) {
					//needUpdate = false;
					if (data.textStatus == 'abort') {
						return;
					}
					$('.bootbox .alert-danger').html(data.textStatus + (data.errorThrown ? ' ' + data.errorThrown : '')).show();
				},
				progressall: function(e, data) {
					var progress = parseInt(data.loaded / data.total * 100, 10);
					$('.bootbox .progress .progress-bar').css('width', progress + '%');
					$('.bootbox .progress .sr-only').html(progress + '%');
				}
			});
		});
		//删除事件
		$('ul .btn-remove', gridsterNode).off('click').on('click', function(e) {
			var node = $(this).parent();
			bootbox.confirm('<h4>确定要删除指定条目？</h4>' + (node.data('__name') ? '<br />' + node.data('__name') : '') + (node.data('__image') ? '<br /><img src="' + node.data('__image') + '" alt="" class="img-thumbnail" />' : ''),function(result){
				if(result){
					app_gridsterObjects[gridsterIndex].remove_widget(node, false, function() {
						app_gridsterChanged[app_currentScreen + '.' + app_currentPanel + '.' + gridsterIndex] = true;
						gridsterNode.find('.btn-save').css('visibility', '').removeAttr('disabled');
						$('.navbar .btn-save-all').show();
					});
				}
			});
		});	
	}
	
	
	/* 分类添加移动 */
	function containerSort(style) {
		var dom = $('.container-x-bs');
		dom.sortable({
			cancel: '.btn',
			handle: '.controls-bar',
			change: function(event, ui) {
				app_gridsterChanged[app_currentScreen + '.' + app_currentPanel + '.__sequence'] = true;
				$('.navbar .btn-save-seq').show();
				$('.navbar .btn-save-all').show();
			}
		})
		.height(Math.max(dom.children().get(0).scrollHeight, document.documentElement.clientHeight - dom.offset().top - 20))
		.width(style);
	};
	
	/* 根据数据创建gridster区域 */
	function gridsterCreate(options,index, serialization){
		var gridsterNode = $('<div class="gridster" data-index="' + index + '" data-name="' + serialization.name + '"><ul></ul>' + $('.controls-template').html() + '</div>').width(serialization.style.width);
		$('.container-x-bs').append(gridsterNode);
		app_gridsterObjects[index] = gridsterNode.children('ul').css('min-height', serialization.style.height).gridster(options).data('gridster');
		
		app_gridsterObjects[index].remove_all_widgets();
		$.each(serialization.data, function() {
			var cls = createLi(this.data);
			app_gridsterObjects[index].add_widget('<li '+cls+'><input type="hidden" name="data" value=\''+JSON.stringify(this.data)+'\' disabled><span class="btn-remove fa fa-times"></span>' + (this.data.image ? '<img src="' + this.data.image + '" alt="" />' : '') + (this.data.name ? '<span class="item-name-bg"></span><span class="item-name">' + this.data.name + '</span>' : '') + '</li>', this.size_x, this.size_y, this.col, this.row);
		});
		gridsteraddEvent(gridsterNode,index);
		
		//---------------以下为事件绑定----------------------------------
		//控制按钮组显示隐藏事件
		$('.gridster').off('mouseenter').on('mouseenter', function(e) {
			$('.controls').hide();
			$('.controls', this).show();
		});
		
		//添加按钮-绑定事件
		$('.controls-bar .btn-add').off('click').on('click', function(e) {
			var gridsterNode = $(this).closest('.gridster');
			var gridsterIndex = gridsterNode.data('index');
			
			var item = {
				data: {
					name: '',
					type: '',
					target: '',
					image: '',
					image_md5: ''
				}
			};
			var cls = createLi(item.data);
			app_gridsterObjects[gridsterIndex].add_widget('<li '+cls+'><input type="hidden" name="data" value=\''+JSON.stringify(item.data)+'\' disabled><span class="btn-remove fa fa-times"></span></li>', 1,1);
			app_gridsterChanged[app_currentScreen + '.' + app_currentPanel + '.' + gridsterIndex] = true;
			gridsterNode.find('.btn-save').css('visibility', '').removeAttr('disabled');
			$('.navbar .btn-save-all').show();
			gridsteraddEvent(gridsterNode, gridsterIndex);
		});
		
		//删除按钮-绑定事件
		$('.controls-bar .btn-remove').off('click').on('click', function(e) {
			var gridsterNode = $(this).closest('.gridster');
			var gridsterIndex = gridsterNode.data('index');
			bootbox.confirm('<h4>确定要删除分组</h4><br/>' + app_panelDatas[app_currentPanel][gridsterIndex].name + '（' + gridsterIndex + '）？',function(result){
				if(result){
					var g_width = app_panelDatas[app_currentPanel][gridsterIndex].style.width;
					var g_margin_r = parseInt($('.gridster').css('margin-right'), 10);
					var c_width = $('.container-x-bs').width();
					app_gridsterObjects[gridsterIndex].destroy();
					delete app_gridsterObjects[gridsterIndex];
					gridsterNode.remove();
					app_gridsterChanged[app_currentScreen + '.' + app_currentPanel + '.' + gridsterIndex] = true;
					$('.navbar .btn-save-all').show();
					$('.container-x-bs').width(c_width - g_width - g_margin_r);
					delete app_panelDatas[app_currentPanel][gridsterIndex];
				}
			});
		});
		//修改按钮-绑定事件
		$('.controls-bar .btn-edit').off('click').on('click', function(e) {
			var gridsterNode = $(this).closest('.gridster');
			var gridsterIndex = gridsterNode.data('index');
			var base_dimension = app_panelDatas[app_currentPanel].options.widget_base_dimensions;
			var base_margin = app_panelDatas[app_currentPanel].options.widget_margins;
			var size_col = parseInt(gridsterNode.width()/(base_dimension[0]+base_margin[0]+base_margin[0]));
			var size_row = parseInt(parseInt(gridsterNode.children('ul').css('min-height'),10)/(base_dimension[1]+base_margin[1]+base_margin[1]));
			
			//modal中分组属性输入框联动效果
			$(document).on('input propertychange','.bootbox #inputSizeC',function(){
				$('.bootbox #inputSizeW').val($(this).val()*(base_dimension[0]+base_margin[0]+base_margin[0]))
			});
			$(document).on('input propertychange','.bootbox #inputSizeR',function(){
				$('.bootbox #inputSizeH').val($(this).val()*(base_dimension[1]+base_margin[1]+base_margin[1]))
			});
			
			bootbox.dialog({
				title:"分组属性",
				message:function(){
					var domModal = $('.group-add-template');
					domModal.find('#inputName').attr("value",gridsterNode.attr('data-name'));
					domModal.find('#inputKey').attr("value",gridsterIndex).attr({disabled:true});
					domModal.find('#inputSizeC').attr("value",size_col);
					domModal.find('#inputSizeR').attr("value",size_row);
					domModal.find('#inputSizeW').attr("value",gridsterNode.width());
					domModal.find('#inputSizeH').attr("value",parseInt(gridsterNode.children('ul').css('min-height'),10));
					return domModal.html();
				},
				buttons:{
					main:{
						label: "提交",
						className: "btn-primary",
						callback: function() {
							var name = $('.bootbox').find('#inputName').val();
							var key = $('.bootbox').find('#inputKey').val();
							var w = $('.bootbox').find('#inputSizeW').val();
							var h = $('.bootbox').find('#inputSizeH').val();
							if (!name || !key || !w || !h) {
								return;
							}
							var width = app_panelDatas[app_currentPanel][gridsterIndex].style.width;
							var tmp_data = {
								name: name,
								style: {
									width: parseInt(w, 10),
									height: parseInt(h, 10)
								},
							};
							
							//改变名称
							if(gridsterNode.data('name') != name){
								gridsterNode.attr('data-name',name);
							}
							//改变高度
							if(gridsterNode.width()!=tmp_data.style.width || parseInt(gridsterNode.children('ul').css('min-height'),10) != tmp_data.style.height){
								var options = app_gridsterObjects[gridsterIndex].options;
								//清除gridster对象
								app_gridsterObjects[gridsterIndex].remove_style_tags();		
								app_gridsterObjects[gridsterIndex].remove_all_widgets();
								delete app_gridsterObjects[gridsterIndex];
								gridsterNode.width(w);
								gridsterNode.children('ul').remove();
								gridsterNode.append('<ul></ul>');
								app_gridsterObjects[gridsterIndex] = gridsterNode.children('ul').css("min-height", h+"px").gridster(options).data('gridster');
								$.each(app_panelDatas[app_currentPanel][gridsterIndex].data, function() {
									var cls = createLi(this.data);
									app_gridsterObjects[gridsterIndex].add_widget('<li '+cls+'><input type="hidden" name="data" value=\''+JSON.stringify(this.data)+'\' disabled><span class="btn-remove fa fa-times"></span>' + (this.data.image ? '<img src="' + this.data.image + '" alt="" />' : '') + (this.data.name ? '<span class="item-name-bg"></span><span class="item-name">' + this.data.name + '</span>' : '') + '</li>', this.size_x, this.size_y, this.col, this.row);
								});
								gridsteraddEvent(gridsterNode,gridsterIndex);
								$('.container-x-bs').css({width: $('.container-x-bs').width() - width + tmp_data.style.width});
							}
							
							
							app_panelDatas[app_currentPanel][gridsterIndex] = $.extend(true,{},app_panelDatas[app_currentPanel][gridsterIndex],tmp_data);
							
							app_gridsterChanged[app_currentScreen + '.' + app_currentPanel + '.' + gridsterIndex] = true;
							gridsterNode.find('.btn-save').css('visibility', '').removeAttr('disabled');
							$('.navbar .btn-save-all').show();
						}
					},
					cancel:{
						label:"取消",
						className:'btn-default',
					}
				}
			});
		});
		//保存按钮-绑定事件
		$('.controls-bar .btn-save').off('click').on('click', function(e) {
			var tthis = $(this);
			tthis.attr({disabled: true});
			var gridsterNode = $(this).closest('.gridster');
			var gridsterIndex = gridsterNode.data('index');
			var obj = {
				name: gridsterNode.attr('data-name'),
				style: {
					width: gridsterNode.width(),
					height: parseInt(gridsterNode.children('ul').css('min-height'), 10)
				},
				data: Gridster.sort_by_row_and_col_asc(app_gridsterObjects[gridsterIndex].serialize())
			};
			$.ajax({
				url: window.location.href.split('?')[0]+'?r=admin/terminal/dealdata',
				data: {
					id:$('#tplid').val(),
					type:"update_panel_index",
					panel:app_currentPanel,
					index:gridsterIndex,
					data:JSON.stringify(obj)
				},
				type: 'POST',
				success: function(data, textStatus, jqXHR) {
					if(data.error){
						alert(data.error);
						tthis.removeAttr('disabled');
						return;
					}
					delete app_gridsterChanged[app_currentScreen + '.' + app_currentPanel + '.' + gridsterIndex];
					app_panelDatas[app_currentPanel][gridsterIndex] = obj;
					gridsterNode.find('.btn-save').css('visibility', 'hidden').removeAttr('disabled');
					if (!com_hasKey(app_gridsterChanged)) {
						$('.navbar .btn-save-all').hide();
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					alert((jqXHR.responseJSON ? jqXHR.responseJSON.message : 'Error') + '\n\n' + jqXHR.status + (errorThrown ? ' ' + errorThrown : ''));
				}
			});
		});
	}
	
	/* 频道内容展示 */
	function showPanel(){
		window.location.replace(window.location.href.split('#')[0] + '#panel=' + app_currentPanel);
		
		//清空内容区
		$('.container-x-bs').empty().removeClass().addClass('container-x-bs ' + app_currentPanel);
		app_gridsterObjects = {};
		
		var data = app_panelDatas[app_currentPanel];
		if(data && data.options && com_hasKey(data.__sequence)){
			//获取gridster 的 option
			var options = data.options;
			options = $.extend(true, {}, app_gridsterOptions, options);
			var int_width = 0;
			for (var key in data.__sequence) {
				int_width += data[data.__sequence[key]].style.width;
				gridsterCreate(options,data.__sequence[key], data[data.__sequence[key]]);
			}
			
			//获取内容区宽度
			var margin_right = parseInt($('.gridster').css('margin-right'));
			int_width = int_width + data.__sequence.length*margin_right;
			//为分类添加移动效果
			containerSort(int_width);
		}
		showDropdownMenu();
	}
	
	/* 获取模板内容数据 */
	function getPanelData(){
		$.ajax({
			url: window.location.href.split('?')[0]+'?r=admin/terminal/dealdata',
			data: {
				id:$('#tplid').val(),
				type:"get_tpl_data",
			},
			type: 'POST',
			async:false,
			dataType: 'json',
			success: function(data, textStatus, jqXHR) {
				if(data.error){
					alert(data.error);
					return false;
				}
				app_panelDatas = data;
			},
			error: function(jqXHR, textStatus, errorThrown) {
				app_panelDatas = {};
			}
		});
		/*
		$.ajax({
			url: 'partials/' + app_currentTemplate + '/' + app_currentScreen + '/data.js?_t=' + (new Date()).getTime(),
			//url: 'partials/' + app_currentTemplate + '/' + app_currentScreen + '/data1.js?_t=' + (new Date()).getTime(),
			type: 'GET',
			async:false,
			dataType: 'json',
			success: function(data, status, xhr) {
				app_panelDatas = data;
				//showPanel();
			},
			error: function(data,status,xhr){
				app_panelDatas = {};
				//showPanel();
			}
		});
		*/
	}
	
	function setGristerOption(title,from){
		bootbox.dialog({
			title:title,
			message:function(){
				return $('.gridster-option-template').html();
			},
			buttons:{
				main:{
					label: "提交",
					className: "btn-primary",
					callback: function() {
						var tmpoption = {};
						var inputs = $(".bootbox :text");
						for(var i=0,il=inputs.length;i<il;i++){
							if(!inputs.eq(i).val()) return false;
						}
						$(".bootbox :text").each(function(){
							var key = $(this).attr("id").split('-');
							var il = key.length;
							if(il==2){
								if(!tmpoption[key[0]]){
									tmpoption[key[0]] = [];
								}
								tmpoption[key[0]].push(parseInt($(this).val(),10));
							}else if(il==3){
								if(!tmpoption[key[0]]){
									tmpoption[key[0]] = {};
								}
								if(!tmpoption[key[0]][key[1]]){
									tmpoption[key[0]][key[1]] = [];
								}
								tmpoption[key[0]][key[1]].push(parseInt($(this).val(),10));
							}
						});
						app_panelDatas[app_currentPanel] = {};
						app_panelDatas[app_currentPanel].options = tmpoption;
						if(from=="add"){
							app_panelDatas[app_currentPanel].__sequence = [];
						}
						//更新数据库
						$.ajax({
							url: window.location.href.split('?')[0]+'?r=admin/terminal/dealdata',
							async:false,
							data: {
								id:$("#tplid").val(),
								type:"add_panel_option",
								panel:app_currentPanel,
								data:JSON.stringify(app_panelDatas[app_currentPanel])
							},
							type: 'POST',
							//dataType: 'json',
							success: function(data, textStatus, jqXHR) {
								if(data.error){
									alert(data.error);
									app_panelDatas = {};
									return false;
								}
								if(from=="add"){
									$('.navbar .btn-add').click();
								}else if (from=="set"){
									window.location.reload();
								}
								return true;
							},
							error: function(jqXHR, textStatus, errorThrown) {
								alert((jqXHR.responseJSON ? jqXHR.responseJSON.message : 'Error') + '\n\n' + jqXHR.status + (errorThrown ? ' ' + errorThrown : ''));
								app_panelDatas = {};
								return false;
							}
						});
						
					}
				},
				cancel:{
					label:"取消",
					className:'btn-default',
				}
			}
		});
		$('.bootbox .modal-dialog').addClass('modal-lg');
	}
	
	/* 导航栏添加下拉菜单 */
	function showDropdownMenu(){
		$('.navbar-nav .dropdown-menu, .navbar .caret').remove();
		$('.navbar-nav .dropdown').removeClass('dropdown');
		$('.navbar-nav .active').append($('.group-controls-template').html()).addClass('dropdown').children('a').append('<span class="caret"></span>');
		//鼠标进入控制栏隐藏事件
		$('.navbar').off('mouseenter').on('mouseenter', function(e) {
			$('.controls').hide();
		});
		//修改属性事件
		$('.navbar .btn-opt').off('click').on('click', function(e) {
			e.stopPropagation();
			if(com_hasKey(app_gridsterChanged)){
				bootbox.alert("<h4>请先保存全部数据再设置参数</h4>");
				return false;
			}
			setGristerOption("参数设置","set");
		});
		//添加分组事件
		$('.navbar .btn-add').off('click').on('click', function(e) {
			e.stopPropagation();
			if(!app_panelDatas[app_currentPanel]){
				setGristerOption("还未配置画图区参数，请先设置！","add");
				return false;
			}
			
			//modal中分组属性输入框联动效果
			var base_dimension = app_panelDatas[app_currentPanel].options.widget_base_dimensions;
			var base_margin = app_panelDatas[app_currentPanel].options.widget_margins;
			$(document).on('input propertychange','.bootbox #inputSizeC',function(){
				$('.bootbox #inputSizeW').val($(this).val()*(base_dimension[0]+base_margin[0]+base_margin[0]))
			});
			$(document).on('input propertychange','.bootbox #inputSizeR',function(){
				$('.bootbox #inputSizeH').val($(this).val()*(base_dimension[1]+base_margin[1]+base_margin[1]))
			});
			bootbox.dialog({
				title:"分组属性",
				message:function(){
					return $('.group-add-template').html();
				},
				buttons:{
					main:{
						label: "提交",
						className: "btn-primary",
						callback: function() {
							var name = $('.bootbox').find('#inputName').val();
							var key = $('.bootbox').find('#inputKey').val();
							var w = $('.bootbox').find('#inputSizeW').val();
							var h = $('.bootbox').find('#inputSizeH').val();
							var c_width = $('.container-x-bs').width();
							var options = app_panelDatas[app_currentPanel].options;
							if (!name || !key || !w || !h) {
								return false;
							}
							if (app_panelDatas[app_currentPanel][key]) {
								bootbox.alert('<h4>键值“<b>' + key + '</b>”已经存在，请换一个试试。</h4>');
								return false;
							}
							
							app_panelDatas[app_currentPanel][key] = {
								name: name,
								style: {
									width: parseInt(w, 10),
									height: parseInt(h, 10)
								},
								data:[]
							};
							app_panelDatas[app_currentPanel].__sequence.push(key);
							options = $.extend(true, {}, app_gridsterOptions, options);
							gridsterCreate(options, key, app_panelDatas[app_currentPanel][key]);
							//为分类添加移动效果
							
							containerSort(c_width + parseInt($('.gridster').css('margin-right'), 10) + app_panelDatas[app_currentPanel][key].style.width);
							app_gridsterObjects[key].$wrapper.find('.btn-save').css('visibility', '').removeAttr('disabled');
							app_gridsterChanged[app_currentScreen + '.' + app_currentPanel + '.' + key] = true;
							$('.navbar .btn-save-all').show();
						}
					},
					cancel:{
						label:"取消",
						className:'btn-default',
					}
				}
			});
		});
		//保存顺序事件
		$('.navbar .btn-save-seq').off('click').on('click', function(e) {
			e.stopPropagation();
			alert('coding');
		});
		//全部保存事件
		$('.navbar .btn-save-all').off('click').on('click', function(e) {
			e.stopPropagation();
			//alert('coding');
			if ($(this).hasClass('disabled')) {
				return;
			}
			$(this).addClass('disabled');
			var obj = {};
			$('.gridster').each(function(index, element) {
				var gridsterNode = $(element);
				var gridsterIndex = gridsterNode.data('index');
				if(app_gridsterChanged[app_currentScreen + '.' + app_currentPanel + '.' + gridsterIndex]){
					obj[gridsterIndex] = {
						name: gridsterNode.data('name'),
						style: {
							width: gridsterNode.width(),
							height: parseInt(gridsterNode.children('ul').css('min-height'), 10)
						},
						data: Gridster.sort_by_row_and_col_asc(app_gridsterObjects[gridsterIndex].serialize())
					};
				}
			});
			$.ajax({
				url: window.location.href.split('?')[0]+'?r=admin/terminal/dealdata',
				data: {
					id:$('#tplid').val(),
					type:"update_panel_all",
					panel:app_currentPanel,
					data:JSON.stringify(obj)
				},
				type: 'POST',
				dataType: 'json',
				success: function(data, textStatus, jqXHR) {
					if(data.error){
						alert(data.error);
						tthis.removeAttr('disabled');
						return;
					}
					app_gridsterChanged = {};
					for(key in obj){
						app_panelDatas[app_currentPanel][key] = obj[key];
					}
					$('.navbar .btn-save-seq').hide();
					$('.navbar .btn-save-all').hide().removeClass('disabled');
					$('.controls-bar .btn-save').css('visibility', 'hidden').removeAttr('disabled');
				},
				error: function(jqXHR, textStatus, errorThrown) {
					alert((jqXHR.responseJSON ? jqXHR.responseJSON.message : 'Error') + '\n\n' + jqXHR.status + (errorThrown ? ' ' + errorThrown : ''));
				}
			});
		});
	}
	
	/* 内容展示 */
	function showContent(){
		getPanelData();
		app_currentPanel = $(".navbar-nav .active").attr("data-slug");
		var queryPanel = $.query.get('panel');
		//判断url地址栏中的panel值是否存在，存在则切换栏目，不存在则显示默认栏目
		var hasPanel = false;
		if (queryPanel) {
			$('.navbar .nav li').each(function(){
				if($(this).data('slug')==queryPanel){
					hasPanel = true;
					if($(this).hasClass('active')){return;}
					$(this).addClass('active').siblings().removeClass('active');
				}
			});
			if(hasPanel){
				app_currentPanel = queryPanel;
			}
		}
		
		//清空内容区数据
		/*
		for (var key in app_gridsterObjects) {
			app_gridsterObjects[key].destroy();
		}
		$('.container-x-bs').empty();
		$('.template-link, .template-script').remove();
		*/
		//动态加载css 布局完成后再加载 保证效果
		//com_addCSS('partials/'+ app_currentTemplate + '/style.css');
		
		//绑定菜单栏点击事件
		$('.navbar .nav li').off('click').on('click', function(e) {
			var tthis = $(this);
			if (tthis.hasClass('active')) {
				return;
			}
			if (com_hasKey(app_gridsterChanged)) {
				bootbox.confirm('<h4>还有修改未保存，继续面板切换？</h4><br/>可点击当前面板菜单（' + $('.navbar .active').data('name') + '）下的“全部保存”快速保存。',function(result){
					if(result){
						app_gridsterChanged = {};
						tthis.addClass('active').siblings().removeClass('active');
						app_currentPanel = tthis.attr("data-slug")
						showPanel();
					}else{
						return;
					}
				});
			}else{
				tthis.addClass('active').siblings().removeClass('active');
				app_currentPanel = tthis.attr("data-slug")
				showPanel();
			}
		});
		//显示栏目
		showPanel();
	}
	
	// Init
	showContent();

	$(window).on('scroll', function() {
		$('.navbar').css({
			left: $(this).scrollLeft()
		});
	}).on('mousewheel DOMMouseScroll', function(e) { // mousewheel scroll x axis (chrome default: shift + mousewheel)
		if ($(document.body).hasClass('modal-open')/* || !e.shiftKey*/) {
			return;
		}
		var scrollTop = $(this).scrollTop();
		var wheelDelta = e.originalEvent.detail ? e.originalEvent.detail * -40 : e.originalEvent.wheelDelta;
		var scrollHeight = e.originalEvent.detail ? document.documentElement.scrollHeight : document.body.scrollHeight;
		if ((scrollTop == 0 && wheelDelta > 0) || (scrollTop == (scrollHeight - document.documentElement.clientHeight) && wheelDelta < 0)) {
			$(this).scrollLeft($(this).scrollLeft() - wheelDelta);
		}
	}).on('beforeunload',function(){
		if (com_hasKey(app_gridsterChanged)) {
			var str = '还有修改未保存。\n\n可点击当前面板菜单（' + $('.navbar .active').data('name') + '）下的“全部保存”快速保存。';
			return str;
		}
	});
});
