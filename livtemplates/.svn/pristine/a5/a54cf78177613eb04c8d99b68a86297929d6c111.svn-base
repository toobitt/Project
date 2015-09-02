$(function(){
    $.widget('m2o.nav', {
        options : {
        	url : '',
            tpl : '',
            tname : 'nav-tpl'
        },
        _init : function(){
        	$.template(this.options.tname, this.options.tpl);
            this._on({
                'click .hook' : '_stretch',
                'click .title' : '_selected',	//刷新右侧列表
                'click .title .fast-publish' : '_showGenerate'
            });
            this._root();
        },
        _root : function(){
            var _this = this,
            	parent = _this.element.find('.column-list').find('ul');
            $.globalAjax(parent, function(){
                return _this._ajax(siteId, parent);
            });
        },
        _ajax : function(id, parent){
            var _this = this;
            var url = this.options.url;
            return $.getJSON(url, {site_id : id}, function(json){
            		var data = json['0'],
            			is_open_mk = data['is_open_mk'] || 0;
            		var mainPage = data['main_page'];
            		mainPage ? _this.element.removeClass('hide-a') : _this.element.addClass('hide-a');
                    $.tmpl(_this.options.tname, data['column'], {is_open_mk:is_open_mk}).appendTo(parent.empty());
                    $.extend( $.MC.cache, {'site_id':data['site_id'],'page': data['page'],'main_page':data['main_page']} );
                    _this.element.find('.title').eq(0).click();
                    _this.element.find('.hook').eq(0).click();
                }
            );
        },
        _stretch : function(event){
        	var self = $(event.currentTarget),
        		item = self.closest('li'),
        		_this = this,
            	cname = 'stretch-list';
            if( item.hasClass(cname) ){
            	item.find('.stretch-list').removeClass(cname);
        		item.find('ul').hide();
            }else{
            	if(item.attr('_ajax')){
        			item.find('ul').eq(0).show();
        		}else{
        			item.attr('_ajax', true);
        			this._appendBox(item);
        			$.globalAjax(self, function(){
                        return $.getJSON(_this.options.url,{site_id : $.MC.cache['site_id'],fid : item.data('fid')},function(json){
                        	var data = json['0'],
                        		is_open_mk = data['is_open_mk'] || 0;
                            $.tmpl(_this.options.tname, data['column'], {is_open_mk:is_open_mk}).appendTo(item.find('ul').empty());
                            $.extend( $.MC.cache, {'site_id':data['site_id'],'page': data['page'],'main_page':data['main_page']} );
                        });
                    });
        		}
            }
        	item.toggleClass(cname);
        },
        _appendBox : function(parent){
            $('<ul><li class="no-child"><img src="' + RESOURCE_URL + 'loading2.gif" style="width:30px;"/></li></ul>').appendTo(parent);
        },
        _selected : function(event){
            var self = $(event.currentTarget);
//			if(self.hasClass('selected')){
//        		return;
//        	}
            var	item = self.closest('li'),
            	all = this.element.find('.title'),
            	id = item.data('id'),
            	cname = 'selected';
            all.removeClass( cname );
            self.toggleClass( cname );
            var param = '';
            var fid = $('body').search('getNode'),
    			search = $('body').search('getFormInfo');
            if( !item.hasClass('top-item') ){
            	param = '&site_id='+ $.MC.cache['site_id'] + fid + search;
            }else{
            	param = '&site_id='+ $.MC.cache['site_id'] + search;
            }
            $.MC.list.list('refresh', self, param);
            this._posTop();
        },
        _posTop : function(){
        	var limitTop = 300,
        		top_body = top.$('body'),
        		scrollTop = top_body.scrollTop();
        	if( scrollTop > limitTop );{
        		var mask = $('<div/>').css( {'position':'absolute','width':'100%','height':'100%','z-index':100000} ).prependTo('body');
        		top_body.animate( { scrollTop : 0 },1000, function(){
        			mask.remove();
        		} );
        	}
        },
        _showGenerate : function(event){
            var self = $(event.currentTarget);
            $.MC.generate.generate('show', self)
            self.closest('.title').addClass('on');
            return false;
        },
        emptyGenerate : function(){
            this.element.find('.on').removeClass('on');
        },
        refresh : function( site_id ){
        	var _this = this;
        	$.globalAjax($('.choice-area'), function(){
        		var url = _this.options.url + '&site_id='+site_id;
        		return $.getJSON(url,function( json ){
        			var data = json['0'],
        				parent = _this.element.find('.column-list').find('ul');
        			var mainPage = data['main_page'];
        			mainPage ? _this.element.removeClass('hide-a') : _this.element.addClass('hide-a');
        			$.tmpl(_this.options.tname, data['column']).appendTo(parent.empty());
                    $.extend( $.MC.cache, {'site_id':data['site_id'],'page': data['page'],'main_page':data['main_page']} );
                    $.MC.list.list('refresh', $('.choice-area') ,'&site_id='+site_id + $('body').search('getFormInfo'));
        		});
            });
        }
    });
    
    //列表项
    $.widget('m2o.list',{
    	options : {
    		url : '',
    		tpl : '',
    		page : $.noop
        },
        _init : function(){
            $.template('m2o-each', this.options.tpl);
            this._on({
            	'click .edit-btn' : '_editPublishTime',
            	'click .sure' : '_updatePublishTime'
            });
        },
        _editPublishTime : function( event ){
        	var self = $(event.currentTarget);
        	var input = self.siblings('.edit-time');
        	input.hg_datepicker();
        	input.show().focus();
        	self.siblings('.sure').css('display','inline-block');
        },
        _updatePublishTime : function( event ){
        	var self = $(event.currentTarget),
        		parent = self.closest('.m2o-each'),
        		time = self.siblings('input');
        	var url = './run.php?mid=' + gMid + '&a=update_content_relation&id='+parent.data('id')+'&publish_time='+time.val(); 
        	$.globalAjax(parent,function(){
        		return $.getJSON(url, function(json){
        			self.myTip({
        				string : '更新成功'
        			});
        			self.hide();
        			time.hide();
        			self.siblings('.time').text( time.val() );
        		});
        	});
        },
        _getListInfo : function( load, param ){
        	var url = this.options.url,
        		_this = this;
        	$.globalAjax(load, function(){
        		var newUrl = url + param;
        		return $.getJSON(newUrl, function(json){
        			var data = $.globalListData = json[0]['content_data'];
        				$.extend($.geach || ($.geach = {}), {
        					data : function(id){
        						var info;
        						$.each(data, function(i, n){
        							var status = n['status'];
        							if(n['rid'] == id){
        								info = {
        										id : n['rid'],
        										status_show : $.global_status_show[ status ],
        										data : JSON.stringify(n)
        			           			}
        								$.extend( info, n );
        			             		return false;
        			    			}
        			      		 });
        			   			return info;
        			        }
        			    });
        				_this._refreshList( json, param );
	                }
	            );
            });
        },
        _refreshList : function( json,param ){
        	var listData = json[0]['content_data'],
        		pageDate = json[0]['page'],
        		widget = this.element;
        	widget.find('.m2o-each').each(function(){
        		if($(this).is(':m2o-geach')){
        			$(this).geach('destroy');
        		}
        	});
        	widget.empty();
        	if( !listData.length ){
        		$('<p class="no-content" />').text('没有符合条件的内容！').appendTo(widget);
        		$.MC.page.hide();
        		return;
        	}
        	$.tmpl('m2o-each', listData ).appendTo(widget);
        	this._flagKey( param );
        	this._initPage( pageDate );
        	this._initWeightColor();
        	this._addEvent();
        	
        	
        	window.App = Backbone;
        	var Records = window.Records;
        	var RecordsView = window.RecordsView;
        	var WeightBox = window.WeightBox;
        	var Publish_box = window.Publish_box;
        	var models = $.map( listData,function( value, key){
        		value['id'] = value['rid'];
        		return value;
        	} );
        	
        	recordCollection = new Records;
        	recordsView = new RecordsView({ el: $('.m2o-each').parent(), collection: recordCollection });
            recordCollection.add( models );
            if( this.weight_init ){
            	return;
            }
            if (WeightBox) {
            	new WeightBox({ el: $('#weight_box') });
         	}
            if( Publish_box ){
                if ($('#add_share').size()) {
    		        new Publish_box({
    		        	beforeCreate: function(view) {
    		        		view.$el.removeAttr('style').html(
    							'<div class="publish-box"><iframe></iframe></div>'
    						);
    		        	},
    		            el: $('#add_share'),
    		            plugin: 'hg_share',
    		            initialized: function(view) {
    		            	App.on('openShare_box', view.open, view);
    		            	App.on('closeShare_box', view.close, view);
    		            }
    		        });
    		    }
            }
            this.weight_init = true;
        	
        },
        _flagKey : function( param ){
        	if(!param) return;
        	var match = param.match(/k(?:ey)?=([^&]*)/);
            if(match && match[1] && $.trim(match[1])){
                var key = decodeURI(match[1]);
                if(/^\+*$/.test(key)) return;
                key = key.replace(/^\++/, '').replace(/\++$/, '');
                var reg = new RegExp(key, 'ig');
                this.element.find('.m2o-common-title').each(function(){
                	var _title = $(this).html(); 
                    _title = _title.replace(reg, '<b style="color:red;font-style:normal;font-weight:normal;">' + key + '</b>');
                    $(this).html(_title);
                });
            }
        },
        _initPage : function( pageDate ){
        	var pageOption = {
                	current_page : pageDate['current_page'],
            		total_page : pageDate['total_page'],
            		total_num : pageDate['total'],
            		page_num : pageDate['offset']
                };
        	if( $.MC.pageInit ){
        		$.MC.page.page('refresh',pageOption);
        	}else{
        		pageOption['page'] = function( event, page, count ){
        			var column = $.MC.nav.find('.title.selected'),
        	    		columnId = column.closest('li').data('id'),
	        	    	colStr = '';
	            	if( columnId ){
	            		colStr = '&fid=' + columnId;
	            	}
	            	var fid = $('body').search('getNode'),
	            		search = $('body').search('getFormInfo');
	            	var param = '&offset=' + count + '&page=' +page + colStr + fid + search;
	            	$.MC.list.list('refresh', null, param);
        		};
        		$.MC.page.page(pageOption);
        	}
        	$.MC.pageInit = true;
        },
        _initWeightColor : function(){
    		var weight_items = this.element.find( '.weight-inner' );
    		weight_items.each( function(){
    			var weight = $(this).attr( '_weight' ),
    				rgb_color = create_color_for_weight( weight );
    			$(this).css( 'background', rgb_color );
    		} );
    	},
        _addEvent : function(){
        	$('.m2o-each').geach({
        		key : 'rid'	//删除传递id可配置
        	});
        	$.MC.listwrap.glist({
        		key : 'rid'
            });
        },
        refresh : function( load, param ){
        	$.destructState();
        	$.closeDragView('排序模式已关闭');
        	this._getListInfo( load, param );
        }
    });
	
    //生成框
    $.widget('m2o.generate',{
    	options : {
    		url : '',
    		tpl : '',
    		tname : 'gen-tpl'
    	},
    	_init : function(){
    		$.template(this.options.tname, this.options.tpl);
    		this._on({
    			'click .genBtn' : '_submitForm',
    			'click i' : 'hide',
    			'click .choose-item' : '_choose',
    			'click .arrow' : '_showSel'
    		});
    	},
    	show : function( target ){
    		$.tmpl(this.options.tname).appendTo( this.element.empty() );
    		var $target = $(target);
            var $this = this.element.show();
            var pp = $target.offset();
            var tHeight = $target.outerHeight();
            var sHeight = $this.outerHeight();
            var left = pp.left;
            var top = pp.top + tHeight;
            var dHeight = $(document).height();
            if(top + sHeight > dHeight){
                top = pp.top - sHeight;
            }
            $this.css({
                left : left + 'px',
                top : top + 'px'
            });
            $.MC.nav.nav('emptyGenerate');
            this.left = left;
            this.top = top;
            this.element.find('.date-picker').hg_datepicker();
    	},
    	hide : function(){
            this.element.empty().hide();
            $.MC.nav.nav('emptyGenerate');
        },
    	_submitForm : function( event ){
    		var form = this.element.find('form'),
    			self = $(event.currentTarget),
    			load = $.globalLoad( form ),
    			_this = this;
    		form.find('input[name="client_type"]').val( $.MC.search.find('#client_type').val() );
    		form.find('input[name="site_id"]').val( $.MC.cache['site_id'] );
    		form.find('input[name="m_type"]').val( self.attr('_type') );
    		var aim = $.MC.nav.find('.title.on').closest('li');
    		var fid = '';
    		if( !aim.data('id') ){
    			fid = $.MC.cache['main_page'];
    		}else{
    			fid = $.MC.cache['page'] + aim.data('id') + '_' + aim.data('name');
    		}
    		form.find('input[name="fid"]').val( fid );
    		form.find('input[name="client_type"]').val( $.MC.search.find('#client_type').val() );
    		form.ajaxSubmit({
                success: function() {
                    load();
                    form.myTip({ 
                		string : '生成成功！',
                        delay : 500,
                        dtop : 0,
                        dleft : 0
                    });
                    self.addClass('disabled').removeClass('genBtn');;
                }
            });
            return false;
    	},
    	_showSel : function( event ){
    		var self = $(event.currentTarget);
    		self.closest('div').find('ul').show();
    	},
    	_choose : function( event ){
    		var self = $(event.currentTarget),
    			parent = self.closest('div'),
    			check = parent.find('input');
    		parent.find('.arrow').text(self.text());
    		check.val( self.attr('_data') == 'yes' ? '1' : '0'  );
    		parent.find('ul').hide();
    	},
    });
    
    //搜索
    $.widget('m2o.search',{
    	options : {},
    	_init : function(){
    		this._on({
    			'submit #searchform' : '_submit',
    		});
    		this.site_id = this.element.find('#site_id');
			this.client_type = this.element.find('#client_type');
			var _this = this;
			$.extend($.MC.cache,{site_id:_this['site_id'].val(), client_type:_this['client_type'].val() });
    	},
    	_submit : function( event ){
    		var self = $(event.currentTarget);
    		event.preventDefault();
    		//if一二列、刷新站点，else 刷新列表
    		var site_id = this.element.find('#site_id').val();
    		if( $.MC.cache['site_id'] != site_id ){
    			var param = '&site_id=' + site_id;
    			$.MC.nav.nav('refresh',site_id);
    			$.MC.cache['site_id'] = site_id;
    			return;
    		}
    		var searchStr = this.getFormInfo(),
    			nodeStr = this.getNode(),
    			result = searchStr + nodeStr;
    		$.MC.list.list('refresh', $('.choice-area'), result);
    		var start_weight = self.find('#start_weight').val(),
    			end_weight = self.find('#end_weight').val();
    		self.find('#display_colonm_show').text( '权重(' + start_weight + '-' + end_weight + ')');
    		return false;
    	},
    	getFormInfo : function(){
    		var form = $(this.element).find('#searchform');
    		var result = '&' + form.serialize();
    		return result;
    	},
    	getNode : function(){
    		var select = $.MC.nav.find('.title.selected'),
    			nodeStr = '';
    		if( select.length ){
    			var nodeId = $.MC.nav.find('.title.selected').closest('li').data('fid');
    			if( nodeId ){
    				nodeStr = '&fid=' + nodeId;
    			}
    		}
    		return nodeStr;
    	},
    });
    
    $.MC = {
    		nav : $('.temp-nav'),
    		generate : $('#generate-box'),
    		listwrap : $('.m2o-list'),
    		list : $('.m2o-each-list'),
    		page : $('.pagelink'),
    		search : $('#search-box'),
    		pageInit : false,
    		cache : {}
    };
    $.MC.list.list({
    	url : './run.php?mid='+gMid+'&a=get_content',
    	tpl : $('#m2o-each-tpl').html()
    });
    $.MC.nav.nav({
    	url : './run.php?mid=' + gMid + '&a=get_node',
    	tpl : $('#nav-item-tpl').html(),
    });
    $.MC.generate.generate({
    	tpl : $('#generate-tpl').html(),
    	url : './run.php?mid=' + gMid
    });
    $('body').search();
    $(document).on({
        'click' : function(){
            $.MC.generate.generate('hide');
        }
    });
    $('#generate-box').click(function( event ){
    	event.stopPropagation();
    });

    $('body').on('click','.m2o-share',function(){
    	$.share({
    		className : 'share-wrap',
    	});
    }).on('click','.m2o-cdn',function(event){
    	var self = $(event.currentTarget),
    		url = self.attr('href');
    	if( !self.hasClass('on') ){
        	$.globalAjax(self,function(){
        		return $.getJSON(url,function( json ){
        			if(json['callback']){
        				eval( json['callback'] );
        				return;
        			}else{
        				var data = json[0];
        				if( data ){
        					self.text( ' 推送成功' ).addClass('on');
        				}
        			}
        		});
        	});
    	}

    	return false;
    });
});