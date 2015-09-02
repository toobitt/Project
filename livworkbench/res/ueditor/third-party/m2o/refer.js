(function($){
	var referInfo = {
		template : '' +
				'<div class="refer-slide">' +
					'<div id="edit-slide-each1" class="edit-slide-each">' +
						'<div class="edit-slide-title">引用素材</div>' +
						'<span class="editor-slide-no"></span>'+
						'<div class="edit-slide-refer-content edit-slide-content" data-sortlevel="0">' +
							'<div class="refer-item refer-my_publisth edit-slide-next refer-with-icon">' +
								'<span>我发布的</span>' +
								'<a class="refer-item-button">&gt;</a>' + 
							'</div>' +
						'</div>' +
					'</div>' +
				'</div>' +
				'',
		
		item_tpl : '' + 
				'<div class="refer-item refer-${bundle} edit-slide-next {{if !level}}refer-with-icon{{/if}}" data-islast="${islast}" data-host="${host}" data-dir="${dir}" data-filename="${filename}" data-fid="${fid}" data-sort_id="${sort_id}" >' + 
					'<span>${name}</span>' +
					'<a class="refer-item-button">&gt;</a>' +
				'</div>' +
				'',
		item_content : '' +
				'<div class="edit-slide-each">' +
					'<div class="edit-slide-title">${tit}</div>' +
					'<span class="slide-back">返回</span>' +
					'<span class="editor-slide-no"></span>'+
					'<div class="edit-slide-refer-content edit-slide-content" data-sortlevel="${sortlevel}">' +
						'{{tmpl($data["columnlist"]) "item_tpl"}}' +
					'</div>' +
				'</div>' +
				'' ,
		item_data : '' +
			'<div class="refer-item refer-material-item">' +
				'<div class="wrap-img" data-host="${host}" data-dir="${dir}" data-filename="${filename}" data-id="${id}">' +
					'<img src="${src}" alt="${alt}" title="${title}" />' +
				'</div>' + 
				'<div class="refer-label">' +
					'${cont}' +
					'<p>${update_time}</p>' +
				'</div>' + 
			'</div>' + 
			'',
		item_nodata : '' +
			'<h3 class="nodata">没有此类素材！</h3>' +
			'',
		css : '' + 
			'.refer-slide{position:relative; width:11000px; top:-44px;}' +
			'.edit-slide-each{width:245px; margin:0 10px; float:left; position:relative;}' +
			'.ump-box .edit-slide-each{width:235px}'+
			'.edit-slide-title{height:43px; line-height:43px;text-align:center;}' +
			'.slide-back{position:absolute;left:0; top:10px; z-index:99; cursor:pointer; width:22px; height:22px;color:transparent;background: url(./res/ueditor/third-party/m2o/images/slide/slide-back.png) no-repeat center; }' +
			'.edit-slide-refer-content{overflow-y:auto;}' +
			'.refer-item{padding:10px; border-bottom:1px solid #e7e7e7; position:relative; cursor:pointer; overflow:hidden;}' +
			'.refer-item-button{width:8px; height:10px; position:absolute;background-repeat:no-repeat;right:10px; top:16px; text-indent:-999px; background-image:url(./res/ueditor/third-party/m2o/images/slide/slide-next.png); overflow:hidden}' +
			'.refer-with-icon span{width:140px; height:22px; display:block; line-height:22px; padding-left:30px;}' +
			'.refer-my_publisth span{background: url(./res/ueditor/third-party/m2o/images/slide/nav-user-h.png) no-repeat left center;}' +
			'.refer-my_publisth:hover span{background-image: url(./res/ueditor/third-party/m2o/images/slide/nav-user.png);}' +
			'.refer-with-icon.refer-tuji span{background: url(./res/ueditor/third-party/m2o/images/slide/tw-tjk-h.png) no-repeat left center;}' +
			'.refer-with-icon.refer-tuji:hover span{background-image: url(./res/ueditor/third-party/m2o/images/slide/tw-tjk.png);}' +
			'.refer-with-icon.refer-vote span{background: url(./res/ueditor/third-party/m2o/images/slide/hd-tp-h.png) no-repeat left center;}' +
			'.refer-with-icon.refer-vote:hover span{background-image: url(./res/ueditor/third-party/m2o/images/slide/hd-tp.png);}' +
			'.refer-with-icon.refer-livmedia span{background: url(./res/ueditor/third-party/m2o/images/slide/mt-zbt-h.png) no-repeat left center;}' +
			'.refer-with-icon.refer-livmedia:hover span{background-image: url(./res/ueditor/third-party/m2o/images/slide/mt-zbt.png);}' +
			'.wrap-img{width:72px; height:54px; line-height:54px; float:left; border:1px solid #E7E7E7; text-align:center; margin-right:10px; cursor:pointer;}' + 
			'.wrap-img img{max-height:54px; max-width:72px; vertical-align:middle; }' +
			'.refer-label{ display: table-cell; height: 54px; vertical-align: middle; max-width: 115px; word-wrap: break-word;}' +
			'.refer-label p{color:#9f9f9f; font-size:0.7em; }' +
			'.nodata{color:red; font-size:16px; padding:10px; text-align:center;}' +
			'.refer-material-search{border-bottom: 1px solid #e7e7e7; line-height: 40px;padding-left: 10px;}' +
			'.refer-event-submit{margin-left:10px; cursor:pointer; }' +
			'.page-control{float:right; margin:15px 20px;}' +
			'.page-control a{margin:10px; cursor:pointer; }' +
			'.ump-inner .edit-slide-title{background:#fff}' +
			'.editor-slide-inner .edit-slide-title{background:#f9f9f9}' +
			'',
		cssInited : false
	};
	$.widget('ueditor.refer', $.ueditor.baseWidget, {
		options : {
			index : true,
			title : '引用素材',
			slide : '.refer-slide',
			eachfirst : '#edit-slide-each1',
			each : '.edit-slide-each',
			content : '.edit-slide-content',
			mypublish : 'refer-my_publisth',
			eventsubmit : 'refer-event-submit',
			icon : 'refer-with-icon',
			next : '.edit-slide-next',
			tit : '.edit-slide-title',
			back : '.slide-back',
			mulpage : '.page-control a',
			wrap : '.refer-material-item',
		},
		_create : function(){
			this.editor = this.options.editor;
			this._super();
            this._template('attach-template',referInfo, this.body);
            this.sortlevel = 0;
			this.content = '';
			this.tit = '';
            this.nextInfo = {
				host: '',
				dir: '',
				filename: '',
				fid: 0,
				sort_id: 0,
				isLast: 0
			};
			this.typeList = {
				VOD: 'vod',
				TUJI: 'tuji',
				VOTE_QUESTION: 'vote_question',
				VOTE: 'vote'
			};
			this.page = {
				total: 0,
				needNext: true,
				nowCount: 0,
				step: 7,
				cache: null,
				hasNext: function() {
					return this.needNext;
				}
			};
			this.searchHtml = '<div class="refer-material-search"><label>搜索：<input /></label><a class="refer-event-submit edit-slide-next">确定</a></div>';
			this.waitingImg = '<img class="waiting-img" src="' + RESOURCE_URL + 'loading2.gif"/>';
		},
		_init : function(){
			var op = this.options,
				handlers = {};
			this.box = this.element.find( op['slide'] );
			handlers['click ' + op['next'] ] = '_showNext';	
			handlers['click ' + op['back'] ] ='_back';
			handlers['click ' + op['mulpage'] ] ='_switchPage';
			handlers['click ' + op['wrap'] ] ='insertRefer';
			this._on(handlers);
			this._initWater();
			this._super();
		},
		
		/*增加内容开始*/
		_showNext : function( event ){
			var op = this.options,
				self = $(event.currentTarget);
			//新增一个全局变量，记录该target所属的第一级菜单名
			if( self.closest('.edit-slide-content').data('sortlevel') == 0 ){
				this.belongTopSortName = self.find('span').text();
			}
			this.addAjaxB( self );
		},
		
		/*提交ajax之前*/
		addAjaxB : function( dataDom ){
			var next = null;
			var op = this.options,
				_this = this;
			this.page.total = this.page.nowCount = 0;
			this.page.cache =null;
			if ( dataDom.hasClass( op['mypublish'] ) ) {
				this.content = '我发布的';
				_this.requestAjax( dataDom );
				this.nextInfo.islast = 0;
				this.nextInfo.fid = 0;
				this.search = false;
			}else if(dataDom.hasClass( op['eventsubmit'] )){
				if( !dataDom.parent().find('input').val().trim() ){
					dataDom.myTip({
						string : '请输入要搜索的内容'
					});
					return;
				}
				this.content = '搜索结果';
				this.nextInfo.isLast = 1;
				this.nextInfo.key = dataDom.parent().find('input').val().trim();
				this.nextInfo.search_type = dataDom.data('search_type');
				_this.requestAjaxForSearch( dataDom );
				this.search = true;
			}else{
				this.nextInfo.isLast = dataDom.data( 'islast' );
				this.nextInfo.host = dataDom.data( 'host' );
				this.nextInfo.dir = dataDom.data( 'dir' );
				this.nextInfo.filename = dataDom.data( 'filename' );
				this.nextInfo.fid = dataDom.data( 'fid' );
				this.nextInfo.sort_id = dataDom.data( 'sort_id' );
				this.content = ( this.content == '我发布的' && this.sortlevel != 1  ? this.content : dataDom.find( 'span' ).text() );
				_this.requestAjax( dataDom );
				this.search = false;
			}
		},
		
		requestAjaxForSearch : function( dataDom ){
			this.sortlevel++;
			var _this = this,
				sortlevel = this.sortlevel,
				text = this.nextInfo.key,
				host = this.nextInfo.host,
				filename = this.nextInfo.filename,
				dir = this.nextInfo.dir;
			var url = this.options.config['materialUrl'],
				param = {
						host: host,
						dir: dir,
						filename: filename,
						key: text
					};
			$.globalAjax(dataDom, function(){
				return $.getJSON( url, param, function(data) {
					data = data || [];
					if ( sortlevel == _this.sortlevel ) { //如果ajax后,当前内容级别切换了,则什么都不做
						data = data.filter(function(d) { return d != null; });
						data.push('mix');
						data.push('isLast');
						_this.addAjaxA( data );
						_this.setreferSlide( dataDom );
					}
				});
			});
		},
		
		requestAjax : function( dataDom ){
			this.sortlevel++;
			var url = this.options.config['materialUrl'] + '&host=' + this.nextInfo.host + 
				'&dir=' + this.nextInfo.dir + '&filename=' + this.nextInfo.filename + '&fid=' + this.nextInfo.fid,
				isLast = false,
				materialType,
				op = this.options,
				_this = this,
				self = dataDom,
				sortlevel = this.sortlevel;
			var goal = self.closest( op['content'] ).data('sortlevel');
			if( this.content == '我发布的' ) {
				if(goal == 0){
					this.nextInfo.isLast = 0;
				}
				if ( this.nextInfo.isLast ) {
					url += '&my_publisth=1';
				} else {
					if (this.sortlevel == '1') {
						url = this.options.config['materialUrl'];
					}
				}
			}
			if ( this.nextInfo.isLast == 0 ) {
				this.isLast = false;
				isLast = false;
			} else {
				this.isLast = true;
				url += '&sort_id=' + this.nextInfo.sort_id + '&offset=' + this.page.nowCount + '&counts=' + (this.page.step + 1); 
				isLast = true;
				materialType = this.nextInfo.filename; 
			}
			if( this.belongTopSortName == '我发布的' ){
				url += '&my_publisth=1'
			}
			$.globalAjax( dataDom, function(){
				return $.getJSON(url, function( data ){
					var data = data || [];
					if(isLast){
						data.push( materialType );
						data.push( 'isLast' );
					}else{
						data.push( 'notLast' );
					}
					if ( sortlevel == _this.sortlevel ) { //如果ajax后,当前内容级别切换了,则什么都不做
						_this.addAjaxA( data );
						_this.setreferSlide( dataDom );
					}
				});
			});
			
//			$.ajax({
//				url: url,
//				type: 'post',
//				processData: false,
//				contentType: false,
//				dataType: 'json',
//				success: function( data ){
//					var data = data || [];
//					if(isLast){
//						data.push( materialType );
//						data.push( 'isLast' );
//					}else{
//						data.push( 'notLast' );
//					}
//					if ( sortlevel == _this.sortlevel ) { //如果ajax后,当前内容级别切换了,则什么都不做
//						_this.addAjaxA( data );
//						_this.setreferSlide( dataDom );
//					}
//				}
//			});	
		},
		/*提交ajax之后*/
		addAjaxA : function( json ){
			var len = json.length,
				sort = json.pop();
			if(len > 2){
				if( sort === 'isLast' ) {
					this.addMaterialList( json );
				} else {
					this.addSortList( json );
				}
			}else{
				this.showEmpty();
			}
		},
		
		addMaterialList : function( json ){
			var data = json,
				_this = this,
				op = this.options,
				sortlevel = this.sortlevel,
				num = 0, 
				total,
				type = data.pop();
			var realdata = [],
				info = {};
			total = data.length;
			if (total < _this.page.step + 1) {
				_this.page.needNext = false;
				_this.page.cache = null;
			} else {
				_this.page.needNext = true;
				_this.page.cache = type;
			}
			_this.page.nowCount += total;
			if( $.isArray( data ) ){
        		$.each( data, function( key, value ){
        			_this.preloadImg(value, realdata);
        			if ( ++num >= total ) {
						showmulPage = _this.showPage();
					}
        		} );
        	}else{
        		_this.preloadImg(value, realdata);
        	}
        	info.columnlist = realdata;
			info.sortlevel = this.sortlevel;
			info.tit = this.content;
			$.template('item_tpl',referInfo.item_data);
			$.template('item_content',referInfo.item_content);
        	var dom = $.tmpl('item_content', info).appendTo( op['slide'] );
        	$( '.edit-slide-content:last' ).after( showmulPage );
        	$(showmulPage).find('a').each(function(){
	    	   if($(this).text() == '上一页'){
	    			$( '.edit-slide-each:last' ).find( op['back'] ).hide();
	    		}
        	});
        	$(window).trigger('resize.slide');

        	var height = this.element.height() - dom.find('.edit-slide-title').height();
        	if( dom.find('.page-control').length ){
        		height -= dom.find('.page-control').outerHeight(true);
        	}
        	dom.find('.edit-slide-refer-content').css({
        		'max-height' : height + 'px'
        	});
		},
		
		showPage : function(){
			var html = '<div class="page-control">';
			if( this.page.nowCount > (this.page.step+1) ) { //需要上一页
				html += '<a class="prev">上一页</a>';	
			}
			if (this.page.hasNext() == true) {
				html += '<a>下一页</a>';
			} 
			if( html != '<div class="page-control">' ) {
				html += '</div>';
			}
			return html;
		},
		
		preloadImg : function(value, arr){
			var info = {},
				title = '';
			src =  $.globalImgUrl(value.img);
			img = value.img;
			bundle = value.app_bundle;
			info.id = value.id;
			info.host = value.host;
			info.dir = value.dir;
			info.filename = value.filename;
			info.update_time = value.update_time;
			info.cont = value.title;
			title = this.Settitle( bundle);
			if(!img.filename){
				info.alt = '无索引图';
				info.src = './res/ueditor/third-party/m2o/images/big_default.png';
				info.title = title;
			}else{
				info.alt = "一张素材示意图";
				info.src = src;
				info.title = title;
			}
			arr.push(info);
		},
		
		Settitle : function( str ){
			switch(str){
				case this.typeList.VOD:{
					return '点击插入此视频';
				}
				case this.typeList.TUJI:{
					return '点击插入此图集';
				}
				case this.typeList.VOTE_QUESTION:{
					return '点击插入此投票';
				}
				case this.typeList.VOTE:{
					return '点击插入此问卷';
				}
			}
		},
		
		addSortList : function( json ){
			var _this = this;
			var op = this.options;
			var realdata = [],
				info = {};
			if( $.isArray( json )){
				$.each(json,function(key, value){
					_this._handleData(value, realdata);
				});
			}else{
				_this._handleData(value, realdata);
			}
			info.columnlist = realdata;
			info.sortlevel = this.sortlevel;
			info.tit = this.content;
			$.template('item_tpl',referInfo.item_tpl);
			$.template('item_content',referInfo.item_content);
        	var dom = $.tmpl('item_content', info).appendTo( op['slide'] );
        	var height = this.element.height() - dom.find('.edit-slide-title').height()-10;
        	dom.find('.edit-slide-refer-content').height( height );
//        	if(this.content == '我发布的'){				//因为点击搜索之后，this.content会改变，这里的判断就无法执行了
        	if(this.belongTopSortName == '我发布的'){		
        		if(this.sortlevel == '2'){
        		 	$( '.edit-slide-content:last' ).prepend( _this.searchHtml );
        		}
        	 }else {
        	 	if(this.sortlevel == '1'){
        	 		 $( '.edit-slide-content:last' ).prepend( _this.searchHtml );
        	 	}
        	 }
        	$(window).trigger('resize.slide');
		},
		
		setreferSlide : function( dataDom ){
			var op = this.options,
				self = dataDom,
				item = self.closest( op['each'] );
			this.box.animate({
				left: '-=' + (item.width()+20) + 'px'
			}, 200);
		},
		/*增加内容结束*/
		
		_switchPage : function( event ){
			var self = $(event.currentTarget);
			var nORp = self.text();
    		if( nORp == '上一页' ) {
    			this.showprevtPage( event );
    		} else {
    			this.showNextPage( self );
    		}
		},
		
		showprevtPage : function( event ){
			var op = this.options,
				self = $(event.currentTarget);
			var total= self.closest( op['each'] ).find( '.refer-item' ).length;
			this.page.nowCount -= total;
			this._back(event);
		},
		
		showNextPage : function( self ){
			var op = this.option;
			this.requestAjax( self );
			self.closest( op['each'] ).find( op['back'] ).hide();
		},
		
		_back : function( event ){
			var op = this.options,
				self = $(event.currentTarget);
			this.sortlevel--;
			var item = self.closest( op['each'] );
			this.box.animate({
				left : '+=' + (item.width()+20) + 'px'
			},200,function(){
				item.remove();
			});
		},
		
		_click : function( event ){
        	var self = $(event.currentTarget),
        		id = self.attr('_id');
        	var data = this.imgCollection[id];
        	this.insertImg('img', data);
        },
		
		insertRefer : function( event ){
			var op = this.options,
				_this = this,
				item = $(event.currentTarget),
				self = item.find('.wrap-img');
			var url = this.options.config['referUrl'],
				param = {
					host : self.data('host'),
					dir : self.data('dir'),
					filename : self.data('filename'),
					id : self.data('id')
			};
			$.globalAjax(self, function(){
				return $.getJSON( url, param, function(data) {
					if ( $.type(data) != 'array' || !data[0] ) {
						self.myTip({
							string : '插入失败'
						});
						return;
					}
					var src = data[0];
					var imgHtml = '<p style="text-align:center;"><img class="image-refer" src= "' + src + '" /></p>'; 
					_this.editor.execCommand('insertHtml', imgHtml);
				});
			});
		},
		
		showEmpty: function() {
			var info = {},
				op = this.options;
			info.tit = this.content;
			$.template('item_tpl',referInfo.item_nodata);
			$.template('item_content',referInfo.item_content);
        	$.tmpl('item_content', info).appendTo( op['slide'] );
			$(window).trigger('resize.slide');
		},
		
		/*初始化最初分类开始*/
		_initWater : function(){
        	var _this = this,
        		url = this.options.config['materialUrl'];
        	setTimeout(function(){
        		$.globalAjax(_this.element, function(){
        			return $.getJSON( url , function( data ){
                		if( data.length ){
                			_this._instance( data );
                		}
                	});
        		});
        	},300);
        },
        _instance : function( data ){
        	var _this = this,
        		op = this.options,
        		prebox = $( op['eachfirst'] ).find( op['content'] ),
    			realdata = [];
        	if( $.isArray( data ) ){
        		$.each( data, function( key , value ){
        			_this._handleData(value, realdata);
        		} );
        	}else{
        		_this._handleData(data, realdata);
        	}
        	$.template('item_tpl',referInfo.item_tpl);
        	$.tmpl('item_tpl', realdata).appendTo(prebox);
        },
         _handleData : function( data , arr ){
        	var info = {};
        	info.bundle = data.bundle ? data.bundle : data.filename;
			info.name = data.name;
			info.level = this.sortlevel;
			info.islast = data.is_last;
			info.host = data.host;
			info.filename = data.filename;
			info.fid = data.fid;
			info.dir = data.dir;
			info.sort_id = data.sort_id;
			arr.push( info );
        },
        /*初始化最初分类结束*/
	});
	
	$.ueditor.m2oPlugins.add({
        cmd : 'refer',
        title : '引用素材',
        click : function(editor){
            $.editorPlugin.get(editor, 'refer').refer('show');
        }
    });
})(jQuery);