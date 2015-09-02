$(function(){
	(function($){
		var controll = {
			delajax : function( ids, obj, method, self ){
				var url = './run.php?mid=' + gMid + '&a=' + method + '&ajax=1';
				if( obj.length < 1 ){
					jAlert("请选择要删除的内容","删除提醒").position(self);
					return false;
				}
				if( obj.length > 1 ){
					var tip = "您确认批量删除选中记录吗？";
				}else{
					var tip = "您确定删除该条内容吗？";
				}
				if(obj.is('.selected')){
					jConfirm( tip , '删除提醒' , function( result ){
						if( result ){
							$.globalAjax( obj, function(){
							return $.get(url,{id : ids},function(){
									obj.remove();
									if($('.m2o-each').length < 1){
										$('#nodata-tpl').tmpl().appendTo('.m2o-each-list');
									}
								});
							});
						}
					}).position(self);
				}else{
					$.get(url,{id : ids},function(){
						obj.remove();
						if($('.m2o-each').length < 1){
							$('#nodata-tpl').tmpl().appendTo('.m2o-each-list');
						}
					});
				}
			},
			auditajax : function( ids, obj, method, self ){
				var url = './run.php?mid=' + gMid + '&a=' + method + '&ajax=1';
				if( obj.length < 1 ){
					jAlert("请选择要审核的内容","审核提醒").position( self );
					return false;
				}
				if( obj.length > 1 ){
					var tip = "您确认批量审核选中记录吗？";
				}else{
					var tip = "您确定审核该条内容吗？";
				}
				jConfirm( tip , '审核提醒' , function( result ){
					if( result ){
						$.getJSON(url,{id : ids},function( data ){
							if(data['callback']){
								eval( data['callback'] );
								return;
							}else{
								obj.text('已审核').css({'color':'#17b202'});
							}
						});
					}
				}).position( self );
			},
			
			auditchajax : function( ids, obj , method ){
				var url = './run.php?mid=' + gMid + '&a=' + method + '&ajax=1';
				var tip = "您确定审核该条内容吗？";
				$.globalAjax( obj, function(){
					return $.getJSON(url,{id : ids},function( data ){
						if(data['callback']){
							eval( data['callback'] );
							return;
						}else{
							var data = data[0];
							status = data.status;
							audit = data.audit;
							op = data.op;
							color = status_color[audit];
							obj.data('method', op);
							obj.text(audit).css('color',color)
											.attr('_status',status);
						}
					});
				});
			},
			
			backajax : function( ids, obj, method, self ){
				var url = './run.php?mid=' + gMid + '&a=' + method + '&ajax=1';
				if(obj.length < 1){
					jAlert("请选择要打回的数据", "打回提醒").position( self );
					return false;
				}
				if( obj.length > 1 ){
					var tip = "您确认批量打回选中记录吗？";
				}else{
					var tip = "您确定打回该条内容吗？";
				}
				jConfirm( tip , '打回提醒' , function( result ){
					if( result ){
						$.getJSON(url,{id : ids},function( data ){
							if(data['callback']){
								eval( data['callback'] );
								$('.load-img').remove();
								return;
							}else{
							obj.find('.m2o-state').text('已打回')
												  .attr('_status',2)
												  .css({'color':'#f8a6a6'});
							}
						});
					}
				} ).position( self );
			}
		};
		var status_color = {
			'待审核' : '#8ea8c8',
			'已审核' : '#17b202',
			'已打回' : '#f8a6a6'
		};
		$.widget('mag.left_list', {
			options : {
				each : '.magazine-each',
				box : '.m2o-m',
				addpop : '.pop-add',
				more : '.load-more',
				
				getmoremagUrl : ''
			},
			_create : function(){
				this.page = {
					nowCount: 20,
					step: 20,
				};
			},
			_init : function(){
				var op = this.options,
					handlers = {};
				handlers['click ' + op['each'] ] = '_getLast';
				handlers['click ' + op['more'] ] = '_loadMore';
				this._on(handlers);
				this._on({
					'click .del' : '_delItem',
				});
				this._initAjax();
				this.initData();
			},
			
			_getLast : function( event ){
				var self = $(event.currentTarget);
				if(self.hasClass('selected')){
					return;
				}else{
					self.addClass('selected')
						.siblings().removeClass('selected');
				}
				this._ajaxData( self );
			},
			
			_initAjax : function(){
				var op = this.options;
				var first = this.element.find( op['each'] ).eq(0);
				first.addClass('selected');
				this._ajaxData( first );
			},
			
			_ajaxData : function( obj ){
				var info = {
					issue_id : obj.data('issueid'),
					maga_id : obj.data('id'),
					magname : obj.find('input[name=magname]').val(),
					current_nper : obj.find('input[name=current_nper]').val(),
					volume : obj.find('input[name=volume]').val()
				}
				$( this.options.box ).view( 'getData', obj, info);
			},
			
			_loadMore : function(){
				var op = this.options,
					_this = this,
					offset = this.page.nowCount,
					count = this.page.step;
				$.getJSON(this.options.getmoremagUrl, {offset : offset, count : count}, function(data){
					if(data['callback']){
						eval( data['callback'] );
						return;
					}else{
						_this.requestAjax( data );
					}
				});
			},
			
			_delItem : function( event ){
				var self = $(event.currentTarget),
				obj = self.closest('.magazine-each'),
				id = obj.data('id'),
				method = self.data('method');
				this._delajax( id, obj ,method );
				event.stopPropagation();
			},
			
			_delajax : function( ids, obj ,method ){
				var _this = this;
				var url = './run.php?mid=' + gMid + '&a=' + method,
					tip = "您确定删除该条内容吗？";
				jConfirm( tip , '删除提醒' , function( result ){
					if( result ){
						$.getJSON(url,{id : ids},function( data ){
							if( data == "-1" ){
								jAlert("请先删除该杂志下的期刊", "删除提醒");
								return false;
							}else if(data['callback']){
								eval( data['callback'] );
								return;
							}else{
								obj.remove();
								if(!obj.prev().length){
									_this.initAjax();
								}
							}
						});
					}
				});
			},
			
			initData : function(){
				var op = this.options,
					len = this.element.find( op['each'] ).length;
				if(len < this.page.step){
					$( op['more'] ).remove();
				}
			},
			
			requestAjax : function( data ){
				var data = data[0],
					op = this.options,
					lent = data.length;
					info = {};
				if(lent){
					$.each(data, function(key, value){
						info.id = value.id;
						info.tname = value.name;
						info.issue_id = value.issue_id;
						info.sort_name = value.sort_name;
						info.release_cycle = value.release_cycle;
						info.user_name = value.user_name;
						info.year = value.year;
						info.current_nper = value.issue;
						info.total_issue = value.total_issue;
						info.create_time = value.create_time;
						info.url = value.url;
					$('#magadd-tpl').tmpl(info).appendTo('.magazine-list');	
					});
					this.page.nowCount += lent; 
				}
				if(lent < this.page.step){
					this.element.find( op['more'] ).remove();
				}
			},
			
			// updataIssuedata : function( data, magid ){
				// var op = this.options;
				// var obj = $( op['each'] ).filter(function(){
					// return ($(this).data('id') == magid);
				// });
				// this.replaceLastest( data, obj );
			// },
// 			
			// replaceLastest : function( data, obj ){
				// var cont = obj.find('p span').html();
				// var url = data.img_info.url,
					// issueid = data.id,
					// year = data.year,
					// issue = data.issue,
					// total_issue = data.total_issue;
					// create_time = data.create_time;
				// obj.find('img').attr('src', url);
				// obj.data('issueid', issueid);
				// obj.find('h4').html(year + '第' + issue + '期 总' + total_issue + '期');
				// obj.find('p span').html(cont + ' ' +create_time);
			// },
			
		});
		
		$.widget('mag.view', {
			options : {
				getarticleUrl : '',
				
				articletpl : '',
				articletname : 'articallist-tpl',
				profiletpl : '',
				profiletname : 'getprofile-tpl',
				nodatatpl : '',
				nodatatname : 'nodata-tpl'
			},
			_create : function(){
				this.status_color = ['#8ea8c8','#17b202','#f8a6a6'];
				$.template(this.options.articletname, this.options.articletpl);
				$.template(this.options.profiletname, this.options.profiletpl);
				$.template(this.options.nodatatname, this.options.nodatatpl);
				this.pageInit = false;
			},
			_init : function(){
				
			},
			
			getData : function( obj, info ){
				var _this = this;
				var param = {};
				param.issue_id = info.issue_id;
				info.page ? param.page = info.page : '';
				info.count ? param.count  = info.count : '';
				$.getJSON(this.options.getarticleUrl, param, function(data){
					if(data['callback']){
						eval( data['callback'] );
						return;
					}else{
						var data = data[0] || '';
						_this.distribute( data, info );
					}
				});
			},
			
			distribute : function( data, info ){
				this.getArticle( data.info );
				this._getSort( data.sort_info, info );
				data.page_info && this._initPage( data.page_info, info );
				this.element.find('.m2o-list').list_sort();
				$('#searchform').find('input[name=issue_id]').val(info.issue_id);
				$('#searchform').find('input[name=maga_id]').val(info.maga_id);
			},
			
			getArticle : function( data ){
				if(data){
					var _this = this;
					var box = this.element.find('.m2o-each-list'),
						articleData = [];
					$.each(data, function(key, value){
						var param = {};
						param.id = value.id;
						param.order_id = value.order_id;
						param.articletitle = value.title;
						param.sort_name = value.sort_name;
						param.article_author = value.article_author;
						param.redactor = value.redactor;
						param.state = value.state;
						param.audit = value.audit;
						if(param.audit == "已审核"){
							param.op = 'back';
						}else{
							param.op = 'audit';
						}
						param.user_name = value.user_name;
						param.create_time = value.create_time;
						indexpic_url = value.indexpic_url;
						param.status_color = _this.status_color[param.state];
						if(indexpic_url){
							param.indexpic = $.globalImgUrl(value['img_info'], '40x30');
						}
						articleData.push( param );
					});
					this._clearData( box );
					$.tmpl(this.options.articletname, articleData).appendTo( box );
					this._addEvent( articleData );
					this._judgeHeight();
				}else{
					this._noArticle();
				}
			},
			
			_judgeHeight : function(){
				var pheight = $('.m2o-m').find('.magazine-profile').height(),
					lheight = $('.m2o-m').find('.m2o-list').height();
				var mheight = pheight + lheight;
				if(mheight < 699){
					mheight = 699;
				}
				mheight = (mheight > 699) ? mheight : 699;
				$('.m2o-lastest').css('height', mheight);
			},
			
			_noArticle : function(){
				var box = this.element.find('.m2o-each-list');
				this._clearData( box );
				$.tmpl(this.options.nodatatname).appendTo( box );
			},
			
			_getSort : function( data, info ){
				var area = this.element.find('.magazine-profile');
				var param = {}, profileData = [];
				param = info;
				param.sortlist = data;
				profileData.push(param);
				this._clearData( area );
				$.tmpl(this.options.profiletname, profileData).appendTo( area );
			},
			
			_initPage : function( option ){
				var _this = this;
				var page_box = this.element.find('#page_size');
				if(this.pageInit){
					page_box.page('refresh',option);
				}else{
					option['page'] = function( event, page, count ){
						var info = {};
						info.page = page;
						info.count = count;
						_this.refresh( info );
					}
					page_box.page( option );
					this.pageInit = true;
				}
			},
			
			refresh : function( param ){
				var obj = $('.m2o-lastest').find('.magazine-each.selected');
				var info = {
					issue_id : obj.data('issueid'),
					maga_id : obj.data('id'),
					magname : obj.find('input[name=magname]').val(),
					current_nper : obj.find('input[name=current_nper]').val(),
					volume : obj.find('input[name=volume]').val(),
					page : param.page,
					count : param.count
				}
				this.getData( obj, info );
	    	},
			
			_addEvent : function( articledata ){
				$('.m2o-each').geach({
					'audit' : function( event, _this ){
						var self = $(event.currentTarget),
							id = self.closest( _this.element ).data('id'),
							method = self.data('method');
						controll.auditchajax( id, self, method );
					}
				});
				$('.m2o-list').glist({
					'batchDelete' : function(event,_this){
						var op = _this.options,
							obj = _this.element.find( op['each'] + '.selected' ),
							self = $(event.currentTarget),
							method = self.data('method');
						var ids = obj.map(function(){
							return $(this).data('id');
							}).get().join(',');
						controll.delajax( ids, obj ,method, self );
					},
					'batchAudit' : function(event,_this){
						var op = _this.options,
							obj = _this.element.find( op['each'] + '.selected' ),
							status = obj.find('.m2o-state'),
							self = $(event.currentTarget),
							method = self.data('method');
						var ids = obj.map(function(){
							return $(this).data('id');
							}).get().join(',');
						controll.auditajax( ids, status, method, self );
					},
					'batchBack' : function(event, _this){
						var op = _this.options,
							obj = _this.element.find( op['each'] + '.selected' ),
							self = $(event.currentTarget),
							method = self.data('method');
						var ids = obj.map(function(){
							return $(this).data('id');
							}).get().join(',');
						controll.backajax( ids, obj, method, self );
					}
				});
				data = articledata;
			    $.extend($.geach || ($.geach = {}), {
			        data : function(id){
			            var info;
			            $.each(data, function(i, n){
			               if(n['id'] == id){
			                   info = {
			                       id : n['id']
			                   }
			                   return false;
			               }
			            });
			            return info;
			        }
			    });
			},
			
			_clearData : function( obj ){
				obj.empty();
			},
		});
	})($);
	$('.m2o-m').view({
		getarticleUrl : './run.php?mid=' + gMid + '&a=get_article&ajax=1',
		articletpl : $('#articallist-tpl').html(),
		profiletpl : $('#getprofile-tpl').html(),
		nodatatpl : $('#nodata-tpl').html()
	});
	$('.m2o-lastest').left_list({
		getmoremagUrl : './run.php?mid=' + gMid + '&a=get_more_maga&ajax=1'
	});
	var searchForm = $('#searchform');
	searchForm.submit(function(){
		$(this).ajaxSubmit({
			beforeSubmit : function(){

			},
			dataType : 'json',
			success : function( data ){
				var data = data[0] || '';
				var obj = $('.m2o-lastest').find('.magazine-each.selected');
				var info = {
					issue_id : obj.data('issueid'),
					maga_id : obj.data('id'),
					magname : obj.find('input[name=magname]').val(),
					current_nper : obj.find('input[name=current_nper]').val(),
					volume : obj.find('input[name=volume]').val()
				}
				$('.m2o-m').view( 'distribute', data, info );
			},
		});
		return false;
	});
});
