$(function(){
	(function($){
		$.widget('site.site_list',{
			options : {
			},
			
			_create : function(){
			},
			
			_init : function(){
				this._on({
					'mouseenter .site-nav' : '_over',
					'click .search-img' : '_search',
					'keyup input[name="search"]' : '_change'
				});
				this._switchable();
				this.element.find('.arrow').show();
			},
			
			_switchable : function(){
			    var len = this.element.find('.list-item').length;
			    if( len > 1 ){
			    	this.element.find('.switch-slider-box').switchable({
				        triggers: false,
				    	panels: '.list-item',
				    	effect: 'scrollRight',
				    	easing: 'cubic-bezier(.455, .03, .515, .955)',
				    	end2end: true,
				    	autoplay: false,
				    	next : '.next',
				    	prev : '.prev',
				    });
			    }
			    this._onOffarrow( len > 1 ? true : false );
			},
			
			/*实例化数据  插入数据*/
			_initinfo : function( data ){
				var selectdata = this._slice( data );
				var box = this.element.find('.switch-slider-box'),
					info = {};
				info.option = selectdata;
				box.empty();
				selectdata.length ? this.element.find('#select-tpl').tmpl( info ).appendTo( box ) : box.append('<div class="no-data">没有找到相关数据！</div>');
				this._switchable();
			},
			
			_onOffarrow : function( bool ){
				this.element.find('.arrow')[ bool ? 'show' : 'hide' ]();
			},
			
			_over : function(){
				this.element.find('.site-box').show();
			},
			
			_change : function( event ){
				this._search( event );
			},
			
			_search : function( event ){
				var self = $(event.currentTarget),
					val = $.trim(this.element.find('input[name="search"]').val());
				this._getsearchlist( val );
			},
			
			/*关键字搜索*/
			_getsearchlist : function( data ){
				var searchdata = [];
				$.each( datalist , function(key , value ){
					if( !data ){
						var result = [ key , value ];
						searchdata.push( result );
					}else{
						if( value.indexOf( data ) > 0  ){
							var result = [ key , value ];
							searchdata.push( result );
						}
					}
				});
				this._initinfo( searchdata );
			},
			
			/*截断数据 分为50条数据一组的数组*/
			_slice : function( data ){
				var result = [];
				for(var i=0,len=data.length;i<len;i+= 50 ){
				   result.push(data.slice(i,i+50));
				}
				return result;
			}
		});
})($);
});
