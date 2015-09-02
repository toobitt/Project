$(function(){		//新闻编辑页
	(function($){
	$.widget('epaper.edit',{
		options : { },
		_init : function(){
			var _this = this;
			this._on({
				'click .stack-add' : '_addStack',
				'click .page-add' : '_addPage',
				'click .stack-item' : '_changeStack',
				'click .page-item' : '_changeEpaper',
				'click .news-add' : '_addNews',
				'submit .news-edit-form' : '_saveNewsDetail',
				'click .news-title' : '_changeNews',
				'click .edit-news-btn' : '_editNewsTitle',
				'click .del-news-btn' : '_delNews',
				'click .title-info' : '_getNewsTitle',
				'blur .title-info' : '_setNewsTitle',
				'mouseenter .page-item' : '_del',
				'mouseleave .page-item' : '_cancel',
				'click .del-last' : '_delLast',
				'click .epaper-news-mask' : '_mask',
			});
			this._default();
		},
		_default : function(){
			this.newsInfo = {
					'subtitle' : '',
					'source' : '',
					'author' : '',
					'photoer' : '',
					'content' : ''
				};
			this.newsTitle = '';
			this.stackItem = null;
			this.epaperItem = null;
			this._initEdit();
			$('.stack-item').eq(0).click();
			$('#news-detail-tpl').tmpl({
				stack : 'A叠',
				page_num : ' A1'
			}).prependTo($('.news-detail-box'));
		},
		/** 初始化编辑器 */
		_initEdit : function(){
			var _this = this,
				widget = this.element;
			this._addmask();
			var	init = function(){
				$.myueditor = $.m2oEditor.get( 'epaper_editor', {
					initialFrameWidth : 823,
					initialFrameHeight : 420,
					countDom : null,
					tipBtns : [ 'title', 'link' ]
				});
				$.myueditor.addListener('_title',function( event,text ){
					var item = $('.news-titles.show').find('.active');
					item.find('input').val( text );
					_this.newsTitle = text;
					_this._ajaxNewsTitle( item );
				});
				$.myueditor.addListener('_desc',function( event,text ){
					$('input[name="subtitle"]').val( text );
				});
				_this._removemask();
			};
			$.includeUEditor( init, {
				plugins: ['imginfo','imglocal','imgmanage','tip','water','removetag']
			} );
		},
		/** 切换一叠 */
		_changeStack : function( event ){
			var self = $(event.currentTarget),
				_this = this,
				flag = self.attr('_flag');
			this.stackItem = self;
			if( !(self.hasClass('active')) ){
				self.addClass('active').siblings().removeClass('active');
				$('.news-titles').removeClass('show');
				//判断是否需要发起请求
				if( self.attr('_needajax') ){
					var	i = self.index(),
						url = './run.php?mid=' + gMid + '&a=get_page',
						stack_id = self.attr('_id'),
						period_id = $('.period_id').val();
					//1.插入版面列表
					var new_page = $('<ul />').insertBefore('.page-add').attr({
						'class' : 'each-list page',
						'_belong' : flag
					}).addClass('show').siblings().removeClass('show');
					var currentPageList = $('.page').filter('.show');
					$.globalAjax(self,function(){
						return $.getJSON(url,{stack_id:stack_id, period_id:period_id},function(data){
							$.each(data,function(key,value){
								var id = this['id'],
								page_num = key+1;
								var page = $('<li />').appendTo(currentPageList).attr({
									'class' : 'page-item',
									'_needajax' : 'true',
									'_id' : id,
									'_pageNum' : page_num
								}).text(flag + page_num + '');
							});
							currentPageList.find('li').eq(0).click();
						});
					});
					self.attr('_needajax','');
				}else{
					var currentPageList = $('.page').filter(function(){
						return ($(this).attr('_belong') == flag);
					});
					currentPageList.addClass('show').siblings().removeClass('show');
					currentPageList.find('li:first-child').click();
				}
			}
		},
		_changeEpaper : function( event ){
			var self = $(event.currentTarget),
				i = self.index();
			this.epaperItem = self;
			if( !(self.hasClass('active')) ){
				$('.page-item').removeClass('active');
				self.addClass('active')
				if( self.attr('_needajax') ){	
					var	url = './run.php?mid=' + gMid + '&a=get_article',
						page_id = self.attr('_id');
					$('<ul />').appendTo('.news-list').attr({
						'class' : 'news-titles',
						'_belong' : self.text()
					});
					$.globalAjax(self,function(){
						return $.getJSON(url,{page_id : page_id},function(data){
							var	currentNewsList = $('.news-titles').filter('.show');
							$.each(data,function(key,value){
								$('#news-title-tpl').tmpl({
									id : this.id,
									title : this.title
								}).appendTo(currentNewsList);
							});
						});
					});
					$('.mask').show();
					self.attr('_needajax','');
				}
			}
			$('.news-titles').filter(function(){
				return ( $(this).attr('_belong') == self.text() )
			}).addClass('show').siblings('ul').removeClass('show');
		},
		_tabNews : function( self ){
			self.addClass('active').siblings().removeClass('active');
			var	_this = this,
				url = './run.php?mid=' + gMid + '&a=detail',
				id = self.attr('_id'),
				content = '';
			var info = {
					stack : this.stackItem.text(),
					stack_id : this.stackItem.attr('_id'),
					page_num : this.epaperItem.text(),
					page_id : this.epaperItem.attr('_id'),
					news_title : $('.news-title').filter('.active').find('input').val()
				};
			if(id){
				$.globalAjax(self,function(){
					return $.getJSON(url,{id:id},function(data){
						var data = data[0],
							editorPic = [];
						for( var i in data['material'] ){
							editorPic.push( data['material'][i] );
						}
						$.editorPlugin.get( $.myueditor , 'imgmanage').imgmanage('resetManageView', editorPic);
						if ( data ){
							info['subtitle'] = data['subtitle'];
							info['source'] = data['source'];
							info['author'] = data['author'];
							info['photoer'] = data['photoer'];
							info['period_id'] = data['period_id'];
							info['news_title_id'] = data['id']
							content = data['content'];
						}
						_this._refreshDetailView( info, content );
						_this.newsInfo = _this._getNewsInfo();
					});
				});
			}else{
				_this._refreshDetailView( info, content );
			}
		},
		_refreshDetailView : function( info, content ){
			$.myueditor.setContent(content || '');
			$('#news-detail-tpl').tmpl( info ).prependTo($('.news-detail-box').empty());
		},
		_changeNews : function( event ){
			$('.mask').hide();
			var self = $(event.currentTarget),
				_this = this;
			var same = _this._compareInfo();
			if( !same ){
				jConfirm('修改还未保存，现在要保存么？','保存提示',function(result){
					result ? $('.save-button').click() : _this._tabNews(self);
				});
			}else{
				_this._tabNews(self);
			}
		},
		_getNewsInfo : function(){
			return {
				'subtitle' : $('input[name="subtitle"]').val(),
				'source' : $('input[name="source"]').val(),
				'author' : $('input[name="author"]').val(),
				'photoer' : $('input[name="photoer"]').val(),
				'content' : $.myueditor.getContent()
			};
		},
		_compareInfo : function(){
			var newInfo = this._getNewsInfo(),
				oldInfo = this.newsInfo,
				newArr = [],
				oldArr = [];
			for( var i in newInfo ){
				newArr.push(newInfo[i]);
			};
			for( var j in oldInfo ){
				oldArr.push(oldInfo[j]);
			};
			var newStr = $(newArr).get().join(','),
				oldStr = $(oldArr).get().join(',');
			return newStr == oldStr;
		},
		_delNews : function( event ){
			var self = $(event.currentTarget),
				_this = this,
				father = self.closest('li');
			event.stopPropagation();
			jConfirm("确定要删除吗？","删除提醒",function( result ){
				if(result){
					var url = './run.php?mid=' + gMid + '&a=delete&ajax=1',
						id = father.attr('_id');
					$.globalAjax(self,function(){
						return $.getJSON(url,{id:id},function(data){
							if( data['callback'] ){
								eval( data['callback'] );
								return;
							}
							if( father.hasClass('active') ){
								_this.newsInfo = _this._getNewsInfo();
							}
							father.slideUp(function(){
								father.remove();
							});
							$('.editable').val('');
							$.myueditor.setContent('');
							$('.mask').show();
						});
					});
				}
			});
		},
		_editNewsTitle : function( event ){
			var self = $(event.currentTarget),
				aim = self.closest('li').find('input');
			aim.removeAttr('readonly').css('border-color','#ccc');
			aim.click().focus();
			event.stopPropagation();
		},
		_getNewsTitle : function( event ){
			this.newsTitle = $(event.currentTarget).val();
		},
		_setNewsTitle : function( event ){
			var self = $(event.currentTarget),
				father = self.closest('li');
			self.attr('readonly','readonly').css('border-color','transparent');
			var flag = !( this.newsTitle == self.val() );
			if( father.attr('_id') && flag){
				this._ajaxNewsTitle(father);
			}
			event.stopPropagation();
		},
		_ajaxNewsTitle : function(target, param){
			$.globalAjax(target,function(){
				var url = './run.php?mid=' + gMid + '&a=update_title&ajax=1',
					param = {
							title : target.find('input').val(),
							id : target.attr('_id')
						};
				return $.getJSON(url, param, function( data ){
					if( data['callback'] ){
						eval( data['callback'] );
						return;
					}
				})
			});
		},
		_saveNewsDetail : function( event ){
			event.stopPropagation();
			var self = $(event.currentTarget),
				newsEditorContent = $.myueditor.getContent(),
				saveBtn = self.find('.save-button');
			if( !newsEditorContent ){
				saveBtn.myTip({
					string : '请填写新闻内容',
					delay : 2000,
					color : '#ee8176'
				});
				return false;
			}
			var	url = '',
				_this = this,
				id = $('.news_title_id').val(),
				title_hid = this.element.find('.news_title'),
				currentNews = $('.news-titles').filter('.show'),
				val = currentNews.find('.active').find('input').val();
			if( !val ){
				currentNews.find('.active').myTip({
					string : '请先填写新闻标题！'
				});
				return false;
			}
			$(title_hid).val(val);
			url = './run.php?mid=' + gMid + '&a=' + (id ? 'update' : 'create') + '&ajax=1';
			var load = $.globalLoad(self);
			this._refreshEditorContentToHidden( self );
			self.ajaxSubmit({
				url : url,
				dataType : 'json',
				success : function( data ){
					load();
					if( data['callback'] ){
						eval( data['callback'] );
						return;
					}
					var data = data[0],
						id = data['id'];
					currentNews.find('.active').attr('_id',id);
					$('.m2o-main').myTip({
						string : '保存成功！'
					});
				},
				error : function(){
					$('.m2o-main').myTip({
						string : '请填写新闻内容'
					});
				}
			});
			_this.newsInfo = _this._getNewsInfo();
			return false;
		},
		_refreshEditorContentToHidden : function( form ){
			form.find('[name="content"]').val( $.myueditor.getContent() );
		},
		_addStack : function( event ){
			var	self = $(event.currentTarget),
				prev = self.prev(),
				id = parseInt(prev.attr('_id'))+1,
				flag = String.fromCharCode(prev.attr('_flag').charCodeAt(0)+1),
				html = "<li class='stack-item' _id='" + id + "' _flag='" + flag +"'>" + flag + "叠</li>";
			$(html).insertBefore(self).addClass('active').siblings().removeClass('active');
			var newPageList = $('<ul />').insertBefore('.page-add').attr({
				'class' : 'page',
				'_belong' : flag
			}).addClass('show').siblings('ul').removeClass('show');
		},
		_addPage : function(event){
			var self = $(event.currentTarget);
				period_id = $('.period_id').val(),
				stack_id = $('.stack-item').filter('.active').attr('_id'),
				currentPageList = $('.page').filter('.show');
			var len = currentPageList.find('li').length,
				prevPageNum = currentPageList.find('li:last-child').attr('_pageNum'),
				page_num = len ? parseInt(prevPageNum)++ : 1;
			var	url = "./run.php?mid=" + gMid + "&a=create_page&period_id="+ period_id +"&stack_id="+ stack_id +"&page_num=" + page_num;
				$.getJSON(url,function(data){
					var data = data + '',
						flag = $('.stack-item').filter('.active').attr('_flag'),
						html = "<li class='page-item' _needajax='true' _id='"+ data + "' _pagenum='" + page_num + "'>" + flag + page_num + "</li>";
					$(html).appendTo(currentPageList).click();
				});
		},
		/** 添加新闻 */
		_addNews : function( event ){
			var self = $(event.currentTarget),
				aim = $('.news-titles').filter('.show');
			if( !aim.length ){
				$(self).myTip({
					string : '该叠下还没有版，请先去上传版',
					delay : 2000,
					width : 200
				});
				return;
			}
			var same = this._compareInfo();
			if( !same ){
				self.myTip({
					string : '修改还未保存，请先保存再添加',
					delay : 2000,
					width : 200
				});
				return;
			}else{
				$('#news-title-tpl').tmpl().prependTo(aim).addClass('added');
				var theNew = $('.added');
				theNew.find('input').focus();
				theNew.find('.edit-news-btn').click();
				$('.mask').hide();
				$('.added').removeClass('added');
				$('.info-items').remove();
				var info = {
						stack_id : this.stackItem.attr('_id'),
						page_id : this.epaperItem.attr('_id'),
						stack : this.stackItem.text(),
						page_num : this.epaperItem.text()
				};
				this._refreshDetailView( info );
			}
		},
		_del : function( event ){
			var self = $(event.currentTarget),
				parent = self.closest('ul'),
				next = self.next().length;
			if( !next ){
				$('<span />').prependTo(self).attr('class','del-last').text('x');
				self.css('box-shadow','0 0 2px 1px #fff inset');
			}
		},
		_cancel : function(){
			$('.del-last').remove();
			$('.page-item').css('box-shadow','none');
		},
		_delLast : function( event ){
			event.stopPropagation();
			var self = $(event.currentTarget),
				parent = self.closest('li'),
				page_id = parent.attr('_id'),
				period_id = $('#info').attr('_periodId'),
				url = './run.php?mid=' + gMid + '&a=del_page&page_id=' + page_id + '&period_id=' + period_id ; 
			$.globalAjax(self,function(){
				return $.getJSON(url,function(data){
					if ( data == "-1" ){
						self.myTip({
							string : '请先删除该页下的新闻',
							width : 150
						});
					}else{
						parent.slideUp();
						setTimeout(function(){
							parent.remove();
						},300)
					}
				});
			});
		},
		_mask : function( event ){
			var self = $( event.currentTarget );
			self.myTip({
				width : '140',
				string : '编辑器初始化中,请稍候...',
				delay : 1000,
				color : '#ee8176'
			});
		},
		_addmask : function(){
			var new_list_area = this.element.find('.news-list');
			this.mask = $('<div class="epaper-news-mask" title=""/>').prependTo( new_list_area );
		},
		_removemask : function(){
			this.mask.remove();
		}
	});
})($);
	$('.epaper-edit').edit();
});