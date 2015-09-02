$(function(){
	(function($){
		$.widget('tv.tv_form',{
			options : {
				'm2o-form' : '.m2o-form',
				'indexpic' : '.indexpic',
				'photo-file' : '#photo-file',
				'active' : 'active',
				'tv-title' : '.tv-title',
				'teleplay' : '.teleplay',
				'transcode' : '.transcode',
				'current' : 'current',
				'teleitem' : '.teleplay p',
				'del' : '.del',
				'add-play' : 'add-play',
				'avater-img' : '.avater-img',
				'video-file' : '#video-file',
				'tele-total' : '.tele-total',
				'prevent-do' : '.prevent-do',
				'media_box' : '.media_box',
				'vedio-back-close' : '.vedio-back-close',
				'permanent' : '#permanent',
				'check-time' : 3 * 1000
			},
			_create : function(){
				this.status = {};
			},
			_init : function(){
				var _this = this,
					op = this.options,
					handlers = {};
				this._trigger('init',null,[this]);
				this.uploadVod = this.element.find( op['video-file'] );
				handlers['click ' + op['indexpic'] ] = '_showPic';
				handlers['change ' + op['photo-file'] ] = '_addPic';
				handlers['click ' + op['tv-title'] ] = '_showContent';
				handlers['click ' + op['del'] ] = '_delPlay';
				handlers['click ' + op['teleplay'] ] = '_checkPlay';
				/*handlers['click ' + op['avater-img'] ] = '_uploadvod';*/
				handlers['click ' + op['prevent-do'] ] = '_preventInit';
				handlers['click' + op['teleitem'] ] = '_playVideo';
				handlers['click' + op['vedio-back-close'] ] = '_closeBox';
				handlers['click ' + op['permanent']] = '_check';
				handlers['click ' + op['transcode']] = '_sign';
				this._on(handlers);
				this._initForm();
				this.startStatus();
				this._switch();
			},
		
			_switch : function(){
				$('.common-switch').each(function(){
					var $this = $(this);
					$this.hasClass('common-switch-on') ? val = 100 : val = 0;
					$this.hg_switch({
						'value' : val,
						'callback' : function( event, value ){
							var is_on = 0;
							( value > 50 ) ? is_on = 1 : is_on = 0;
							$this.closest('.m2o-item').find('input[type="hidden"]').val( is_on );
						}
					});
				});
			},
		
			startStatus : function(){
				var _this = this;
				_this._checkStatus();
	            _this.intervalTimer = setInterval(function(){
	                _this._checkStatus();
	            }, _this.options['check-time']);
			},
		
			stopCheck : function(){
	            var _this = this;
	            if(_this.intervalTimer){
	                clearInterval(_this.intervalTimer);
	                _this.intervalTimer = null;
	            }
	        },
		
			_checkStatus : function(){
				var _this = this;
				if( !$.isEmptyObject(this.status) ){
					var A_ids = [];
		            $.each(_this.status, function(i, n){
		                if(n == 0){
		                    A_ids.push(i);
		                }
		            });
		            ids = A_ids.join(',');
				}else{
					ids = this.element.find('.teleplay').map( function(){
						return $(this).attr( '_video_id' );
					} ).get().join(',');
				}
				if( ids ){
					var url = './run.php?mid=' + gMid + '&a=get_video_status';
					 $.getJSON( url , { video_id : ids }, function( data ){
						if( $.isArray( data ) && data[0] ){
							_this._checkAfter( data[0], ids.split(',').concat() );
						}else{
							_this.videoError( ids.split(',') );
							_this.stopCheck();
						}
					} );
				}else{
					this.stopCheck();
				}
			},
		
			_checkAfter : function( info, ids ){
				var _this = this;
	            if(info['status_data']){
	                $.each(info['status_data'], function(i, n){
	                	var index = $.inArray( n['id'], ids );
	                	ids.splice(index, 1);
	                	if( i == info['status_data'].length - 1 ){
	                		_this.videoError( ids );
	                	}
	                	
	                    _this.status[n['id']] = parseInt(n['status']);
	                    var item = _this.element.find('.teleplay[_video_id="' + n['id'] + '"]');
	                    if( item[0] ){
	                        _this._zhuan(item.find('.transcode'), n['status'], n['transcode_percent']);
	                    }
	                });
	            }
			},
		
			videoError : function( ids ){
				var _this = this;
				if( $.isArray( ids ) && ids[0] ){
					$.each(ids, function(kk, vv){
	            		var current = _this.element.find('.teleplay[_video_id="' + vv + '"]');
	            		current.find('.transcode').html('视频异常');
	            		current.find('.transcode').css({color : '#f8a6a6'});
	            	});
				}
			},
		
			_zhuan : function(zhuan, code, percent){
				var _this = this,
					str_html = zhuan.html();
	            var tips = {
	                '-1' : '转码失败',
	                '0' : '转码中 ' + percent + '%',
	                '1' : '转码完成',
	                '-2' : '等待转码'
	            };
	            if( !percent || percent == -1){
	                code = -2;
	            }
	            if( code != 1 && tips[code] ){
	            	 zhuan.html( tips[code] );
	            }
	            if( str_html == '未发布' ){
	            	_this._hover( zhuan );
	            }else if( code == 1 && str_html != '已发布'){
	                this._delay(function(){
	                    zhuan.html( '未发布' );
	                    _this._hover( zhuan );
	                }, 1500);
	            }
			},
			
			_initForm : function(){
				var widget = this.element,
					_this = this,
					op = this.options;
				var dom = widget.find('.save-button')
				widget.submit(function(){
					$(this).ajaxSubmit({
					beforeSubmit:function(){
						$('#top-loading').show();
						var title = $('.m2o-m-title').val();
						var indexpic = $('.indexpic').find('img').attr('src');
						if(!title){
							_this._myTip(dom, '电视剧名称还未填写');
							return false;
						}
						if(!indexpic){
							_this._myTip(dom, '电视剧海报还未添加');
							return false;
						}
					},
					dataType : 'json',
					success:function( data ){
						if( $.isArray(data) ){
							var data = data[0],
						    infoid = data['id'];
							_this._myTip(dom, '电视剧保存成功');
							if( !widget.data('id') ){
								widget.data('id',infoid);
								window.location.href = './run.php?mid=' + gMid + '&a=form&id=' + infoid + '&infrm=1';
							}
						}else{
							_this._myTip(dom, '电视剧保存失败');
						}
						
					},
					error:function(){
						_this._myTip(dom, '电视剧保存失败');
					}
					});
					return false;
				});
			},
			
			_myTip : function( dom, str, left ){
				dom.myTip({
					string : str,
					delay: 2000,
					dtop : 5,
					dleft : left || 130,
					width : 'auto',
					padding: 10
				});
			},
			
			_preventInit: function( event ){
				var self = $(event.currentTarget);
				_this._myTip(self, '文件上传中，请稍后', -130);
			},
			_showPic:function(){
				$('#photo-file').trigger('click');
			},
			_addPic:function( event ){
				var _this = this,
				    self = event.currentTarget;
				 var   file=self.files;
					_this.handleattachFiles(file);
			},
			handleattachFiles: function(files){
				var widget = this.element,
					op = this.options;
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
						var box=$( op['indexpic'] );
						var img = box.find('img');
			            !img[0] && (img = $('<img/>').appendTo(box));
			            img.attr('src', imgData);
					}
					reader.readAsDataURL(file);
				}
			   },
			_ajaxTip:function(obj,tip){
				obj.html(tip).css({'opacity':1,'z-index':100001});
				setTimeout(function(){
					obj.css({'opacity':0,'z-index':-1});
				},2000);
			},
			_showContent : function( event ){
				var op = this.options,
				    widget = this.element,
					self = $(event.currentTarget);
					f_id= widget.data("id");
					t_id = self.attr("_id");
					if(!f_id==''){					  
				     if( self.hasClass( op['active'] ) ){		
					     return					
				     }else{
					   $( op['tv-title'] ).toggleClass( op['active'] );
					   $('.basic-info').add('.tv-maintain').toggle();
				     }		
				   }else{
				   	 if(t_id!='t_1'){
				   	 	alert("请先填完基本信息再维护");
				   	 }
				   }
			},
			_checkPlay : function( event ){
				var op = this.options,
					self = $(event.currentTarget),
					id = self.attr('_id'),
					url = './run.php?mid=' + gMid + '&a=form&id=' + id; 
				if( self.hasClass( op['current'] ) ){
					self.removeClass( op['current'] );
				}else{
					if(self.hasClass( op['add-play'] )){
					   return
					}else{
						 self.addClass( op['current'] );
					}
				}
			},
			_delPlay : function( event ){
				var op = this.options,
					widget = this.element;
				var self = $(event.currentTarget),
					item = self.closest( op['teleplay'] ),
					id = item.attr('_id');
				this._del( id, item );
				event.stopPropagation();
			},
			_remind : function( title , message , method ){
				jConfirm( title, message , function(result){
					if( result ){
						method();
					}else{
						
					}
				});
			},
			_del : function( id , item ){
				var method = function(){
					var url = './run.php?mid=' + gMid + '&a=deleteEpisode';
					$.get( url, {id : id } ,function(){
						item.remove();
					});
				};
				this._remind( '是否要删除此内容?', '删除提醒' , method );

			},
			_playVideo : function( event ){
				var op =this.options,
					self = $(event.currentTarget),
					id = self.closest( op['teleplay'] ).attr('_id');
				    url = "run.php?mid="+ gMid + "&a=play_episode&admin_id=" + gAdmin.admin_id + "&admin_pass=" + gAdmin.admin_pass + "&tv_play_id=" + id;
				var box = $( op['media_box'] ),
					hash =  + new Date() + '' + Math.ceil( Math.random() * 1000 );
				box.removeClass('media-box-show');
				box.html('');
				self.data( 'ajaxhash', hash );
			    $.getJSON( url, {id : id} ,function( data ){
			    	if(hash != self.data('ajaxhash')) return;
			        var data=data[0];
				    $.each(data,function(key,value){
				    	 var obj=data[key];
				    	  info={};
				    	 info.video_url = obj.video_url;
				    	 info.video_url_m3u8 = obj.video_url_m3u8;
				    	 $('#vedio-tpl').tmpl(info).appendTo( op['media_box'] );
				         box.addClass('media-box-show').attr({'_type':'m_video'});
				        });
					});
		    },
		    _closeBox : function(){
			this._closeVideo();
			},
			_closeVideo : function(){
				var op = this.options,
					box = $( op['media_box'] );
				box.removeClass('media-box-show');
				setTimeout(function(){
					box.html('');
				},500)
			},
			
			_check : function( event ){
				var self = $(event.currentTarget),
					item = self.closest('.m2o-item');
				if(self.prop('checked')){
					item.find('.date-picker').val('0');
				}
			},
			
			_sign : function( event ){
				event.stopPropagation();
				var _this = this;
					self = $(event.currentTarget);
				if( self.html() == '签发' ){
					var li = self.closest('.teleplay'),
						video_id = li.attr('_video_id'),
						tv_id = $('.m2o-form').data('id'); 
					this.hover = true;
					var url = './run.php?mid=' + gMid + '&a=tv_episode_publish'; 
					$.getJSON( url , { video_id : video_id, tv_play_id : tv_id }, function( data ){
						_this.hover = false;
						if( data && data[0] ){
							var data = data[0];
							var str = data.error ? '未发布' : '已发布';
							self.html( str );
							if( !data.error ){
								self.css({'color' : '#17b202'})
							}
							_this._myTip(self, data.msg );
						}
					});
				}
			},
			
			_hover : function( $this ){
				var _this = this;
				$this.hover(function(){
					if( $this.html() == '未发布' ){
						$this.html('签发');
					}
				}, function(){
					if( _this.hover ){
						return;
					}
					if( $this.html() == '签发' ){
						$this.html('未发布');
					}
				});
			},
			
			/*_uploadvod : function(event){
			   var op = this.options;
			   $( op['video-file'] ).click();
	    	},*/
	    	uploadVodAfter : function( data ){
			   var op = this.options,
				   info = {};
			   info.vod_src = data.img_index;
			   info.num = data.index_num;
			   info.id = data.id;
			   info.video_id = data.video_id;
			   info.title=data.title;
			   this.status[ info.video_id ] = 0;
			   var addVod = $('#add-vod-tpl').tmpl( info ).appendTo( op['tele-total'] );
			   $(".updata-num").text(info.num);
			   this.startStatus();
		    },
		});
	})($);
	//$('.m2o-form').tv_form();
});

