jQuery(function($){
	(function($){
		$.widget('video.page', {
	        options : {
	        	total_num : 0,
	        	total_page : 0,
	        	current_page : 0,
	        	page_num : 40,
	        	prev_page:0,
	        	next_page:0,
	        	last_page:0
	        },

	        _create : function(){
	            this._createBox();
	        },

	        _init : function(){
	            this._on({
	                'click span[_page]' : '_click'
	            });
	        },

	        _createBox : function(){
	            var op = this.options;
	            var cp = parseInt(op.current_page);
	            var tp = op.total_page;
	            var prev_page=op.prev_page;
	            var next_page=op.next_page;
	            if(tp < 2){
	                this.element.hide();
	                return;
	            }
	            var html = '';
	            html+='<span class="prev-btn" _start=' + prev_page + ' _page=' +(cp-1) + '><em></em></span>';
	            html+='<span class="new-current">第' + cp + '页/</span>'; 
	            html += '<span class="new-page-all">共' + tp + '页</span>';
	            html += '<span class="next-btn" _start=' + next_page + '  _page=' +(cp+1)+ '><em></em></span>';
	            this.element.html(html);
	        },

	        _click : function(event){
	        	var self=$(event.currentTarget)
	        	var start = self.attr('_start');
	            var page = self.attr('_page');
	            var pages=this.options['total_page'];
	            if(page==0 || page>pages){
	            	return;
	            }
	            this._trigger('page', null, [start]);
	        },

	        show : function(){
	            this.element.show();
	        },

	        hide : function(){
	            this.element.hide();
	        },

	        refresh : function(option){
	            this.show();
	            $.extend(this.options, option);
	            this._createBox();
	        }
	    });
		
		$.widget('special.special_form',{
			options:{
				title : '#title',
				indexPic : '.indexpic-box',
				indexFile : '#Filedata',
				bigPic : '.special-bigpic',
				bigPicFile : '#bigFiledata',
				bigPicflag : '.bigpic-flag',
				client_index : '.client_logo_item_add',
				clientlist: '.client-list',
				client_file_area :'.client-file-area',
				client_file: '.client-file',
				client_file_data : '.file-data',
				client_file_input : '.file-input',
				client_file_delete:'.client_logo_delete',
				client_box : '.client-box',
				briefArea : '#special-brief-area',
				brief_tpl : '#brief-tpl',
				brief_del :'.brief-del',
				addButton : '.add',
				save:'#special-save',
				special_form:'#special-form',
				attachment : '#attach-upload',
				attachmentFile : '#attachment-file',
				attach_tpl : '#attach-tpl',
				attach_area: '.attachment-view',
				attach_del : '.attach-del',
				conTab : '.con-tab',
				attachhiddenid_tpl:'#attachhiddenid-tpl',
				attachid_hidden:'.attachid-hidden',
				attach_tab:'.attach-tab',
				vedio_upload:'#vedio-upload',
				vedio_file_box:'.vedio-file-box',
				file_close:'.file-close',
				file_list:'.common-vod-area',
				file_list_li:'.common-vod-area .file-list-li',
				page_link:'.common-page-link',
				file_list_tpl:'#file-list-li-tpl',
				vedio_view_tpl:'#vedio-view-tpl',
				vedio_view_list:'.vedio-view-list',
				button_mask:'.button-mask',
				vedio_del:'.vedio-view-list .attach-del',
				vedio_search_form:'#vedio-search-form',
				key_search:'#key-search',
				loading: '#top-loading',
				loading2: '#top-loading2',
				result_tip : '.result-tip'
			},
			_create:function(){
				this.attach_area=this.element.find(this.options['attach_area']);
				this.attachid_hidden=this.element.find(this.options['attachid_hidden']);
				this.loading=this.element.find(this.options['loading']);
				this.loading2=this.element.find(this.options['loading2']);
				this.status=false;
				this.callback=false;
			},
			_init:function(){
				var handlers={};
				handlers['click '+this.options['client_index']] ='_toggleClient';
				handlers['click '+this.options['client_file_delete']] ='_delclientPic';
				handlers['click '+this.options['indexPic']] ='_setIndexPic';
				handlers['click '+this.options['bigPic']] ='_setbigPic';
				handlers['click '+this.options['addButton']] ='_addBrief';
				handlers['click '+this.options['brief_del']] ='_delBrief';
				handlers['click '+this.options['conTab']] ='_referTip';
				handlers['change '+this.options['indexFile']] ='_changefile';
				handlers['change '+this.options['bigPicFile']] ='_changefile';
				//handlers['change '+this.options['client_file_data']] ='_changefile';
				handlers['click '+this.options['attachment']] ='_attachupload';
				handlers['change '+this.options['attachmentFile']] ='_attachfile';
				handlers['click '+this.options['attach_tab']] ='_attach_tab';
				handlers['click '+this.options['vedio_upload']] ='_vedioupload';
				handlers['click '+this.options['file_list_li']] ='_addVedio';
				handlers['click '+this.options['file_close']] ='_closevedioupload';
				handlers['click '+this.options['vedio_del']] ='_delvedio';
				handlers['click '+this.options['key_search']] ='_triggersearchvedio';
				this._on(handlers);
				this._ajaxForm();
				this._delAttach();
				this._initClient();
				this._handlerBrief( this.element.find('.brief-editor') );
			},
			_vedioupload:function(event){
				var vedioFile=$(this.options['vedio_file_box']);
				var data={};
				$(this.options['button_mask']).show();
				this._ajaxgetVedio(data);
				vedioFile.show();
			},
			_searchvedio:function(event,startflag){
				var self=$(this.options['vedio_search_form']);
				var data={
				};
				data['k']=self.find('#search_list_key').val();
				data['_type']=self.find('input[name="sea_add_leixing_id"]').val();
				data['trans_status']=self.find('input[name="collect_trans_status"]').val();
				if(+startflag){
					data['start']=startflag;
				}
				this._ajaxgetVedio(data);
			},
			_triggersearchvedio:function(){
				this._searchvedio();
			},
			_delvedio:function(event){
				var self=$(event.currentTarget),
				    id=self.data('id');
				self.closest('.file-list-li').remove();
				$('input[name="new-video-id['+ id + ']"]').remove();
			},
			_initPage:function(options){
				var _this=this;
				var pagebox=$(this.options['page_link']);
				if(pagebox.data('instance')){
					pagebox.page('refresh',options);
					return;
				}
				options['page']=function(event,start){
					var _start=start;
					_this._searchvedio(null,_start);
				}
				pagebox.page(options);
				pagebox.data('instance',true);
			},
			_ajaxgetVedio:function(datainfo){
				var _this=this;
				var url="./run.php?mid="+gMid+"&a=get_videos";
				_this.loading.show();
				$.get(url,datainfo,function(data){
					_this.loading.hide();
					var data=data[0] ||{},
					    list=data['video_info'],
					    pageinfo={};
					pageinfo['prev_page']=data['prev_page'];
					pageinfo['current_page']=data['current_page'];
					pageinfo['last_page']=data['last_page'];
					pageinfo['next_page']=data['next_page'];
					pageinfo['total_page']=data['total_page'];
					$(_this.options['file_list']).html('');
					$(_this.options['file_list_tpl']).tmpl({list:list}).appendTo(_this.options['file_list']);
					_this._initPage(pageinfo);
				},'json');
			},
			_addVedio:function(event){
				var self=$(event.currentTarget);
				if(self.data('flag')){
					return;
				}
				var id=self.attr('_id');
				this._ajaxvideoitem(id);
				self.data('flag',true);
			},
			_ajaxvideoitem:function(id){
				var _this=this;
				var url="./run.php?mid="+gMid+"&a=select_video";
				_this.loading2.show();
				$.post(url,{
					material:id
				},function(data){
					_this.loading2.hide();
					var data=data[0] || {};
					if(data.success){
						var info=data['material']['img'];
						var material={};
						material['img']=info['host']+info['dir']+'80x60/'+info['filepath']+info['filename'];
						material['id']=data['id'];
						material['title']=data['material']['title'];
						material['duration']=data['duration'];
					$(_this.options['vedio_view_tpl']).tmpl(material).appendTo(_this.options['vedio_view_list']);
					}
				},'json');
			},
			_closevedioupload:function(){
				var vedioFile=$(this.options['vedio_file_box']);
				vedioFile.find(this.options['file_list']).html('');
				vedioFile.find(this.options['page_link']).html('');
				vedioFile.hide();
				$(this.options['button_mask']).hide();
			},
			_toggleClient:function(event){
				$(event.currentTarget).toggleClass('client_logo_click');
				$(this.options['clientlist']).slideToggle();
			},
			_initClient:function(){
				var ids=$('.client_logo_item').map(function(){
					return $(this).data('id');
				}).get();
				$.each(ids,function(key,value){
					$('#client'+value).hide();
				});
			},
			_delclientPic:function(event){
				var self=$(event.currentTarget),
				    parent=self.parent(),
				    id=parent.data('id');
				parent.remove();
				$('#client'+id).show();
				    
			},
			_setIndexPic:function(event){
				var self=$(event.currentTarget),
				    img=self.find('img'),
				    _indexFile=$(this.options['indexFile']),
				    flag=true;
				var flagobj=self.find('.indexpic-suoyin');
				_indexFile.trigger('click');
				_indexFile.data({imgk:img,flagk:flag,suoyink:flagobj})
			},
			_setbigPic:function(event){
				var self=$(event.currentTarget),
				    img=self.find('img'),
				    _indexFile=$(this.options['bigPicFile']);
				_indexFile.trigger('click');
				_indexFile.data({imgk:img});
			},
			_changefile:function(event,img,flag,flagobj){
				var _this=this,
				    self=event.currentTarget,
					file=self.files;
				var data=$(self).data(),
				    img=data.imgk,
				    flag=data.flagk,
				    flagobj=data.suoyink;
					_this._handleFiles(file,img,flag,flagobj);
			},
			_handleFiles:function(files,img,flag,flagobj){
				var _this=this,
				    imgData;
				for(var i=0;i<files.length;i++){
					var file=files[i];
					var imageType=/image.*/;
					if(!file.type.match(imageType)){
						alert("请上传图片文件");
						continue;
					}
					var reader=new FileReader();
					reader.onload=function(e){
						imgData=e.target.result;
						img.attr('src',imgData);
						img.hasClass('hide') && img.removeClass('hide');
						if(flag){
							flagobj.addClass('indexpic-suoyin-current');
						}else{
							img.parent().find(_this.options['bigPicflag']).hide();
							img.parent().find('.client_title').addClass('show');
						}
					}
					reader.readAsDataURL(file);
				}
				return imgData;
			},
			_attachupload:function(){
				var attachfile=$(this.options['attachmentFile']);
				attachfile.trigger('click');
			},
			_attachfile:function(event){
				var self=event.currentTarget,
				    file=self.files;
				this._handleattachFiles(file);
			},
			_handleattachFiles:function(files){
				var _this=this,
				    formdata=new FormData();
				for(var i=0;i<files.length;i++){
					var file=files[i];
					formdata.append('Filedata',file);
					$.ajax({
	                    url : "./run.php?mid="+gMid+"&a=upload&admin_id=" + gAdmin.admin_id + "&admin_pass=" + gAdmin.admin_pass,
	                    type : 'POST',
	                    data : formdata,
	                    processData : false,
	                    contentType : false,
	                    dataType : 'json',
						error: function() {
							alert(' 上传失败');
						},
						beforeSend:function(){
							_this.loading2.show();
						},
	                    success: function(data){
	                    	_this.loading2.hide();
	                    	var data=data[0] ||{};
	                    	var fileinfo={};
	                    	if(data.error){
	                    		alert('文件类型不支持');
	                    	}else{
		    				    fileinfo.size=data.filesize;
		    				    fileinfo.name=data.name;
		    				    fileinfo.type=data.type
		    				    fileinfo.attach_id=data.id;
		    				    $(_this.options['attach_tpl']).tmpl(fileinfo).appendTo(_this.attach_area);
		    				    $(_this.options['attachhiddenid_tpl']).tmpl(fileinfo).appendTo(_this.attachid_hidden);	                    		
	                    	}
				       }
					});
				}
			},
			_delAttach:function(){
				var attach_area=this.options['attach_area'],
				    attach_del=this.options['attach_del'],
				    attachid_hidden=this.options['attachid_hidden'];
				 $(attach_area).on('click',attach_del,function(event){
					 var self=$(event.currentTarget),
					     parent=self.parent(),
					     id=parent.attr('attach-id'),
					     input=$(attachid_hidden).find('input');
					 parent.remove();
					 for(var i=0;i<input.length;i++){
						 if(input.eq(i).val()==id){
							 input.eq(i).remove();
							 return;
						 }
					 }
				 });
			},
			_handlerBrief : function( targets ){
				var _this = this;
				targets.each( function(){
					var id = $(this).attr('id');
					_this._includeUEditor( id );
				} );
			},
			_initEditor : function( editorId ){
				return function(){
					$.m2oEditor.get( editorId, {
						initialFrameWidth : 648,
						initialFrameHeight : 300
					} );
				}
			},
			_includeUEditor : function( editorId ){
				$.includeUEditor( this._initEditor( editorId ), {
					plugins: null
				} );
			},
			_addBrief:function(event){
					var briefArea=this.options['briefArea'],
					    clone=$($(this.options['brief_tpl']).html()),
					    new_brief_editor = clone.find('.brief-detail');
					new_brief_editor.attr( 'id',Math.ceil( Math.random()*1000 ) );
					clone.find('input').val('').end().addClass('reduce').appendTo(briefArea);
					setTimeout(function(){
						clone.addClass('open');
					},100);
					this._handlerBrief( new_brief_editor );
					
			},
			_delBrief:function(event){
				var self=$(event.currentTarget),
				    obj=self.closest('.special-brief');
				obj.addClass('reset');
				setTimeout(function(){
					obj.remove();
				},1000);
			},
			_ajaxForm:function(){
				var _this=this,
				    form=$(_this.options['special_form']);
				form.submit(function(){
					var msg = '',
						value=$.trim($(_this.options['title']).val()),
						sort_id = form.find('input[name="sort_id"]').val();
					if( !value ){
						msg = '专题标题不能为空';
					}else{
						!(+sort_id) && ( msg = '请选择分类' );
					}
					if( msg ){
						form.myTip( {
							string : msg,
							color : 'red',
							dtop : 300
						} );
						_this.status=false;
						_this.callback=false;
						return false;
					}
					if(UE.instants){
						$.each( UE.instants, function( key, value ){
							var plaintxt = value.getPlainTxt();
							$(value.textarea).val(plaintxt);
						} );
					}
					$(this).ajaxSubmit({
						dataType : 'json',
						beforeSubmit:function(){
							
							_this.loading.show();
						},
						success:function(data){
							_this.loading.hide();
							var error = data;
							var data = data[0] || {},
							    obj=$(_this.options['result_tip']);
							if(data.success){
								var tip="专题属性保存成功";
								_this._ajaxTip(obj, tip);
								_this.status=true;
								_this._setHref( data.id );
								if($('input[name="a"]').val() == 'create'){
									$('input[name="a"]').val('update');
									$('input[name="id"]').val(data.id);
								}
							}
							if( error['callback'] ){
								eval( error['callback'] );
							}else{
								var tip=data.error;
								_this._ajaxTip(obj, tip);
								_this.status=false;
							}
						},
						error:function(data){
							_this.loading.hide();
							hg_show_error(data.responseText);
						}
					});
					return false;
				});
			},
			_ajaxTip:function(obj,tip){
				obj.html(tip).css({'opacity':1,'z-index':100000000});
				setTimeout(function(){
					obj.css({'opacity':0,'z-index':-1});
				},2000);
			},
			/*专题创建成功后设置内容、模板tab链接*/
			_setHref : function( speid ){
				var tab_menus = this.element.find('.con-tab');
				tab_menus.each( function(){
					var href = $(this).attr('href') + speid;
					$(this).removeClass('disabled').addClass('enabled');
					$(this).attr('href',href);
				} );
			},
			_referTip:function(event){
				var _this=this,
				    self=$(event.currentTarget),
				    url = self.attr('href'),
				    form=$(_this.options['special_form']);
				if( self.hasClass('disabled') ) return false;
				if( self.hasClass('enabled') ){
					window.location.href = url;
				}
			},
			_attach_tab:function(event){
				var self=$(event.currentTarget),
				    type=self.data('type');
				(!self.hasClass('active')) && self.addClass('active');
				self.siblings().removeClass('active');
				$('.'+type+'-con').show().siblings().hide();
			}
		});
		$.widget('special.clientPicupload', {
			options : {
				'avatar-url':"./run.php?mid="+gMid+"&a=upload&admin_id=" + gAdmin.admin_id + "&admin_pass=" + gAdmin.admin_pass,
			     upload:'.client-item',
			     uploadFile:'.client-file-data',
			     client_tpl:'#client-tpl',
			     client_log_all:'.client_log_all',
			     client_log:'.client_log_all .client_logo',
			     client_logo_item:'#client_logo_item'

	        },
	        _create : function(){
	        	this.uploadFile=this.element.find(this.options['uploadFile']);
	        },
	        _init:function(){
	        	var _this=this,
	        	    handlers={};
	        	handlers['click '+this.options['upload']] ='_upload';
				handlers['click '+this.options['client_log']] ='_upload';
				this.info={};
				this._on(handlers);
				this.uploadFile.ajaxUpload({
	                url : _this.options['avatar-url'],
	                phpkey : 'Filedata',
	                before : function(info){
	                    _this._uploadBefore(info['data']['result']);
	                },
	                after : function(json){
	                    _this._uploadAfter(json);
	                }
	            });
	        },
	        _upload : function(event){
	        	var self=$(event.currentTarget),
	        	    uploadFile=$(this.options['uploadFile']);
	        	this.info['id']=self.data('id');
	        	this.info['client_name']=self.data('name');
	        	this.edit=false;
	        	this.event=self;
	        	if(self.closest('.client_logo_item').length){
	        		this.edit=true;
	        	}
	        	uploadFile.click();
	        },

	        _uploadBefore : function(src){
	            this._avatar(src);
	        },

	        _uploadAfter : function(json){
	            var data = json.data,
	                client_logo_item=$(this.options['client_logo_item']+this.info.id);
	            this.info['picinfo']=data['pic'];
	            if(this.edit){
	            	client_logo_item.find('input').remove();
	        		this.edit=false;
	            }else{
		            this.event.hide();
	            }
	            $('<input type="hidden"  name="client_top_pic['+this.info['id']+']"/>').val(this.info.picinfo).appendTo(client_logo_item);
	            
	        },
	        _avatar : function(src){
	        	if(this.edit){
	        		this.event.closest('.client_logo_item').find('img').attr('src',src);
	        	}else{
		        	this.info['url']=src;
		            $(this.options['client_tpl']).tmpl(this.info).prependTo(this.options['client_log_all']);
	        	}
	        }
		});
	})(jQuery);
	$('#special-form').special_form();
	$('#special-form').clientPicupload();
});
function hg_search_k(){
	$('#vedio-search-form #key-search').trigger('click');
}