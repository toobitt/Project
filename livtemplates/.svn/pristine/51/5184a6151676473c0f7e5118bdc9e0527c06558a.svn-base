$(function(){
	(function($){
		$.widget('epaper.editlink',{
			options : {
				'stack-item' : '.stack-item',
				'link-prev-item' : '.link-prev-item',
				'uploadBtn' : '.uploadBtn',
				'uploadFlieBtn' : '.uploadFlieBtn',
				'link-news-link' : '.link-news-link',
				'save-link-button' : '.save-link-button',
				'tips' : '.tips',
				'option' : '.option',
				'choosen' : '.choosen',
				'link-choose-area' : '.link-choose-area',
				'each-pages' : '.link-edit-prev',
				'addStack' : '.stack-add',
				'sort' : '.sort',
				'open-hot' : '.open-hot',
				
				'link-prev-tpl' : '#link-prev-tpl',
				'link-news-tpl' : '#link-news-tpl',
				'link-choose-area-tpl' : '#link-choose-area-tpl'
			},
			_create : function(){
				this.linkColor = ['#e84c3d','#3598db','#f39c11','#f969a7',
					              '#1bbc9b','#fdd01b','#23c4f2','#7e6972',
					              '#9b58b5','#a5c73c'];
			},
			_init : function(){
				var op = this.options,
					_this = this,
					handlers = {};
				handlers['click' + op['stack-item']] = '_changeStack';
				handlers['click' + op['link-prev-item']] = '_changeEpaper';
				handlers['click' + op['uploadBtn']] = '_uploadFile';
				handlers['click' + op['save-link-button']] = '_saveHotarea';
				handlers['click' + op['option']] = '_selectLinkTo';
				handlers['click' + op['choosen']] = '_dropSelect';
				handlers['click' + op['addStack'] ] = '_addStack';
				handlers['click' + op['sort']] = '_sort';
				handlers['click' + op['open-hot']] = '_openHot';
				
				this._on(handlers);
				this._default();
			},
			_default : function(){
				var op = this.options,
					_this = this;
				this.hot_info = new Array();
				this.periodId = $('#info').attr('_periodid');
				this.epaperId = $('#info').attr('_epaperid');
				$(op['stack-item']).eq(0).click();
				
				this.uploadPdf = this.element.find(op['uploadFlieBtn']);
				this.uploadPdf.ajaxUploadWithUrl( {
					url : "./run.php?mid=" + gMid + "&a=upload&epaper_id=" + this.epaperId +"&period_id=" + this.periodId,
					after : function(json){
						_this._uploadPdfAfter(json);
					}
				} );
			},
			_uploadPdfAfter :function( json ){
				var op = this.options,
					currentStack = $('.stack-item').filter('.active'),
					currentPages = $('.link-edit-prev').filter('.active'),
					page_flag =  currentPages.find('li').length + 1 ,
					flag = currentStack.attr('_flag'),
					data = json['data'];
				$.each(data,function(key,value){
					$.each(this,function(i,_value){
						var img_info = _value['img_info'],
							info = {};
						info.src = $.createImgSrc(img_info,{'width':'93','height':'140'});
						info.pic = img_info['host']+img_info['dir']+img_info['filepath']+img_info['filename'];
						info.id = _value['page_id'];
						info['img_id'] = _value['img_id'];
						info.stack = flag;
						info.flag = page_flag ;
						$(op['link-prev-tpl']).tmpl(info).appendTo(currentPages);
					});
				});
				$('.uploadBtn').find('.pageNum').text(flag + (page_flag+1) + '');
			},
			_addStack : function( event ){
				var	self = $(event.currentTarget),
					prev = self.prev(),
					id = parseInt(prev.attr('_id'))+1,
					flag = String.fromCharCode(prev.attr('_flag').charCodeAt(0)+1),
					html = "<li class='stack-item' _needajax='true' _id='" + id + "' _flag='" + flag +"'>" + flag + "叠</li>";
				$(html).insertBefore(self);
				var newPageList = $('<ul />').insertBefore('.page-add').attr({
					'class' : 'page',
					'_belong' : flag
				});
			},
			_changeStack : function( event ){
				var self = $(event.currentTarget),
					_this = this,
					op = this.options;
				if( !(self.hasClass('active')) ){
					$('.link-prev-item').removeClass('active');
					self.addClass('active').siblings().removeClass('active');
					$('.belong-stack').text(self.text());
					var	i = self.index(),
						stack_id = self.attr('_id'),
						period_id = _this.periodId,
						flag = self.attr('_flag'),
						url = './run.php?mid=' + gMid + '&a=get_page&type=edit_link';
					if( self.attr('_needajax') ){
						$.globalAjax(self,function(){
							return $.getJSON(url,{'stack_id':stack_id, 'period_id':period_id},function(data){
								var newPageList = $('<ul />').attr({
									'class' : 'each-list link-edit-prev each-list',
									'_id' : stack_id,
									'_belong' : flag
								}).insertBefore(op['uploadBtn']).addClass('active').siblings('ul').removeClass('active');
								self.attr('_needajax','');
								var arr = [],	//小模板信息
									hotArr = [],	//此叠所有版热区
									currentPageList = $(op['each-pages']).filter('.active');
								$.each(data,function(key,value){
									var	hot_info = value['hot_area'];	//此版热区
									var	info = {
											'id' : value['id'],
											'src' : $.createImgSrc(value,{width:93,height:140}),
											'pic' : value['host']+value['dir']+value['filepath']+value['filename'],
											'flag' : key + 1,
											'stack' : flag,
											'page_num' : value['page_num'],
									};
									arr.push(info);
									hotArr.push(hot_info);
								});
								_this.hot_info[i] = hotArr;
								$(op['link-prev-tpl']).tmpl(arr).prependTo(currentPageList);
								$('.link-prev-item').eq(0).click();
								var addFlag = currentPageList.attr('_belong') + (currentPageList.find('li').length+1);
								$(op['uploadBtn']).find('.pageNum').text(addFlag);
							});
						});
					}else{
						var current = $(op['each-pages']).eq(i),
							addFlag = current.attr('_belong') + (current.find('li').length+1);
						current.addClass('active').siblings('ul').removeClass('active');
						$(op['uploadBtn']).find('.pageNum').text(addFlag);
					}
				}
			},
			_openHot : function(){
				$('.open-hot').text('热区编辑已开启');
				$('.mask').hide();
				$('.intro').slideDown();
			},
			_closeHot : function(){
				$('.open-hot').text('开启热区编辑');
				$('.mask').show();
				$('.intro').slideUp();
			},
			_changeEpaper : function( event ){
				if( $('.sort').text() == '保存排序' ){
					$(event.currentTarget).myTip({
						string : '排序还未保存!'
					});
					return;
				}
				this._closeHot();
				var self = $(event.currentTarget),
					op = this.options,
					_this = this,
					kk = $(op['stack-item']).filter('.active').index(),
					nn = self.index();
				if( !(self.hasClass('active')) ){
					$('.belong-paper').text(self.find('.pageNum').text());
					$(op['link-news-link']).empty();
					var	op = this.options,
						url = './run.php?mid=' + gMid + '&a=get_article&type=edit_link',
						page_id = self.attr('_id');
					var allEpaper = $('.link-prev-item');
					allEpaper.removeClass('active');
					self.addClass('active');
					$.globalAjax(self,function(){
						return $.getJSON(url,{page_id : page_id},function(data){
							var arr = [];
							$.each(data,function(key,value){
								var info = {};
								info.id = value['id'];
								info.title = value['title'];
								info.num = key + 1;
								info.color = _this.linkColor[key%10];
								arr.push(info);
							});
							$(op['link-news-tpl']).tmpl(arr).prependTo(op['link-news-link']);
							var pic = self.attr('_pic'),
								news = $(op['link-news-link']).find('li'),
								hot_info = _this.hot_info[kk][nn];
							$('.prev-area').find('img').attr('src',pic);
							$('.hot-box').empty();
							var hotarea = $('.hotarea'),
								hot_width = hotarea.outerWidth(),
								hot_height = hotarea.outerHeight();
							$.each(hot_info,function(key,value){
								var info = this,
									hotarea = {},
									id =  value['id'] ;
								hotarea.top = value['top'] * hot_height;
								hotarea.left = value['left'] * hot_width;
								hotarea.width = value['width'] * hot_width;
								hotarea.height = value['height'] * hot_height;
								var filter = news.filter(function(){
									return $(this).attr("_id") == id;
								});
								$('.hotarea').hotarea('createHot',hotarea);
								var index = filter.index(),
									aim = $('.hot-item').eq(key).find('.option').eq(index);
								aim.click();
							});
						});
					});
				}
			},
			_uploadFile : function(){
				this.uploadPdf.click();
			},
			_saveHotarea :function( event ){
				var self = $(event.currentTarget),
					op = this.options,
					_this = this,
					kk = $(op['stack-item']).filter('.active').index(),
					nn = $(op['link-prev-item']).filter('.active').index(),
					url = './run.php?mid=' + gMid + '&a=update_link';
				var items = $('.hot-item'),
					hotInfo = [],
					hotarea = $('.hotarea'),
					hot_width = hotarea.width(),
					hot_height = hotarea.height();
				items.each(function(key,value){
					var info = {},
						ptop = $(this).position().top / hot_height
						pleft = $(this).position().left / hot_width,
						pwidth = $(this).width() / hot_width,
						pheight = $(this).height() / hot_height;
					info = {
							'id' : $(this).attr('_id'),
							'title' : $(this).attr('_title'),
							'top' : ptop,
							'left' : pleft,
							'width' : pwidth,
							'height' : pheight,
					};
					hotInfo.push(info);
					_this.hot_info[kk][nn] = hotInfo;
				});
				var page_id = $(op['link-prev-item']).filter('.active').attr('_id');
				$.globalAjax(self,function(){
					return $.get(url,{page_id:page_id,hotInfo:hotInfo},function(){
						self.myTip({
							string : '保存成功'
						});
						_this._closeHot();
					});
				});
			},
			_dropSelect : function( event ){
	        	var self = $(event.currentTarget);
	        	self.closest('.link-choose-area').find('.link-choose').show();
	        },
	        _upSelect : function( self ){
	        	var self = self;
	        	self.closest('.link-choose-area').find('.link-choose').hide();
	        },
			_selectLinkTo : function( event ){
	        	var self = $(event.currentTarget),
	        		father = self.closest('.link-choose-area'),
	        		hotItem = father.closest('.hot-item'),
	        		val = self.text(),
	        		i = self.index();
	        	father.find('.choosen').find('a').text(val).attr('title',val);
	        	father.find('.link-choose').hide();
	        	var obj = self.closest('.edit-link-area').find('.news-list').find('li'),
	        		id = obj.eq(i).attr('_id');
	        	hotItem.attr('_id',id);
	        	hotItem.attr('_title',self.text());
	        	event.stopPropagation();
	        	this._upSelect(self);
			},
			_sort : function( event ){
				var _this = this,
					op = this.options,
					self = $(event.currentTarget),
					text = self.text(),
					wrap = $('.link-news-link'),
					item = wrap.find('li');
				if( text == '开启排序' ){
					self.myTip({
						string : '排序模式已开启，拖动新闻标题进行排序',
						delay : 3000,
						width : 300
					});
					wrap.sortable({
						axis : 'y',
						start : function(){
							self.text('保存排序').css('background','orange');
						},
						stop : function( event, ui ){
							var self = $(ui.item);
							_this._changeOrder(self);
						}
					});
				}else if(text == '保存排序'){
					var id = item.map(function(){
						return $(this).attr('_id');
					}).get().join(',');
					var order_id = item.map(function(key){
						return (key+1);
					}).get().join(',');
					var url = './run.php?mid=' +gMid+ '&a=sort';
					$.globalAjax(self,function(){
						return $.get(url,{order_id : order_id, id:id},function(){
							self.myTip({
								string : '保存成功'
							});
							self.text('开启排序').css('background','#1bbc9b');
						});
					});
				}
			},
			_changeOrder : function(self){
				var self = self,
					i = self.index(),
					order = i + 1;
				self.find('.news-num').text(order);
				var nextAll = self.nextAll('li');
				$.each(nextAll,function(key, value){
					var n_order = order + 1 + key;
					$(this).find('.news-num').text(n_order);
				});
				var prevAll = self.prevAll('li');
				$.each(prevAll,function(key, value){
					var p_order = i - key;
					$(this).find('.news-num').text(p_order);
				});
			}
		});
	})($)
	$('.epaper-edit').editlink();
})