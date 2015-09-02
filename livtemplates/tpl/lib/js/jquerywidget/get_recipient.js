/*
 * author qiaoxuewu
 * date 2014-11-12
 * 依赖ajaxload_new、page.js
 * title : 为组件名称  如'接收人信息'
 * needType :为true表示需要显示管理员信息切换，false则默认显示会员信息
 * infoUrl : 会员接口,
 * manageUrl : 管理员接口,
 * callback : 保存回调
 * 实例化调用方式 $( input ).get_recipient();
 * input 需要绑定data-type 不绑定默认为1 表示为会员   2表示管理员
 * 	     需要绑定data-name , data-id  为已选 实例化组件选中元素
 * type_name : 类别  保存为会员 或者 管理员
 * muti : true为可以多选  false 不可多选 
 * 
 * */
$(function(){
	(function(){
	var widgetInfo = {
	        template : ''+
		        '<div class="recipient-item-box">'+
		        	'<div class="recipient-head">' +
		        		'<p class="title">{{= title}}</p>' + 
		        		'<div class="fright">' +
		        			'<span class="recipient-save">确定</span>'+
		        			'<span class="recipient-cancel"></span>'+
		        		'</div>'+
		        	'</div>' + 
		        	'<div class="recipient-search">'+
		        		'{{if needType}}' +
		        		'<div class="recipient-toggle">'+
		        			'<span {{if type_id ==1}}class="current"{{/if}} _type="1">会员</span>' +
		        			'<span {{if type_id ==2}}class="current"{{/if}} _type="2">管理员</span>' +
		        		'</div>'+
		        		'{{/if}}' +
		        		'<div class="recipient-quick-search-box">' + 
	        				'<input type="text" name="recipient-quick-search" placeholder="请输入搜索条件" />' +
	        				'<span class="recipient-search-btn"></span>'+
	        			'</div>' + 
	        			'<div class="recipient-drop-menu">'+
	        				'<span class="menu-select">全部分组</span>' +
		        			'<ul>'+
			        		'</ul>' +
		        		'</div>'+
		        	'</div>'+
					'<div class="selected">'+
						'<ul class="selected-list">'+
							
						'</ul>'+
					'</div>'+
					'<div class="group-list">'+
						'<ul>'+
							
						'</ul>'+
						'<div class="page"><div class="page_size"></div></div>'+
					'</div>'+
					'<input type="hidden" name="{{= target}}" value="{{= memberId}}"/>'+
					'<input type="hidden" name="{{= type_name}}" class="{{= type_name}}" value="{{= type_id}}"/>'+
					'<input type="hidden" name="group_id" value="0"/>'+
				'</div>'+
	        	'',
	        selected : '<li _id="{{= id}}">'+
							'<span class="selected-name">{{= name}}</span>'+
							'<span class="cancel">x</span>'+
						'</li>'	+
						'',
			infoList : '<li {{if select}}class="select"{{/if}} _id="{{= member_id}}">{{= member_name}}</li>'+
					   '',
			groupList : '<li _id="{{= id}}">{{= name}}</li>' +
						'',
	        css : ''+
		        '.recipient-item-box{width:500px;z-index: 9999999;position: absolute;top:-1000px;transition: all 0.5s;background: #fff;border-bottom: 5px solid #6ea5e8;border-left: 5px solid #6ea5e8;border-right: 5px solid #6ea5e8;border-radius: 2px;}'+
		        '.recipient-item-box .recipient-toggle{float: left;margin-left: 5px;width: 90px;height: 25px;border: 1px solid #ddd;border-radius: 15px;line-height: 25px;display: -webkit-box;}'+
		        '.recipient-item-box .recipient-toggle span{display: block;text-align: center;height: 20px;border-radius: 15px;line-height: 20px;font-size: 12px;transition: all 0.5s;cursor:pointer;}'+
		        '.recipient-item-box .recipient-toggle span:first-child{width: 40px;margin: 2px 0px 0px 4px;}'+
		        '.recipient-item-box .recipient-toggle span:last-child{width: 45px;margin: 2px 4px 0px -2px;}'+
		        '.recipient-item-box .recipient-toggle span.current{background:#6ea5e8;color:#fff;}'+
		        '.recipient-item-box .recipient-search{border-bottom: 1px dotted #ddd;padding: 5px;height: 27px;}'+
		        '.recipient-item-box .recipient-search .recipient-drop-menu{float:right;position:relative;}'+
	        	'.recipient-item-box .recipient-search .menu-select{float: right;width: 80px;height: 25px;border: 1px solid #ddd;border-radius: 3px;text-overflow: ellipsis;white-space: nowrap;overflow: hidden;line-height: 26px;padding-left: 5px;}'+
	        	'.recipient-item-box .recipient-drop-menu:hover ul{display:block;}'+
	        	'.recipient-item-box .recipient-drop-menu ul{width: 85px;border: 1px solid #ddd;position: absolute;top: 26px;left: 0px;background: #fff;border-radius: 3px;display:none;max-height: 207px;overflow-y: scroll;}'+
	        	'.recipient-item-box .recipient-drop-menu li{height:25px;line-height:25px;padding-left:5px;font-size:12px;cursor:pointer;}'+
	        	'.recipient-item-box .recipient-drop-menu li:not(:last-child){border-bottom:1px solid #ddd}'+
	        	'.recipient-item-box .recipient-drop-menu li:hover{background:#ddd}'+
	        	'.recipient-item-box .recipient-quick-search-box{position:relative;}'+
	        	'.recipient-item-box .recipient-search input{float: right;height: 21px!important;border-radius: 3px;font-size: 12px;margin-left: 5px;}'+
	        	'.recipient-item-box .recipient-search .recipient-search-btn{display:block;width:21px;height:21px;background:url('+ RESOURCE_URL +'/menu2013/search.png) no-repeat center center #fff;position: absolute;right: 2px;top: 2px;cursor:pointer;}'+
	        	'.recipient-item-box .recipient-head{height:50px;background:#6ea5e8}'+
		        '.recipient-item-box .recipient-head .title{display:block;height:50px;line-height:50px;font-size:20px;color:#fff;padding: 0px 10px;}'+
		        '.recipient-item-box .recipient-head .fright{float:right;margin-top: -37px;display: -webkit-box;}'+
		        '.recipient-item-box .recipient-head .recipient-save{display: block;font-size: 14px;background: -webkit-linear-gradient(#f3f3f3,#dedede);width: 55px;height: 25px;line-height: 25px; text-align: center;color: #686868;border-radius: 2px;cursor: pointer;}'+
		        '.recipient-item-box .recipient-head .recipient-cancel{display: block;width: 25px;height: 25px;background:url('+ RESOURCE_URL +'/datasource/close4.png) no-repeat center,-webkit-linear-gradient(#f3f3f3,#dedede);line-height: 27px;text-align: center;border-radius: 2px;color: #686868;font-size: 16px;cursor: pointer;margin: 0px 5px 0px 10px;}'+
		        '.recipient-item-box ul{overflow:hidden;}'+
				'.recipient-item-box .selected{min-height:30px;padding: 10px 10px 5px 10px;background:#fff;border-radius:2px;margin-bottom:2px;border-bottom: 1px dotted #ddd;}'+
				'.recipient-item-box .selected li{float:left;width:88px;height:30px;background:#eee;position: relative;margin: 0px 10px 6px 0px;border-radius: 2px;}'+
				'.recipient-item-box .selected li:nth-of-type(5n){margin-right:0px!important;}'+
				'.recipient-item-box .selected .selected-name{display:block;width:100%;height:100%;line-height:30px;text-align:center;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;}'+
				'.recipient-item-box .selected .cancel{display:none;width:10px;height:10px;color:#000;text-align:center;line-height:10px;position: absolute;top: 2px;right: 2px;cursor:pointer;}'+
				'.recipient-item-box .selected li:hover .cancel{display:block;}'+
				'.recipient-item-box .group-list{clear: both;height:200px;background:#fff;border-radius:2px;padding:10px 10px 0px 10px;}'+
				'.recipient-item-box .group-list ul{height:160px}' +
				'.recipient-item-box .group-list li{float:left;width:88px;height:30px;background:#eee;margin:0px 10px 10px 0px;text-align: center;line-height: 30px;border-radius: 2px;text-overflow: ellipsis;overflow: hidden;white-space: nowrap;cursor:pointer;}'+
				'.recipient-item-box .group-list li:nth-of-type(5n){margin-right:0px!important;}'+
				'.recipient-item-box .group-list li.select{background:#6ea5e8;color:#fff;}'+
				'.recipient-item-box .page{height:30px;}'+
				'.recipient-item-box .page .numbers-box{display:none;}'+
				'.recipient-item-box .page .hoge_page{margin:0!important;height:auto!important;}'+
				'.recipient-item-box .page-code a , .page_next a , .page_cur{border-radius:2px;}'+
	        	'',
	        cssInited : false
	 };
	
	 $.widget('recipient.get_recipient', {
	        options : {
	        	target : 'recipient',
	        	needType : true,									/*为true表示需要显示管理员信息切换，false则默认显示会员信息*/
	        	title : '',											/*组件标题 可自行传入*/
	        	infoUrl : 'run.php?a=getMemberInfo&method=show',	/*会员信息接口*/
	        	manageUrl : 'run.php?a=getAuthInfo&method=show',	/*管理员信息接口*/
	        	type_name : 'owner_utype',							/*类别  保存为会员 或者 管理员*/
	        	muti : true ,										/*多选  默认true*/
	        	callback : function( target , info ){								/*保存时的回调 默认null*/ 
	        		
	        	}									    
	        },

	        _create : function(){
	        	
	        },

	        _init : function(){
	        	this.initTmpl();
	            this._on({
	                'focus #recipient' : 'tmplShow',
	                'click .recipient-save' : '_saveRecipient',
	                'click .recipient-cancel' : 'tmplHide',
	                'click .group-list li' : '_chooseRecipient',
	                'click .recipient-item-box .selected .cancel' : '_cancelRecipient',
	                'click .recipient-drop-menu li' : '_groupSelect',
	                'click .recipient-search-btn' : '_quickSearch',
	                'click .recipient-toggle span' : '_toggleType'
	            });
	        },
	        
	        initTmpl : function(){
        		var target = $('#' + this.options.target );
	        	var selectRecipient = target.data('name'),
	        		memberid= target.data('id'),
	        		type = target.data('type') ? target.data('type') : 1,
	        		url = '';
	        	this.options['memberName'] = selectRecipient;
	        	this.options['memberId'] = memberid;
	        	this.options['type_id'] = type;
	        	this.wrap = this._template('get_recipient', widgetInfo, target, this.options);
	        	this.getSelected();								/*获取已选*/
	        	if( type == 1 ){
	        		url = this.options.infoUrl;
	        	}else{
	        		url = this.options.manageUrl
	        	}
	        	this.getInfo( null ,null , url );
	        },
	        
	        getSelected : function(){
	        	var target = $('#' + this.options.target );
	        	var selectRecipient = target.data('name'),
        			memberid= target.data('id');
	        	if(selectRecipient && memberid){
	        		this.r_name = (selectRecipient + ',').split(','),
	        		this.r_id = ( memberid + ',' ).split(',');
		        	var _this = this;
	        			box = this.wrap.find('.selected-list').empty();
	        		var selected = [],
	        			selectedList = {};
	        		$.each( this.r_name , function( key , value ){
	        			if( value ){
	        				selectedList.id = _this.r_id[key ];
	        				selectedList.name = value;
	        				selected.push( selectedList );
	        			}
		        	});
	        		$.tmpl( widgetInfo.selected , selected ).appendTo(box);
	        	}
	        },
	        
	        getInfo : function(param , page ,url ){
	        	var _this = this,
	        		val = $.trim( this.wrap.find('input[name="recipient-quick-search"]').val() ),
	        		group_id = this.wrap.find('input[name="group_id"]').val();
	        	var data = param || {};
	        	data.group_id = group_id;
	        	data.member_name = val;
	             if (page) {
	                 data.page = page
	             } else {
	                 data.page = 1
	             }
	        	this.ajax( this.wrap , url , data , function( data ){
	        		_this.getInfolist( data.info );
	        		_this.getInfopage( data.page_info);
	        		_this.getGrouplist( data.group_info );
	        	});
	        },
	        
	        getInfolist : function( data ){
	        	var box = this.wrap.find('.group-list ul').empty(),
	        		_this = this;
	        	$.each( data ,function( key , value ){
	        		if( _this.r_id ){
	        			$.each( _this.r_id , function( k ,v ){
	        				if( v ){
	        					if(v == value.member_id){
	        						value.select = true;
				        		}else{
				        			value.select = false;
				        		}
	        				}
		        		})
	        		}
	        	})
	        	$.tmpl( widgetInfo.infoList , data ).appendTo(box);
	        },
	        
	        getGrouplist : function( data ){								/*获取分组*/
	        	data.push( {id : 0 , name : '全部分组' } );
	        	var box = this.wrap.find('.recipient-drop-menu ul');
	        	var _this = this;
	        	$.tmpl( widgetInfo.groupList , data.reverse() ).appendTo(box);   /*reverse()点到数组数据顺序*/
	        	this.options['selectGroup'] = function( event ){			/*分组列表绑定事件*/
	        		var self = $( event.currentTarget ),
	        			txt = self.text(),
	        			id = self.attr('_id');
	        		_this.wrap.find('.menu-select').text( txt );
	        		_this.wrap.find('input[name="group_id"]').val( id );  /*暂时存放  以防搜索重新实例化掉已选group_id*/
	        		var member_name = _this.wrap.find('input[name="recipient-quick-search"]').val();
	        		var data = {};
	        		data.group_id = id;
	        		data.member_name = $.trim( member_name );
	        		var type = _this.wrap.find('input[name="'+ _this.options.type_name +'"]').val()
	        		if( type == 1 ){
		        		url = _this.options.infoUrl;
		        	}else{
		        		url = _this.options.manageUrl
		        	}
	        		_this.getInfo( data , 1 , url);
	        	}
	        },
	        
	        getInfopage : function( option ){								/*分页*/
	        	var page_box = this.wrap.find('.page_size'),
	                _this = this;
	            option.show_all = true;
	            if (page_box.data('init')) {
	                page_box.page('refresh', option);
	            } else {
	                option['page'] = function (event, page, page_num) {
	                    _this._refresh(null, page);
	                }
	                page_box.page(option);
	                this.page_num = option.page_num;
	                page_box.data('init', true);
	            }
	        },

            _refresh: function (data, page) {
            	var type = this.wrap.find('.' + this.options.type_name).val();
            	if( type == 1 ){
	        		url = this.options.infoUrl;
	        	}else{
	        		url = this.options.manageUrl
	        	}
                this.getInfo(data, page ,url );
            },
	        
	        _template : function(tname, info, container, datas){
	            tname = tname || this.options.templateName;
	            info = info || this.options.pluginInfo;
	            container = container || this.element;
	            datas = datas || {};
	            $.template(tname, info.template);
	            var dom = $.tmpl(tname, datas).insertAfter(container);
	            this.initCss();
	            return dom;
	        },
	        
	        initCss : function(){
	        	if( !widgetInfo.cssInited && widgetInfo.css ){
	        		widgetInfo.cssInited = true;
	        		this.addCss( widgetInfo.css );
	        	}
	        },
	        
	        addCss : function(css){
	            $('<style/>').attr('style', 'text/css').appendTo('head').html(css);
	        },
	        
	        tmplShow : function(){
	        	var target = $('#' + this.options.target );
	        	var offset = target.offset(),
	        		top = offset.top,
	        		left = offset.left,
	        		width = target.width(),
	        		height = target.height(),
	        		Theight = this.wrap.height();
	        	this.wrap.css({'top' : top -Theight + 100 , 'left' : left+100 });
	        },
	        
	        tmplHide : function(){
	        	this.wrap.css('top' , '-1000px');
	        },
	        
	        _groupSelect : function( event ){								/*分组查询*/
	        	this._trigger('selectGroup', event );
	        },
	        
	        _quickSearch : function( event ){								/*快速搜索*/
	        	var self = $( event.currentTarget ),
	        		val = $.trim( this.wrap.find('input[name="recipient-quick-search"]').val() ),
	        		type = 
	        		group_id = this.wrap.find('input[name="group_id"]').val();
	        	var data = {};
	        	data.group_id = group_id;
	        	data.member_name = val;
	        	var target = this.wrap.find('input[name="'+ this.options.type_name +'"]'),
	        		type = target.val();
        		if( type == 1 ){
	        		url = this.options.infoUrl;
	        	}else{
	        		url = this.options.manageUrl
	        	}
        		this.getInfo( data , 1 , url);
	        },
	        
	        _toggleType : function( event ){								/*会员、管理员切换*/
	        	var self = $( event.currentTarget ),
	        		type = self.attr('_type'),
	        		hasCurrent = self.hasClass('current');
	        	self.addClass('current').siblings().removeClass('current');
	        	if( !hasCurrent ){
	        	 	this.wrap.find('.menu-select').text('全部分组');
	        	 	this.wrap.find('input[name="recipient-quick-search"]').val('');
		        	this.wrap.find('input[name="group_id"]').val(0);
	        		this.wrap.find('.selected-list').empty();
	        		this.wrap.find('input[name="'+ this.options.type_name +'"]').val( type );
		        	if( type == 1){
		        		this.getInfo( null , 1 ,this.options.infoUrl );
		        	}else{
		        		this.getInfo( null , 1 ,this.options.manageUrl );
		        	}
		        	if( type == this.options['type_id'] ){
		        		this.getSelected();
		        	}
	        	}
	        },
	        
	        _chooseRecipient : function( event ){
	        	var self = $(event.currentTarget ),
	        		name = self.text(),
	        		id = self.attr('_id');
	        	var box = $('.selected ul');
	        	if( this.options['muti'] ){ 					/*支持多选*/
	        		if( self.hasClass('select')){
	        			self.removeClass('select');
	        			this.wrap.find('.selected li[_id="'+ id +'"]').remove();
		        	}else{
		        		self.addClass('select');
		        		$.tmpl( widgetInfo.selected , {id : id , name : name} ).appendTo( box );
		        	}
	        	}else{
	        		if( self.hasClass('select')){
	        			self.removeClass('select');
	        			this.wrap.find('.selected li[_id="'+ id +'"]').remove();
	        		}else{
	        			self.addClass('select');
	        			$.tmpl( widgetInfo.selected , {id : id , name : name} ).appendTo( box.empty() );
	        		}
	        	}
	        },
	        
	        _showSelected : function(){
	        	this.ids = this.wrap.find('.selected li').map(function(){
	        		return $(this).attr('_id');
	        	}).get();
	        	this.names = this.wrap.find('.selected li').map(function(){
	        		return $(this).find('.selected-name').text();
	        	}).get();
	        },
	        
	        _cancelRecipient : function( event ){
	        	var self = $( event.currentTarget ),
	        		parent = self.closest('li'),
	        		id = parent.attr('_id');
	        	parent.remove();
	        	this.wrap.find('.group-list .select[_id="'+ id +'"]').removeClass('select');
	        },
	        
	        _saveRecipient : function( event ){
	        	var target = this.options.target;
	        	this._showSelected();
	        	var info={};
	        	info.ids = this.ids.join(',');
	        	info.names = this.names.join(',');
	        	if( this.ids ){
			        $('input[name="'+ target +'"]').val( this.ids.join(',') );
	        	}
		        this._trigger('callback' , target , [info] );
		        this.tmplHide();
	        },
	        
	       ajax : function( item , url , param , callback){
	    		$.globalAjax( item, function(){
	    			return $.getJSON( url , param , function( data ){
	    				if( $.isFunction( callback ) ){
	    					callback( data );
	    				}	
	    		    });
	    		});
	    	},
	    });
	})($);
});