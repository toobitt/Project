$(function(){
	(function($){
		$.widget('special.special_tmpl',{
			options : {
				tplUrl : '',
				infoUrl : '',
				selUrl : ''
			},
			
			_create : function(){
			},
			
			_init : function(){
				var op = this.options;
				this._on({
					'click .tmpl-nav li' : '_toggle',
					'click .choose-tpl' : '_choose',
					'mouseover .tmpl-list li' : '_showbigpic',
					'mouseout .tmpl-list li' : '_hidebigpic',
					'click .tpl-search-btn' : '_search',
					'keyup input[name="search-tpl"]' : '_keyup'
				});
				this._initinfo();
			},
			
			/*标签切换*/
			_toggle : function(event){
				this.element.find('input[name="search-tpl"]').val('');
				this._gettplselect();  /**/
				var self = $(event.currentTarget),
					index = self.index(),
					no = this.element.find('.selected').index();
				this._toggleinfo(self);
			},
			
			_toggleinfo : function(self){
				var url = this.options.tplUrl,
					box = this.element.find('.tmpl-list'),
					data = {};
				box.empty();
				if(self){
					data.tag = self.attr('_id');
					self.addClass('selected').siblings().removeClass('selected');
					if(self.data('init')){
						if( this.globalData[data.tag]){
							this._gettplinfo(box ,this.globalData[data.tag]);
						}else{
							this.element.find('.tmpl-list').prepend('<p class="no-data">暂无数据</p>');
						}
					}else{
						this._getTpl(box , url ,data);
						self.data('init', true);
					}
				}else{
					this.hasnotag = true;
					this._getTpl(box , url ,data);
				}
			},
			
			/*选择动画*/
			_choose : function(event){
				var self = $(event.currentTarget),
					_this = this;
					obj = self.closest('li'),
					id = obj.data('id'),
					sign = obj.data('sign'),
					_id = this.element.data('id'),
					tid = this.element.find('.tmpl-selected').attr('_id'),
					title = this.element.find('.special-title').text(),
					tempname = obj.find('.f-t').text(),
					_src = obj.find('img').attr('src');
				obj.siblings().find('.tmpl-img').removeClass('choosen');
				if(id == tid){
					var tip = '您已选择此模板',
						dleft = -110,
						color = '#6ba4eb';
					this._tip(self , tip , dleft , color);
					return false;
				}
				var copy = obj.clone().appendTo('.tmpl-list').css({'position' : 'absolute' , 'top' : obj.offset().top , 'left' : obj.offset().left-17});
				jConfirm( '您确定要选择此模板吗？', '模板选择提醒' , function(result){
					if( result ){
						copy.addClass('copy-move');
						_this._delay(function(){
							copy.remove();
							var url = _this.options.selUrl,
				    		data = {
				    			template_sign : sign,
				    			id : id,
				    			special_id : _id,
				    			spe_name : title,
				    			tem_name : tempname
				    		};
					    	_this._selectTpl(url , data , _src , tempname);
					    	self.closest('li').find('.tmpl-img').addClass('choosen').end().siblings().find('.tmpl-img').removeClass('choosen');/*标志*/
					    	self.closest('li').find('.sel-sign').show().end().siblings().find('.sel-sign').hide();
						},1000);
					}else{}
				});
			},
			
			/*tip提示*/
			_tip : function(item ,tip , dleft ,color){
				item.myTip({
					string : tip,
					dleft : dleft,
					color : color
				});
			},
			
			/*搜索*/
			_search : function(event){
				var self = $(event.currentTarget),
					val = $.trim(self.closest('div').find('input').val());
				this._searchtpl(val);
			},
			
			_searchtpl : function(data){
				var item = this.element.find('.tmpl-list'),
					tag = this.element.find('.selected').attr('_id');
				if(data){
					var html = '';
					item.empty();
					this._gettplinfo(item ,this.globalData[tag]);
					var tname = item.find('.f-t').map(function(){
							return $(this).text();
						});
					$.each(tname,function(key , value){
						if(data.indexOf(tname[key])>=0 || tname[key].indexOf(data)>=0){
							id = item.find('li:eq('+ key +')').data('id');
							sign = item.find('li:eq('+ key +')').data('sign');
							img = item.find('li:eq('+ key +')').data('bigimg');
							html += '<li data-id="'+id+'" data-sign="'+ sign +'" data-bigimg="'+ img +'">' +item.find('li:eq('+ key +')').html()+ ' </li>';
						}
					});
					item.empty().html(html);
				}else{
					item.empty();
					this._gettplinfo(item ,this.globalData[tag]);
				}
			},
			
			/*键盘回车触发搜索*/
			_keyup : function(event){
				var key = event.which;
				key == 13 && this.element.find('.tpl-search-btn').trigger('click');
			},
			
			/*大图预览*/
			_showbigpic : function(event){
				var self = $(event.currentTarget),
					top = self.offset().top,
					left = self.offset().left,
					index = self.index()+1;
					src = self.find('img').attr('src'),
					dheight = $(document).height(),
					hei = $(document).scrollTop();
				var info={};
				info.src = src;
				this.element.find('.big-pic-box').remove();
				$('#show-big-pic-tpl').tmpl(info).appendTo('.tmpl-list');
				var item = this.element.find('.big-pic-box');
				if(index%7 >4 || index%7==0 ){
					if(dheight-top < 425){
						item.css({'top' : top-360  , 'left': left-355});	
						item.find('p').addClass('p-right-bottom');
					}else{
						item.css({'top' : top  , 'left': left-355});
						item.find('p').addClass('p-right');
					}
					
				}else{
					if(dheight-top < 425){
						item.css({'top' : top-360  , 'left': left+135});
						item.find('p').addClass('p-left-bottom');
					}else{
						item.css({'top' : top  , 'left': left+135});
						item.find('p').addClass('p-left');
					}
					
				}
			},
			
			_hidebigpic : function(){
				this.element.find('.big-pic-box').hide();
			},
			
			/*实例化模板*/
			_initinfo : function(){
				var turl = this.options.infoUrl,
					box1 = this.element.find('.tmpl-list'),
					data = {
						special_id : this.element.data('id')
					}
				this._getTpl(box1 , turl ,data);
			},
			
			/*获取模板 ，导航 ， 历史纪录 ， 标题 ，已选模板信息*/
			_getTpl : function(box , url , data){
				var _this = this;
				$.globalAjax(box, function(){
			        return $.getJSON(url,data,function(json){
			            	if( json[0]['error'] ){
			            		jAlert(json[0]['error'],'权限提醒');
			            	}else{
			            		if(data.tag || _this.hasnotag){								/*注:刚进页面没有标签信息，会执行else；当有标签信息并加在好后，第一个标签trigger（'click'）。这时有标签信息data.tag，会执行这一步。如果没有标签信息，就通过_this.hasnotag执行这一步，加载模板信息*/
			            			if( json[0].template ){
			            				_this._gettplinfo(box ,json[0].template);
					            		_this.globalData = _this.globalData || {};				/*将模板数据全局变量存储 切换时不用重复请求*/
					            		_this.globalData[data.tag] = json[0].template;
			            			}else{
			                         	_this.element.find('.tmpl-list').prepend('<p class="no-data">暂无数据</p>');
			            			}
			            		}else{								
			            			if(json[0].tag){
			            				_this._gettaginfo(json[0].tag);						/*导航*/
			            			}else{
			            				_this._toggleinfo(null);							/*如果导航数据存在, 执行这一步*/
			            			}
				            		_this._gettitle(json[0].special.name);					/*标题*/
				            		_this._getpic(json[0].spetemp);							/*已选模板*/
				            		_this._getrecord(json[0].logs);  						/*历史纪录*/
			            		}
			            	}
			            });
			    });
			},
			
			_gettplinfo : function(box ,json){
        			var data = json;
                    $.map(data, function(obj, index){
                        if(obj['pic'] && obj['pic'][0]){
                            obj['realPic'] = $.globalImgUrl(obj['pic'][0]);
                            obj['realBigPic'] = $.globalImgUrl(obj['pic'][0]);
                        }
                    });
                    box.empty();
                    $('#moban-tpl').tmpl(data).appendTo(box);
                    this._gettplselect();  /*模板信息加载完 实例化选中模板*/
			},
			
			/*标签*/
			_gettaginfo : function(json){
				var box = this.element.find('.tmpl-nav');
                $('#tag-tpl').tmpl(json).appendTo(box);
                this.element.find('.tmpl-nav li:first').trigger('click');
			},
			
			/*历史记录*/
			_getrecord : function(data){
				var box = this.element.find('.record-list');
				$('#record-tpl').tmpl(data).appendTo(box);
			},
			
			/*标题*/
			_gettitle : function(json){
				this.element.find('.special-title').text(json);
			},
			
			/*已选模板预览*/
			_getpic : function(json){
				if(json){
					var data = JSON.parse(json.pic),
						src =  $.globalImgUrl(data[0]),
						specialid = this.element.data('id'),
						ext = encodeURIComponent("page_data_id="+ specialid +"&template_id="+ json.id),
						_href = 'magic/main.php?gmid='+ gMid + '&ext='+ ext + '&bs=k';
					this._gethref(src ,json.id ,json.title, _href);
				}else{
					$('.tmpl-selected img').attr('src' , '');
				}
			},
			
			_gethref : function(src , id ,title , href){
				this.element.find('.tpl-img').attr('src' , src);
				this.element.find('.tmpl-selected').attr({'_id' : id , 'title' : title});
				this.element.find('.into-special').attr('href' , href );
				this.element.find('.default-tmpl').css('background' , 'rgba(249 , 6 ,6 ,0.6)');
				
			},
			
			/*初始化选中模板*/
			_gettplselect : function(){
				var cid = this.element.find('.tmpl-selected').attr('_id');
				var ids = this.element.find('.f-item').map(function(){
					return $(this).attr('_id');
				});
				var _this = this;
				$.each(ids , function(key ,value){
					if(ids[key] ==cid ){
						_this.element.find('.f-item:eq('+ key +')').find('.tmpl-img').addClass('choosen');/*标志*/
						_this.element.find('.f-item:eq('+ key +')').find('.sel-sign').show();
						return false;
					}
				})
			},

			/*选择模板*/
			_selectTpl : function(url , data ,src , title){
				var box = this.element.find('.show-tmpl'),
					_this = this;
				$.globalAjax(box, function(){
			        return $.getJSON(url, data ,function(json){
			            	if( json[0]['error'] ){
			            		jAlert(json[0]['error'],'权限提醒');
			            	}else{
			            		var ext = encodeURIComponent("page_data_id="+ data.special_id +"&template_id="+ data.id),
			            			_href = 'magic/main.php?gmid='+ gMid + '&ext='+ ext + '&bs=k';
			            		_this._gethref(src ,data.id ,title, _href);
			                }
					});
			    });
			},
			
			
		});
})($);
	$('.m2o-form').special_tmpl({
		tplUrl : 'run.php?mid=' + gMid + '&a=get_special_templates',
		infoUrl : 'run.php?mid=' + gMid + '&a=get_special_info',
		selUrl : 'run.php?mid=' + gMid +'&a=select_template',
	});
});
