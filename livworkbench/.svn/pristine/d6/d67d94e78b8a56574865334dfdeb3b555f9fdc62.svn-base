function ReferDom( box, outer ) {
	this.box = box;	//要操作的dom都是这个box的后代元素,并且这个box是jQuery对象
	this.outer = outer; //包含这个类的外部类,通过它,Dom对象可以访问外部类的成员
	this.content = null;
	this.title = '';
	this.sortlevel = 0;
	this.referSize = '/72x54/';
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
	this.waitingImg = '<img class="waiting-img" src="' + RESOURCE_URL + 'loading2.gif"/>';
	this.smallWImg = '<div class="small-waiting-img-box">' +
		'<img width="40" src="' + RESOURCE_URL + 'loading2.gif"/>' + 
		'</div>';
	this.searchHtml = '<div class="refer-material-search"><label>搜索：<input /></label><a class="refer-event-submit edit-slide-next">确定</a></div>';
}
jQuery.extend( ReferDom.prototype, {
	switchContent: function() {
		this.sortlevel--;
		this.content = this.box.find( '.edit-slide-refer-content[data-sortlevel=' + this.sortlevel + ']' ); 
	},
	addContent: function( dataDom ) {
		this.addcontentB( dataDom );
		if ( this.sortlevel == 1 ) {
			var dom = this,
				content = this.content;
			$.getJSON( './run.php?mid=' + gMid + '&a=get_material_node', function( data ) {
				data.unshift( '<div class="refer-item edit-slide-next refer-my_publisth refer-with-icon"><span class="refer-item-button">&gt;</span><a>我发布的</a></div>' );
				dom.addSortList(data, content);
			});
		}
	},
	//before ajax之前
	addcontentB: function( dataDom ) {
		var next = null;
		this.sortlevel++;
		this.page.total = this.page.nowCount = 0;
		this.page.cache =null;
		if ( this.content == null ) {
			this.title = '引用素材';
			this.outer.slide.html( '<div id="edit-slide-refer' + this.outer.number +'" class="edit-slide-html-each">' + this.get_bcallback()() + '</div>' );
			this.content = this.box.find('.edit-slide-refer-content:last');
		} else {
			if ( !dataDom ) {
				this.title = '我发布的';
				this.nextInfo.islast = 0;
				this.nextInfo.fid = 0;
				next = this.content.find( '.edit-slide-next:eq(0)' );
				this.search = false;
			} else if ( dataDom.hasClass('refer-event-submit') ) {
				this.title = '搜索结果';
				this.nextInfo.islast = 1;
				this.nextInfo.key = dataDom.data('key');
				this.nextInfo.search_type = dataDom.data('search_type');
				next = dataDom;
				this.search = true;
			} else {
				this.nextInfo.islast = dataDom.data( 'islast' );
				this.nextInfo.host = dataDom.data( 'host' );
				this.nextInfo.dir = dataDom.data( 'dir' );
				this.nextInfo.filename = dataDom.data( 'filename' );
				this.nextInfo.fid = dataDom.data( 'fid' );
				this.nextInfo.sort_id = dataDom.data( 'sort_id' );
				this.title = ( this.title == '我发布的' && (this.sortlevel-1) != 1  ? this.title : dataDom.find( 'a' ).text() );
				next = dataDom;
				this.search = false;
			}
			next.data( {
				'bcallback':  this.get_bcallback(),
				'acallback':  this.get_acallback()
			});
		}
	},
	showEmpty: function() {
		var html = '<h1 style="color:red;text-align:center;">没有此类素材！</h1>';
		this.content.html( html );
		$(window).trigger('resize.slide');
	},
	addSortList: function( sortJson, content ) {
		var html = '', cname = false, dom = this;
		$.each( sortJson, function( index, sort ) {
			if (typeof sort === 'string') {
				html += sort;
			} else if (typeof sort === 'object') {
				cname = getSortIconClass( sort.name ) || '';
				dom.host = sort.host;
				dom.dir = sort.dir;
				dom.filename = sort.filename;
				html += '<div class="refer-item edit-slide-next ' + cname +
					'" data-islast="' + sort.is_last + '" data-host="' + sort.host + '" data-dir="' + sort.dir + 
					'" data-filename="' + sort.filename + '" data-fid="' + sort.fid + '" data-sort_id="' + sort.sort_id + '" >' +
					'<span class="refer-item-button">&gt;</span><a>' + sort.name + '</a></div>';
			}
		});
		if ( content ) {
			var tmp = this.content;
			this.content = content;
		}
		this.content.find( '.waiting-img' ).remove();
		if ( this.content.children().length == 0 ) {
			this.content.html( html );
		} else {
			this.content.children().last().after( html );
		}
		if ( content ) {
			this.content = tmp;
		}
		if ( sortJson[1] && sortJson[1].depath == 1 ) {
			this.content.before( this.searchHtml );
		}
		
		$(window).trigger('resize.slide');
		function getSortIconClass( name ) {
			if (name.indexOf('图集库') != -1) {
				return 'refer-with-icon refer-tuji';
			} else if (name.indexOf('投票') != -1) {
				return 'refer-with-icon refer-vote';
			} else if (name.indexOf('视频库') != -1) {
				return 'refer-with-icon refer-vod';
			}
		}
	},
	addMaterialList: function( materialJson ) {
		var data = materialJson,
			type = data.pop(),
			html = '',
			src, num = 0, total, self = this;
		total = data.length;
		if (total < self.page.step + 1) {
			self.page.needNext = false;
			self.page.cache = null;
		} else {
			self.page.needNext = true;
			self.page.cache = data.pop();
		}
		total = data.length;
		
		$.each( data, function( index, n ) {
			//src = n.img.host + '' + n.img.dir + '' + (n.img.filepath || '') + n.img.filename;
			src = $.globalImgUrl(n.img);
			n.src = src;
			self.preloadImg(src, self.curry(countImg, n) );
		});
		self.page.nowCount += total;
		function countImg(n, msg) {
			var type = n.app_bundle, title;
			switch( type ) {
				case self.typeList.VOD:
					title =  'title="点击插入此视频"';
					break;
				case self.typeList.TUJI:
					title =  'title="点击插入此图集"';
					break;
				case self.typeList.VOTE_QUESTION:
					title =  'title="点击插入此投票"';
					break;
				case self.typeList.VOTE:
					title =  'title="点击插入此问卷"';
					break;
			}
			var content = '';
			if ( msg == 'success' ) {
				content = '<img alt="一张素材示意图" src="' + n.src + '" ' + title + ' />';
			} else {
				content = '无索引图';
			}
			html += '<div class="refer-item refer-material-item"><div class="wrap-img" data-host="' + n.host + '" data-dir="' + n.dir + 
					'" data-filename="' + n.filename + '" data-id="' + n.id + '">' + content + '</div><div class="refer-label">' + n.title + '<p>(' + n.update_time + ')</p>' + '</div></div>';
			if ( ++num >= total ) {
				self.content.find( '.waiting-img' ).remove();
				if ( self.content.children().length == 0 ) {
					self.content.html( html );
				} else {
					self.content.children().last().before( html );
				}
				self.showPage();
				$(window).trigger('resize.slide');
			}
		}
		
	},
	curry: function(func) {
		var args = Array.prototype.slice.call(arguments, 1);
		return function() {
			var innerArgs = Array.prototype.slice.call(arguments);
			return func.apply(null, args.concat(innerArgs));
		}
	},
	get_bcallback: function() {
		var dom = this;
		if ( this.sortlevel == 1 ) {
			return function() {
				var search = '<div class="refer-material-search"><label>搜索：<input /></label><a class="refer-event-submit edit-slide-next">确定</a></div>';
				return '<div class="edit-slide-title"><span class="edit-slide-close">关闭</span>引用素材</div>' +
					//search +
					'<div class="edit-slide-refer-content edit-slide-content" data-sortlevel="' + dom.sortlevel + '">正在初始化...</div>';
			};
		} else {
			return function() {
				return '<div class="edit-slide-title"><div class="refer-back-title"><a class="edit-slide-back">返回</a><span class="edit-slide-close">关闭</span>' + dom.title + '</div></div>' +
						'<div class="edit-slide-refer-content edit-slide-content" data-sortlevel="' + dom.sortlevel + '">' + 
						dom.waitingImg + 
						'</div>';
			}
		}
	},
	get_acallback: function() {
		var dom = this;	
		return function() {
			dom.content = $(this).find( '.edit-slide-refer-content:last' );//更新content为当前显示的
			if (dom.search) {
				dom.requestAjaxForSearch();
			} else {
				dom.requestAjax();
			}
		};
	},
	requestAjaxForSearch: function() {
		var dom = this,
			sortlevel = this.sortlevel,
			text = this.nextInfo.key;
			
    	$.getJSON('run.php', {
			mid: gMid,
			a: 'get_material_node',
			host: dom.host,
			dir: dom.dir,
			filename: dom.filename,
			key: text
		}, function(data) {
			data = data || [];
			if ( sortlevel == dom.sortlevel ) { //如果ajax后,当前内容级别切换了,则什么都不做
				data = data.filter(function(d) { return d != null; });
				if (data.length) {
					data.push('mix');
					data.push('isLast');
				}
				dom.addcontentA( data );
			}
		});
		
	},
	requestAjax: function() {
		var url = './run.php?mid=' + gMid + '&a=get_material_node&host=' + this.nextInfo.host + 
				'&dir=' + this.nextInfo.dir + '&filename=' + this.nextInfo.filename + '&fid=' + this.nextInfo.fid,
			isLast = false,
			materialType,
			dom = this,
			sortlevel = this.sortlevel; //记录下ajax发送前,当前内容的级别
		//发ajax请求,得到json
		if( this.title == '我发布的' ) {
			if ( this.nextInfo.islast ) {
				url += '&my_publisth=1';
			} else {
				if (this.sortlevel == 2) {
					url = './run.php?mid=' + gMid + '&a=get_material_node';
				}
			}
		}
		if ( this.nextInfo.islast == 0 ) {
			this.isLast = false;
			isLast = false;
		} else {
			this.isLast = true;
			url += '&sort_id=' + this.nextInfo.sort_id + '&offset=' + this.page.nowCount + '&counts=' + (this.page.step + 1); 
			isLast = true;
			materialType = this.nextInfo.filename; //注意,以文件名区分请求素材的类型,有点不好,暂时这样
		}
		$.ajax({
			url: url,
			type: 'post',
			processData: false,
			contentType: false,
			dataType: 'json',
			success: function( data ){
				if ( !(data && data.length) ) {
					data = [];
				} else {
					if ( isLast ) {
						data.push( materialType );
						data.push( 'isLast' )
					} else {
						data.push( 'notLast' );
					}
				}
				if ( sortlevel == dom.sortlevel ) { //如果ajax后,当前内容级别切换了,则什么都不做
					dom.addcontentA( data );
				}
			}
		});	
	},
	//after ajax之后
	addcontentA: function( json ) {
		if ( $.type(json) !== 'array' || !json.length ) {
			this.showEmpty();	
		}
		var sort = json.pop();
		if( sort === 'isLast' ) {
			this.addMaterialList( json );
		} else {
			this.addSortList( json );
		}
	},
	showPage: function() {
		var html = '<div class="page-control">';
		if( this.page.nowCount > this.page.step ) { //需要上一页
			html += '<a>上一页</a>';	
		}
		if (this.page.hasNext() == true) {
			html += '<a>下一页</a>';
		} 
		if( html != '<div class="page-control">' ) {
	
			html += '</div>';
			this.content.append( html );
		}
	},
	showNextPage: function() {
		this.content.empty().append( this.waitingImg  );
		this.requestAjax();
	},
	showPrevPage: function() {
		var currentNum = this.content.find('.refer-material-item').length;
		this.content.empty().append( this.waitingImg );
		this.page.nowCount -= (currentNum + this.page.step);
		this.requestAjax();
	},
	insertRefer: function( id, type, wImg, el ) {
		type = type || 'refer';
		var dom = this, 
			url = './run.php?mid=' + gMid + '&a=get_sketch_map&host=' + el.data('host') + 
				'&dir=' + el.data('dir') + '&filename=' + el.data('filename') + '&id=' + id;
		$.getJSON( url, function(data) {
			wImg.remove();
			if ( $.type(data) != 'array' || !data[0] ) {
				alert('插入失败！');
				return;
			}
			dom.preloadImg(data[0], function() {
				window['globalSlideInsertHtml' + dom.outer.number](type, data[0]);
			});
		});
	},
	preloadImg : function(src, callback){
        if($.type(src) == 'array'){
            $.each(src, function(i, n){
                var img = new Image();
                img.src = n;
            })
        }else{
            var img = new Image();
            img.onload = function(){
                callback && callback('success');
            };
            img.onerror = function() {
            	callback && callback('error');
            };
            img.src = src;
        }
    }
});

