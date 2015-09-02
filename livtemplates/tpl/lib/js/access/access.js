(function($){
	$.widget('access.attention', {
		options : {
			moduletpl : '',
			moduletname : 'module-tpl',
			nomoduletpl : '',
			nomoduletname : 'nomodule-tpl',
			getContentUrl : '',
			type : true,
			key : '',
		},
		
		_create : function(){
			var op = this.options;
			$.template(op.moduletname, op.moduletpl);
			$.template(op.nomoduletname, op.nomoduletpl);
			this.dattention = [];
			this.storage = new Hg_localstorage({key : op.key});
			//this.storage.removeItem( op.key );
		},
		
		_init : function(){
			this._on({
				'click .attention .cancel' : '_cancel',
			});
			!this.options.type && this._sysPush();
		},
		
		/*系统推送*/
		_sysPush : function(){
			var spush = this.storage.getItem() || {};
			var dpush = spush[0];
			var currenttime = new Date().getTime();
			var id = [666,730];
			var vid = id.join(',');
			if(dpush && dpush.content.length && dpush.key){
				var times = currenttime - dpush.key,
					intertime = parseInt(times/(1000*60));
				if(intertime > 1){
					this._attentionData( this.options.getContentUrl, true, vid );
				}else{
					this._tmplModule( dpush.content );
				}
			}else{
				this._attentionData( this.options.getContentUrl, true, vid );
			}
		},
		
		/*已关注数据*/
		drawAttention : function( type ){
			var ids = this.storage.getItem();
			if(ids.length){
				type && this._attentionData( this.options.getContentUrl, false, ids.join(',') );
				this._trigger('sysStorage', event, [ids]);
			}else{
				$.tmpl(this.options.nomoduletname, {sort: '已关注'}).appendTo( this.element.find('ul').empty() );
			}
		},
		
		/*取消关注*/
		_cancel : function( event ){
			var id = $(event.currentTarget).closest('li').data('cid');
			this.popAttention(id);
			this._trigger('popList', event, [id]);
		},
		
		popAttention : function( id ){
			this.storage.updateItem( id, false );
			var sData = [];
			$.each(this.dattention, function(key, value){
				if(value.k == id){
					value = null;
				}
				if(value){
					sData.push(value);
				}
			});
			this.dattention = sData;
			this._tmplModule( this.dattention );
		},
		
		
		localAttention : function( item ){
			var oInfo = {};
			oInfo.k = item.data('cid');
			oInfo.data = {
				id : item.data('id'),
				bundle_name : item.find('.m2o-sort').html() || '无类型',
				title : item.find('.m2o-title-overflow').attr('title'),
				access_nums : item.find('.m2o-visit').html(),
				comment_num : item.find('.m2o-comment').html()
			}
			this.dattention.push( oInfo );
			var sdata = this.storage.updateItem( oInfo.k, true);
			this._tmplModule( this.dattention );
		},
		
		_attentionData : function( url, type, id ){
			var _this = this;
			$.globalAjax( this.element, function(){
				return $.getJSON(url, {id : id}, function( data ){
					if(data['callback']){
						eval( data['callback'] );
						return;
					}
					_this._getAttention( data[0].content, type );
				});
			});
		},
		
		_getAttention : function( data, type ){
			var _this = this;
			var daccess = [];
			var oInfo = {};
			$.each(data, function(key, value){
				var param = {};
				param.k = value.cid;
				param.data = {
					id : value.id,
					bundle_name : value.bundle_name || '无类型',
					title : value.title,
					access_nums : value.access_nums,
					comment_num : value.comment_num
				}
				daccess.push( param );
				_this.dattention.push( param );
			});
			var currenttime = new Date().getTime();
			oInfo.key = currenttime;
			oInfo.content = daccess;	
			if(type && !this.options.type ){
				this.storage.resetItem( [oInfo] );
			}
			this._tmplModule( daccess );
		},
		
		_tmplModule : function( data ){
			var _this = this;
			var widget = this.element.find('ul').empty();
			if(data.length){
				var pushArr = [];
				$.each(data, function(key, value){
					var data = value.data;
					var waccess = _this._getWidth( data.access_nums );
						wcomment = _this._getWidth( data.comment_num );
					pushArr.push({
						cid : value.k,
						nbundle : data.bundle_name,
						title : data.title,
						naccess : data.access_nums,
						ncomment : data.comment_num,
						ispush : _this.options.type,
						id : data.id,
						waccess : waccess,
						wcomment : wcomment
					});
				});
				$.tmpl(this.options.moduletname, pushArr).appendTo( widget );
			}else{
				var sort = this.options.type ? '已关注' : '系统推送'
				$.tmpl(this.options.nomoduletname, {sort : sort}).appendTo( widget );
			}
		},
		
		_getWidth : function( num ){
			return (10 + Math.floor(num/20)) + 'px';
		}
	});
	
	$.widget('access.list', {
		options : {
			itemtpl : '',
			itemtname : 'item-tpl',
			noitemtpl : '',
			noitemtname : 'noitem-tpl'
		},
		
		_create : function(){
			var op = this.options;
			$.template(op.itemtname, op.itemtpl);
			$.template(op.noitemtname, op.noitemtpl);
			this.box = this.element.find('.m2o-each-list');
		},
		
		_init : function(){
			this._on({
				'click .add' : '_click',
				'click .sort_visit' : '_sort'
			});
			this._ajaxList();
		},
		
		/*初始化列表数据*/
		_ajaxList : function(page, page_num){
			var _this = this;
			var info = {};
			page ? info.page = page : '';
			page_num ? info.page_num = page_num : '';
			var form_data = $.searchForm.getInfo();
			info.app_uniqued = form_data.vtype || '';
			info.k = form_data.kvalue || '';
			info.access_time = form_data.vinterval || '';
			$.globalAjax( this.element, function(){
				return $.getJSON(_this.options.getContentUrl, info, function( data ){
					if(data['callback']){
						eval( data['callback'] );
						return;
					}
					_this.getData( data[0] );
				});
			});
		}, 
		
		_sort : function( event ){
			var self = $(event.currentTarget),
				attrid = self.attr('attr');
			if( self.hasClass('selected') ){
				return; 
			}
			if( this.element.find('.common-list-empty').length ){
				var str = (attrid == 1) ? '降序' : '升序'; 
				self.myTip({
					string : '暂无可' + str + '的数据'
				});
				return;
			}
			self.addClass('selected').siblings('.sort_visit').removeClass('selected');
			$('.access_nums').val( attrid );
			$('.search').trigger( 'click' );
		},
		
		getData : function( data ){
			this._getContent( data.content );
			data.page && this._initPage( data.page );
			if(this.element.data('init')){
				this._trigger('drawList', event, [0]);
			}else{
				this._trigger('drawList', event, [1]);
				this.element.data('init', true);
			}
		},
		
		_getContent : function( data ){
			var widget = this.box.empty();
			if(data.length){
				var dContent = [];
				$.each(data, function(key, value){
					dContent.push({
						id : value.id,
						order_id : value.order_id,
						cid : value.cid,
						title : value.title,
						pic : value.indexpic ? $.globalImgUrl(value.indexpic, '40x35') : '',
						content_url : value.content_url,
						bundle_name : value.bundle_name,
						access_nums : value.access_nums,
						comment_num : value.comment_num,
						update_time : value.update_time,
                        publish_time: value.publish_time
					});
				});
				$.tmpl(this.options.itemtname, dContent).appendTo( widget );
			}else{
				$.tmpl(this.options.noitemtname).appendTo( widget );
			}
		},
		
		_initPage : function( option ){
			var page_box = this.element.find('.page_size'),
					_this = this;
			if(page_box.data('init')){
				page_box.page('refresh',option);
			}else{
				option['page'] = function( event, page, page_num ){
					_this.refresh(page, page_num);
				}
				page_box.page( option );
				page_box.data('init', true);
			}
		},
		
		refresh : function( page, page_num ){
			this._ajaxList( page, page_num );
		},
		
		_click : function( event ){
			var self = $(event.currentTarget);
			var item = self.closest('.m2o-each');
			if(self.parent().hasClass('on')){
				var id = item.data('cid');
				this._popAttention( id );
				self.attr('title', '关注').parent().removeClass('on');
			}else{
				this._trigger('pushAttention', event, [item]);
				self.attr('title', '取消关注').parent().addClass('on');
			}
		},
		
		_popAttention : function( id ){
			this._trigger('popStorage', event, [id]);
		},
		
		listStorage : function( ids ){
			for(var i=0, len=ids.length; i<len; i++){
				this.onStorage( ids[i], true );
			}
		},
		
		onStorage : function( id, type ){
			var onStorage = this.element.find('.m2o-each').filter(function(){
				return $(this).data('cid') == id;
			});
			onStorage.find('.m2o-attention')[(type ? 'add' : 'remove') + 'Class']('on');
			var sattention = type ? '取消关注' : '关注';
			onStorage.find('.add').attr('title', sattention)
		},
	});
})($);
