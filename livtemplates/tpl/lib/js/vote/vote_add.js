$(function(){
	(function($){
		
		$.widget('vote.vote_form',{
			options : {
			},
			_init : function(){
				this._getFeedbackList();
				this._on({
					'click .play-button' : '_playvideo',
					'click .vedio-back-close' : '_closeBox',
					'blur .num_count' : '_blurplus',
					'click .form-feedback-item' : '_getFeedTitle'
				})
				//this._sortable();
				this._submit();
			},
//			_sortable : function(){
//				var obj = this.element.find('.content-list-add,.advanced-content-list-add'),
//					_this = this;
//				obj.sortable({
//			        cursor: "move",
//			        scrollSpeed: 100,
//			        delay : 200,
//			        axis : 'y',
//			        placeholder: "ui-state-highlight",
//			        stop : function(event , ui){
//			        	$(".content-list-add .content-list").each(function(){
//			        		var indexnum = parseInt($(this).find('.content-index').text())-1;
//			        		var ordernum = $(this).index()+1;
//			        		$(".content-list").find('input[name="order['+ indexnum +']"]').val(ordernum);
//		                })
//			        }
//				}).disableSelection();
//			},
			_getFeedbackList : function(event ,page ,page_num){
				var _this = this,
					box = MC.form.find('.feedback-list'),
					page = page ? page : 1 ,
					url =  'run.php?mid='+ gMid + '&a=get_feedback_list' + '&page=' + page;
					$.globalAjax( box, function(){
						return $.getJSON( url, null, function(data){
							_this._getInfo( data[0].info );
							_this._getInfopage( data[0].page_info );
						});
					});
			},
			
			_getFeedTitle : function(){
				var sform = this.element;
				feed_checked = sform.find('input[name="feedback_id"]:checked');
				if(feed_checked)
				{
					feed_title = feed_checked.parent().find('a').text();
					MC.form.find('.feed-title').text(feed_title);
				}
				
			},
			
			_getInfo : function(data){
				var obj = this.element.find('.feedback-list .feedback-list-item');
				if(data){
					var info = {};
					obj.empty();
					info.options = data;
					info.checkedid = $('#detail-tpl').attr('_val');
					$('#detail-tpl').tmpl(info).appendTo(obj);
				}else{
					obj.html('<li style="text-align: center;font-size: 18px;color:#5c99d0">没有找到你要的内容</li>');
				}
			},
			
			_getInfopage : function( option ){								/*分页*/
	        	var page_box = MC.form.find('.page_size'),
	                _this = this;
	            option.show_all = false;
	            if (page_box.data('init')) {
	                page_box.page('refresh', option);
	            } else {
	                option['page'] = function (event, page, page_num) {
	                    _this._refresh(event,page,page_num);
	                }
	                page_box.page(option);
	                this.page_num = option.page_num;
	                page_box.data('init', true);
	            }
		     },

		     _refresh: function(event,page ,page_num) {
	             this._getFeedbackList(event, page, page_num);
	         },
			
			_blurplus : function(event){
				var self = $(event.currentTarget),
					value = $.trim(self.val());
				var _this = this;
				if(value < 0 || isNaN(value)){
					tip = "只能为非负数";
					self.myTip({
						string : tip,
						delay: 1000,
						width : 150,
						dtop : 0,
						dleft : -20,
					});
					self.val(0);
					return false;
				}
			},

			_submit : function(){
					sform = this.element;
					var _this = this;
					var reg =  /^(0|[1-9]\d*)$/;
					sform.submit(function(){
						var txt = sform.find('.m2o-m-title ').val(),
							len = sform.find('.content-list-add .content-list').length,
							val = sform.find('input[name="option_type"]').val(),
							max = sform.find('input[name="max_option"]').val(), 
							min = sform.find('input[name="min_option"]').val(),  
							start = sform.find('input[name="start_time"]').val(), 
							end = sform.find('input[name="end_time"]').val(), 
							first = sform.find('.content-list-add .content-list:eq(0) .advanced-content-input').val(),
							second = sform.find('.content-list-add .content-list:eq(1) .advanced-content-input').val(),
							iptime = sform.find('input[name="ip_limit_time"]').val(),
							feed = sform.find('input[name="is_feedback"]').prop('checked'),
							flag = true;

						var optitle = new Array();
						sform.find('.content-list-add .content-list .content-title').each(function(){
							if($.trim($(this).val())!='')
							optitle.push($.trim($(this).val()));
						});
						//console.log(optitle.length);
						if(optitle.length < 2){
							//_this._myTip( '选项不能少于两项' );
							//return false;
						}
						if(feed){
							feedback_id = sform.find('input[name="feedback_id"]:checked').val();
							if(!feedback_id){
								_this._myTip( '请选择一个反馈表单' );
								return false;
							}
						}
						if(!txt){
							_this._myTip( '请输入投票名称' );
							return false;
						}
						if( len < 2){
							_this._myTip( '选项不能少于两项' );
							return false;
						}
//						if( !first || !second){
//							_this._myTip( '前两项不能为空' );
//							return false;
//						}
						if(end && start > end){
							_this._myTip( '开始时间不能大于结束时间' );
							return false;
						}
						if(max){
							if(!max.match(reg)){
								_this._myTip('必须为非负整数');
								sform.find('input[name="max_option"]').val('');
								return false;
							}
						}
						if(min){
							if(!min.match(reg)){
								_this._myTip('必须为非负整数');
								sform.find('input[name="min_option"]').val('');
								return false;
							}
						}
						if(parseInt(max) > len || parseInt(min) >len){
							_this._myTip( '最多项和最少项不能多于选项数' );
							return false;
						}
						if(parseInt(min) > parseInt(max)){
							_this._myTip( '最少项不能多于最多项' );
							return false;
						}
						if(val == 2){
							if( max && max < 2){
								_this._myTip( '最多项不能少于2项' );
								return false;
							}
						}
						if(iptime){
							if(isNaN(iptime) || iptime < 0){
								_this._myTip('必须为正数');
								sform.find('input[name="ip_limit_time"]').val(0);
								return false;
							}
						}
					});
			},	
			
			_myTip : function( tip ){
				this.element.find('.save-button').myTip({
					string : tip,
					width : 200,
					delay: 1000,
					dtop : 0,
					dleft : -120,
				});
			},
			
			_playvideo : function(event){
				var self = $(event.currentTarget),
					url = self.closest('.prevod').data('url'),
					offset = self.offset(),
					box = this.element.find('.video-box' );
				box.removeClass( 'video-show' );
				box.html('');
				var info = { video_url : url };
				$( '#vedio-tpl' ).tmpl(info).prependTo( box );
				box.addClass( 'video-show' ).attr({'_type':'m_video'}).css('top' , offset.top-100 +'px');
			},
			
			_closeBox : function(){
				this._closeVideo();
			},
			
			_closeVideo : function(){
				var op = this.options,
					box = $('.video-box');
				box.removeClass( 'video-show').css('top' ,-1000 + 'px');
				setTimeout(function(){
					box.html('');
				},500)
			}
		});	

		$.widget('vote.vote_add',{
			options : {
				delid : '',
				delUrl : '',
				url: '',
				tmp : '',
			},
			_create : function(){
				var _this = this;
				$.pop({
					title : '引用内容',
					className : 'pubLib-pop-box',
					widget : 'pubLib',
					clickCall : function(event , info ,widget){
						_this._clickCall( info, widget );
					}
				});
	            this.datasource = $('.pubLib-pop-box');
	            this.datasource.pubLib('hide');
			},
			_init : function(){
				var _this = this;
				var op = this.options;
				this._on( {
					'click .advance-mode' : '_advance',
					'click .content-del' : '_del',
					'click .advanced-content-add' :'_addinput',
					'click .content-add' : '_addcontent',
					'click .input-del' : '_inputdel',
					'click .pic' : '_fileinput',
					'click .del-pic' : '_delpic',
					'blur .content-title' : '_blur',
					'blur .init-votes' : '_voteblur',
					'click .content-img' : '_uppic',
					'click .cite' : '_cite',
					'click .uploadvod' : '_uploadvod',
				} );
				
			},
			
			uploadpic : function(file , obj){
				this.obj = obj || this.obj;
				var op = this.option;
				var _this = this;
		        file.ajaxUpload({
						url : 'run.php?mid='+ gMid +'&a=upload_image',
						phpkey : 'Filedata',
						type : 'video',
						before : function( info ){
						},
						after : function( json ){
							_this._getinfo(json , _this.obj);
						}
					});
		        
			},
			
			_getinfo : function(json , obj){
				var data = json.data;
				if(obj.data('type') == 1){ /*索引图*/
					obj.attr('src' , data.img_info);
					obj.closest('.content-list').find('.content-title-img').val(data.id);
				}else{
					var info = {};
					info.id = data.id;
					info.src= data.img_info;
					info.type =data.upload_type;
					$('#file-show-tpl').tmpl( info ).appendTo( obj );
					this.obj.find('.Preview').filter(function(){
						return $(this).find('.Pre-file').text() == '图片';
					}).addClass('prepic');
					var ids = obj.find('.prepic').map(function(){
						return $(this).attr('_id');
					}).get().join(',');
					obj.find('.pic-hidden').val(ids);
					obj.find('.prepic .Pre-brief').hide();
				}
			},
			
			uploadVod : function(file,obj){
				this.obj = obj || this.obj;
				var op = this.option;
				var _this = this;
		        file.ajaxUpload({
						url : 'run.php?mid='+ gMid +'&a=upload_video',
						phpkey : 'videofile',
						type : 'video',
						before : function( info ){
						},
						after : function( json ){
							var data = json.data,
							info = {};
						info.id = data.id;
						info.src= data.img_info;
						info.type =data.upload_type;
						info.url = data.url;
						$('#file-show-tpl').tmpl( info ).appendTo( _this.obj );
						_this.obj.find('.Preview').filter(function(){
							return $(this).find('.Pre-file').text() == '视频' || $(this).find('.Pre-file').text() =='音频';
						}).addClass('prevod');
						_this.obj.find('.Preview').filter(function(){
							return $(this).find('.Pre-file').text() == '视频' || $(this).find('.Pre-file').text() =='音频';
						}).find('.play').addClass('play-button');
						var ids = _this.obj.find('.prevod').map(function(){
							return $(this).attr('_id');
						}).get().join(',');
						_this.obj.find('.video-hidden').val(ids);
						_this.obj.find('.Pre-brief').hide();
						}
					});
			},
			
			_advance : function(){
				this.element.find('.content').toggle();
				this.element.find('.advanced-content').toggle();
				var dis = $('.content-list-add')[0].style.display;
				if(dis == 'none'){
					$('.advance-mode').text('取消高级模式');
				}else{
					$('.advance-mode').text('高级模式');
				}
			},
			
			_del : function(event){
				var _this = this;
					var self = $(event.currentTarget),
						obj = self.closest('.content-list'),
						index = obj.find('.content-index').text(),
						val = obj.find('.content-title').val();
					if(!val){
						_this.element.find('.content-list').filter(function(){
							return $(this).find('.content-index').text() == index ;
						}).remove();
						_this.element.find('.content-list-add .content-list').each(function(index){
							$(this).find('.content-index').text(index+1);
						});
						_this.element.find('.advanced-content-list-add .content-list').each(function(index){
							$(this).find('.content-index').text(index+1);
						})
						
					}else{
						var method = function(){
							_this.element.find('.content-list').filter(function(){
								return $(this).find('.content-index').text() == index ;
							}).remove();
							_this.element.find('.content-list-add .content-list').each(function(index){
								$(this).find('.content-index').text(index+1);
							});
							_this.element.find('.advanced-content-list-add .content-list').each(function(index){
								$(this).find('.content-index').text(index+1);
							})
						}
						this._remind( '您确认删除此选项吗？', '删除提醒' , method );	
					}
			},
			
			_remind : function( title , message , method ){
				jConfirm( title, message , function(result){
					if( result ){
						method();
					}else{}
				});
			},
			
			_addinput : function(event){
				var self = $(event.currentTarget);
				var obj = self.closest('div');
				var name= self.closest('div').find('.add-input').attr('name'); 
				var tpl = $('<div class="add-input-brief"><input class="advanced-content-input" placeholder="增加描述" type="text" style="margin-top: 9px;" name="'+name+'""><span class="del input-del" style="margin-left:20px;" title="删除此条描述">一</span></div>');
				tpl.insertAfter(obj);
			},
			
			_uppic : function(event){
				var self = $(event.currentTarget),
					file = this.element.find('.Materialfile'),
					obj = self.find('img'),
					index = 1;
				this.element.find('.Materialfile').trigger('click');
				MC.content.vote_add('uploadpic' , file , obj , index);
			},
			
			_addcontent :function(){
				var obj = $('#advanced-content-input-tpl');
				var	objc = $('#content-input-tpl');
				var	num = parseInt($('.content-index:last').text());
				var	info={};
				if(!num){
					info.num = 1;
					info.reduce = 0;
				}else{
					info.num = num + 1;
					var snum = this.element.find('.content-title:last').attr('name');
					var addnum = snum.replace(/[^0-9]/ig,'');
					info.reduce = parseInt(addnum) + 1 ;
				};
				
				obj.tmpl(info).appendTo('.advanced-content-list-add');
				objc.tmpl(info).appendTo('.content-list-add');
			},
			
			_inputdel :function(event){
				var self = $(event.currentTarget);
				var obj = self.closest('div');
				obj.remove();
			},
			
			_fileinput : function(event){
				var self = $(event.currentTarget);
				var file = this.element.find( '.Materialfile' );
				this.obj = self.closest('.content-list').find('.file-show');
				this.element.find('.Materialfile').trigger('click');
				this.uploadpic(file);
			},
			
			_uploadvod : function(event){
				var self = $(event.currentTarget);
				var file = this.element.find( '.videofile' );
				this.obj = self.closest('.content-list').find('.file-show');
				this.element.find('.videofile').trigger('click');
				this.uploadVod(file);
			},
			
			
			_delpic : function(event){
				var _this =this;
				var self = $(event.currentTarget);
				var obj = self.closest('.Preview');
				if(self.closest('.Preview').hasClass('pre')){
					self.closest('.file-show').find('.cite-hidden').val('');
				}else if(self.closest('.Preview').hasClass('prevod')){
					var ids = self.closest('.file-show').find('.prevod').not(self.closest('.prevod')).map(function(){
						return $(this).attr('_id');
					}).get().join(',');
					self.closest('.file-show').find('.video-hidden').val(ids);
				}else{
					var ids = self.closest('.file-show').find('.prepic').not(self.closest('.prepic')).map(function(){
						return $(this).attr('_id');
					}).get().join(',');
					self.closest('.file-show').find('.pic-hidden').val(ids);
				}
				obj.remove();
			},
			
			_blur : function(event){
				var dom = this.element.find('.content-title');
				this._vote(event,dom);
			},
			
			_voteblur :function(event){
				var dom = this.element.find('.init-votes');
				this._vote(event,dom);
			},
			
			_vote : function(event,dom){
				var self = $(event.currentTarget),
					obj = self.closest('.content-list'),
					index = obj.find('.content-index').text(),
					val = self.val();
				this.element.find('.content-list').filter(function(){
					return $(this).find('.content-index').text() == index ;
				}).find(dom).val(val);
			},
			
			_cite : function(event){
				var self = $(event.currentTarget);
				var	_this= this;
				this.pos  = self.closest('.content-list');
				this.num = self.closest('.content-list').find('.content-index').text();
				this.showPop( 1 );
			},
			_clickCall : function( info ,widget ){
				if( !info ) return;
				var	pos = this.pos.find('.file-show'),
					data = info[0],
					arr = {},
					options = {};
				options.width = 100;
				options.height = 75;
				arr.src = $.createImgSrc( data.indexpic ,options);
				arr.name = data.module_name;
				arr.type = '引用';
				arr.id = data.id;
				arr.title = data.title;
				arr.brief = data.brief;
				arr.indexpic = JSON.stringify(data.indexpic);
				arr.num = '';
				if(+this.type){
					arr.num = this.num - 1;
					var id = pos.find('.pre').attr('_id');
					if(data.id == id){
						alert("此条数据已存在");
						widget.element.pubLib('hide');
						return false;
					}else{
						pos.find('.pre').remove();
					}
				}
				$('#file-show-tpl').tmpl( arr ).appendTo(pos);
				pos.find('.Preview').filter(function(){
					return $(this).find('.Pre-file').text() == '引用';
				}).addClass('pre');
				var val = this.pos.find('.content-title').val();
				if(!val){
					this.pos.find('.content-title').val(arr.title);
					this.pos.find('.vote-brief .advanced-content-input').val(arr.brief);
					this.pos.find('.content-img img').attr('src' ,arr.src);
					this.pos.find('.pic-info-hidden').val(arr.indexpic);
				}
				var ids = pos.find('.pre').map(function(){
					return $(this).attr('_id');
				}).get().join(',');
				pos.find('.cite-hidden').val(ids);
				widget.element.pubLib('hide');
			},
			showPop : function( type, pos){
				this.pos = pos || this.pos;
				this.type = type;
				var top = this.pos.offset().top;
				if( top > 300 ){
					top = top-300;
				}
				this.datasource.pubLib('show', {
					top : top + 'px',
					'margin-top' : 0
				});
			}
				
		});		
		
		$.widget('vote.file_add',{
			options : {
				week : '',
				url : ''
			},
			
			_create : function(){
				
			},
			
			_init : function(){
				var op = this.option;
				var _this = this;
				this._on( {
					'click .indexpic' : '_setIndexPic',
					'change #photo-file' :'_changefile',
					'click .del-pic' : '_delpic',
					'click .cite' : '_cite',
					'click .pic' : '_pic',
					'click .uploadvod' : '_uploadvod',
				} );
			},
			
			_setIndexPic:function(event){
				var self=$(event.currentTarget),
				    img=self.find('img'),
				    _indexFile=this.element.find('#photo-file'),
				    flag=true;
				var flagobj=self.find('.indexpic-suoyin');
				_indexFile.trigger('click');
				_indexFile.data({imgk:img,flagk:flag,suoyink:flagobj})
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
						}
					}
					reader.readAsDataURL(file);
				}
				return imgData;
			},
			
			_pic : function(event){
				var self = $(event.currentTarget);
				var file = this.element.find('.Materialfile');
				var obj = self.closest('.m2o-l').find('.file-show');
				this.element.find('.Materialfile').trigger('click');
				MC.content.vote_add('uploadpic' , file ,obj);
				
			},
			
			_uploadvod : function(event){
				var self = $(event.currentTarget);
				var file = this.element.find('.videofile');
				var obj = self.closest('.m2o-l').find('.file-show');
				this.element.find('.videofile').trigger('click');
				MC.content.vote_add('uploadVod' , file ,obj);
			},
			
			_cite : function(event){
				var self = $(event.currentTarget);
				var pos = self.closest('.m2o-l');
				MC.content.vote_add('showPop' , 0 , pos);
			},
			
			_delpic : function(event){
				var self = $(event.currentTarget),
					obj = self.closest('.Preview');
				obj.remove();
				if(self.closest('.Preview').hasClass('pre')){
					var ids =this.element.find('.pre').map(function(){
						return $(this).attr('_id');
					}).get().join(',');
					this.element.find('.cite-hidden').val(ids);
				}else if(self.closest('.Preview').hasClass('prevod')){
					var ids =this.element.find('.prevod').map(function(){
						return $(this).attr('_id');
					}).get().join(',');
					this.element.find('.video-hidden').val(ids);
				}else{
					var ids =this.element.find('.prepic').map(function(){
						return $(this).attr('_id');
					}).get().join(',');
					this.element.find('.pic-hidden').val(ids);
				}
			},
			
		});
		
		$.widget('vote.info_add',{
			options : {
			},
			
			_create : function(){
				
			},
			
			_init :function(){
				this._on({
					'click .limit-box' : '_limit',
					'click .single-option' : '_option',
					'click .option-ul li' : '_li',
					'click .verify' : '_verify',
					
				});
			},
			
			_limit : function(event){
				var self = $(event.currentTarget);
				var obj = self.parent().find('.limit-hour');
				if(self.prop('checked')){
					self.val(1);
					obj.show();
				}else{ 
					self.val(0);
					obj.hide();
				}
				
			},

			_option : function(){
				this.element.find('.option-ul').toggle();
			},
			
			_li : function(event){
				var self = $(event.currentTarget),
					txt = self.text(),
					obj = this.element.find('.more-options');
					val = this.element.find('input[name="option_type"]');
				self.closest('ul').siblings('a').text(txt);
				if(txt == '多选'){
					val.val('2');
					obj.show();
				}else{
					val.val('1');
					obj.hide();
				}
			},
			
			_verify : function(event){
				var self = $(event.currentTarget);
				var obj = self.parent().parent().find('.verify_type');
				if(self.prop('checked')){
					obj.show();
				}else{
					obj.hide();
				}
			},
		});
	})($);
	
	var MC ={
			form : $('.m2o-form'),
			content : $('.contents-list'),
			voteleft : $('.m2o-l'),
			voteright : $('.m2o-r'),
	};
	MC.content.vote_add({
		url : 'run.php?mid='+ gMid +'&a=upload_image',
		tmp : '#file-show-tpl',
	});
	MC.voteleft.file_add({});
	MC.voteright.info_add({});
	MC.form.vote_form({});
})