function ReferEvent(number, slide){
    this.number = number;
    this.slide = slide;
    this.editor = window['oEdit' + this.number];
    this.editorWindow = $('#idContentoEdit' + this.number)[0].contentWindow;
    this.box = slide.slideHtml;
    this.loaded = false;
    this.option = null;
    this.slide.addInitFunc(this.getInitFunc());
}

jQuery.extend( ReferEvent.prototype, { 
	getInitFunc: function() {
		var self = this;
		return function() {
			self.init();
		}
	},
	init: function() {
		if (!this.loaded) { 
			this.dom = new ReferDom( this.box, this );
		    this.eventHandles = this.createAllHandles();
		    this.bindAllEvents();
		    this.dom.addContent();
		    this.loaded = true;
		} else {
			//this.dom.sortlevel = 1;
		}
	},
    createAllHandles: function() {
    	var dom = this.dom;
    	//返回的匿名对象中函数的this并不指向此匿名对象,而是触发事件的dom
    	return {
	    	showNext: function( event ) {
	    		if( $(this).hasClass( 'refer-my_publisth' ) ) {
	    			dom.addContent();
	    			return;
	    		} else if ( $(this).hasClass('refer-event-submit') ) {
	    			var text = $(this).parent().find('input').val().trim();
	    			if (!text) return false;
	    			$(this).data({
	    				key: text,
	    				search_type: 1
	    			});
	    		}
	    		dom.addContent( $(this) );
	    	},
	    	showBack: function( event ) {
	    		dom.switchContent();
	    	},
	    	switchPage: function( event ) {
	    		var nORp = $(this).text();
	    		if( nORp == '上一页' ) {
	    			dom.showPrevPage();
	    		} else {
	    			dom.showNextPage();
	    		}
	    	},
	    	back: function( event ) {
	    		var back = $(this).find('.edit-slide-back'),
	    			close = $(this).find('.edit-slide-close');
	    		if (event.target != back[0] && event.target != close[0]) {
	    			back.trigger( 'click' );
	    		} 
	    	},
			insertRefer: function( event ) {
				dom.insertRefer( $(this).data('id'), 'refer', $(dom.smallWImg).appendTo($(this).parent()), $(this) );
			}
    	};
    },
    bindAllEvents: function() {
    	var handles = this.eventHandles;
    	this.box.on( 'click', '.edit-slide-next', handles.showNext );
    	this.box.on( 'click', '.edit-slide-back', handles.showBack );
    	this.box.on( 'click', '.page-control a', handles.switchPage );
		this.box.on( 'click', '.refer-material-item .wrap-img', handles.insertRefer );
    	this.box.on( 'click', '.refer-back-title', handles.back );
    }
});
