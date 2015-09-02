$(function(){
	var MC = {
		type : $('.type').find('.section'),
		push : $('.push').find('.section'),
		attention : $('.attention').find('.section'),
		searchForm : $('#searchform'),
		typeLabel : $('#display_type_item'),
		typeValue : $('#app_uniqued'),
		timeLabel : $('#display_time_item'),
		timeValue : $('#access_time'),
		setItem : $('.type').find('.set-item')
	};
	var searchForm = $.searchForm = {
		saveSetUrl : './run.php?mid=' + gMid + '&a=save_set&ajax=1',
		getInfo : function(){
			return info = {
				ltype : MC.typeLabel.html(),
				vtype : MC.typeValue.val(),
				linterval : MC.timeLabel.html(),
				vinterval : MC.timeValue.val(),
				kvalue : MC.searchForm.find('input[name="k"]').val()
			};
		},
		
		setInfo : function( data ){
			MC.typeLabel.html( data.ltype );
			MC.typeValue.val( data.vtype );
			MC.timeLabel.html( data.linterval );
			MC.timeValue.val( data.vinterval );
		},
		
		getStorage : function(){
			var setSave = this.setStorage.getItem();
			if( setSave[0] ){
				this.showStorage( setSave[0] );
				this.setInfo( setSave[0] );
			}
		},
		
		showStorage : function( data ){
			var str = data.ltype + "-" + data.linterval;
			MC.setItem.html( str );
		},
		
		saveSet : function( event ){
			var _this = this,
				self = $(event.currentTarget),
				info = this.getInfo();
			self[0].disabled = true;
			$.globalAjax( self, function(){
				return $.getJSON(_this.saveSetUrl, {access_time : info.vinterval, k : info.kvalue, app_uniqued : info.vtype}, function( data ){
					self[0].disabled = false;
					if(data['callback']){
						eval( data['callback'] );
						return;
					}
					if( $.isArray( data ) && data[0] ){
						self.myTip({
							string : '保存成功',
							dleft : 120,
							dtop : 10
						});
					}
				});
			});
			return;
			this.setStorage.resetItem( [info] );
			this.showStorage( info );
		},
		
		showSet : function(){
			var showSave = this.setStorage.getItem();
			showSave[0] && this.setInfo( showSave[0] );
		},
		
		submitSet : function(){
			var _this = this,
				submit = MC.searchForm.find('input[name="hg_search"]');
			MC.searchForm.ajaxSubmit({
				beforeSubmit : function(){
					submit[0].disabled = true;
				},
				dataType : 'json',
				success : function( data ){
					if( data[0] ){
						MC.type.list('getData', data[0] );
					}
				},
				complete : function(){
					submit[0].disabled = false;
				}
			});
			return false;
		},
		
		init : function( el ){
			this.el = el;
			this.setStorage = new Hg_localstorage( 'deploy' );
			this.getStorage();
			this.el
			.on('click', '.save-set', $.proxy(this.saveSet, this))
			.on('click', '.set-item', $.proxy( this.showSet, this))
			.on('click', '.search', $.proxy( this.submitSet, this))
		},
	};
	MC.type.list({
		itemtpl : $('#item-tpl').html(),
		noitemtpl : $('#noitem-tpl').html(),
		getContentUrl : './run.php?mid=' + gMid + '&a=get_content&ajax=1',
		pushAttention : function( event, obj ){
			MC.attention.attention('localAttention', obj);
		},
		popStorage : function( event, id ){
			MC.attention.attention('popAttention', id);
		},
		
		drawList : function( event, type ){
			MC.attention.attention('drawAttention', type);
		}
	});
	
	MC.push.attention({
		moduletpl : $('#module-tpl').html(),
		nomoduletpl : $('#nomodule-tpl').html(),
		type: false,
		key : 'push',
		getContentUrl : './run.php?mid=' + gMid + '&a=get_content&ajax=1',
	});
	
	MC.attention.attention({
		moduletpl : $('#module-tpl').html(),
		nomoduletpl : $('#nomodule-tpl').html(),
		key : 'attention',
		getContentUrl : './run.php?mid=' + gMid + '&a=get_content&ajax=1',
		sysStorage : function( event, ids){
			MC.type.list('listStorage', ids);
		},
		popList : function( event, id ){
			MC.type.list('onStorage', id);
		},
		
	});
	searchForm.init( MC.searchForm );
});
