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
					'input input[name="search"]' : '_change'
				});
				this._initinfo();
			},
			
			_switchable : function(){
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
			},
			
			/*实例化数据  插入数据*/
			_initinfo : function( data ){
				var	totaldata=[];
				if( !datalist ){
					return false;
				}
				if( !data ){
					$.each( datalist , function(key , value ){
						var sdata = [ key , datalist[key] ];
						totaldata.push( sdata );
					});
					var selectdata = this._slice( totaldata );
				}else{
					var selectdata = this._slice( data );
				}
				var box = this.element.find('.switch-slider-box'),
					info = {};
				info.option = selectdata;
				box.empty();
				selectdata[0] ? $('#select-tpl').tmpl( info ).appendTo( box ) : box.append('<div class="no-data">没有找到相关数据！</div>');
				if( selectdata.length >1 ){
					this.element.find('.arrow').show();
					this._switchable();
				}else{
					this.element.find('.arrow').hide();
				}
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
					if(data.indexOf(datalist[key])>=0 || datalist[key].indexOf(data)>=0){
						var sa = [ key , datalist[key] ];
						searchdata.push( sa );
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
	$('.nav-box').site_list();
});
