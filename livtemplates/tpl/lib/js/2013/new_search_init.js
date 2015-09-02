$( function(){
	(function($){
		var MC = $('body'),
			nav_box = MC.find('.nav-box:first'),
			homeUrl = SCRIPT_URL + '2013/',
			js = ['new_search'],
			href = window.location.href,
			postToFrame = 'nodeFrame';
			
		if( !$('#nodeFrame').length ){
			postToFrame = 'mainwin';
		}
		
		var tools = {
			search2map : function( src ){
				var map = {};
				if( src ){
					var index = src.indexOf('?'); 
					( index > 0 ) && (  src = src.slice( index+1 ) );
					src.split('&').forEach( function( value ){
						value = value.split('=');
						map[value[0]] = value[1];
					} );
				}
				return map;
			},
			
			advanceSearh : function( target ){
				/*配置页面的模块是否需要高级搜索判断*/
				var current_mid = '';
				if( href.indexOf( 'a=configuare' ) != -1 ){
					var current_menu = target || MC.find('#append_menu a.append_cur'),
						menu_href = '';
					current_menu.length && ( menu_href = current_menu.attr('href') );
					if( menu_href ){
						var map = tools.search2map( menu_href );
						map['mid'] &&  ( current_mid = map['mid'] );
					} 
					if( !current_mid   ) return;
					gMid = current_mid;
				}
				if( nav_box.hasClass('new-nav-box') ){
					nav_box.removeClass('new-nav-box');
				}
				$.m2oDeferred( js, homeUrl, function(){
					$.advanceSearchWidget = MC.search_pop( {
						target : postToFrame,
						search_ajax_url : './run.php?mid=' + gMid + '&a=advanced_search',	//配置高级搜索接口
						get_label_url : './run.php?mid=' + gMid + '&a=get_searchtag',		//获取标签列表接口
						save_label_url : './run.php?mid=' + gMid + '&a=save_searchtag',		//保存标签接口
						del_label_url : './run.php?mid=' + gMid + '&a=delete_searchtag',	//删除标签接口
						site_url : './get_publish_content.php?a=get_site',					//获取站点接口
						column_url : './fetch_column.php'									//获取栏目接口
					} );
				} );
			}
		};
		
		MC.on('click','#append_menu a', function( event ){
			if( $.advanceSearchWidget ){
				$.advanceSearchWidget.search_pop('destroy');
				$.advanceSearchWidget = null;
			}
			tools.advanceSearh( $(this) );
		});
	
		tools.advanceSearh();
		
		
	})($);
	
} );
