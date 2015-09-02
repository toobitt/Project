$(function(){
	var opts = {
		nav : config.nav,
		swiper : $('.swiper-content'),
		navbar : $('.swiper-nav'),
		type : {}		//参数
	};
	config.spinner.show();
	
	document.body.addEventListener('gesturestart', function( event ){
		event.preventDefault();
	});
	
	(function(){
		if( $.isArray( opts.nav ) && opts.nav[0] ){
			$.each( opts.nav, function( _, vv ){
				opts.type[vv.key] = vv.type;
				store.remove('airport.' + vv.key);
			} );
			var content_html = template('content', {list : opts.nav});
			opts.swiper.html( content_html );
			
			setTimeout(function(){
		     	var contGallery = new Swiper('.swiper-content', {
			        paginationBulletRender : function( i, className){
			        	return '<div class="' + className + '" data-attr=' + opts.nav[i]['key'] + '>' + opts.nav[i]['name'] + '</div>';
			        },
			        bulletClass : 'swiper-slide',
			       	pagination: '.swiper-nav',
	        		paginationClickable: '.swiper-nav',
	        		bulletActiveClass : 'swiper-slide-active',
	        		onTransitionStart : function( swiper ){
	        			window.scrollTo(0, 0);
	        			var curIndex = swiper.activeIndex,
	        				prevIndex = swiper.previousIndex,
	        				curnav = opts.navbar.find('.swiper-slide').eq( curIndex );
	        			var type = curnav.data('attr');
	        			if( prevIndex == curIndex ){
	        				return false;
	        			}
	        			if( $.inArray( type, ['start_off', 'get_to'] ) > -1 ){
	        				config.spinner.show();
	        				doAjax( type );
	        			}else{
	        				config.spinner.show();
	        				doHtml( type );
	        			}
	        		},
	        		onTransitionEnd : function( swiper ){
	        			var prevIndex = swiper.previousIndex,
	        				curIndex = swiper.activeIndex,
	        				curnav = opts.navbar.find('.swiper-slide').eq( prevIndex );
	        			var type = curnav.data('attr');
	        			if( prevIndex == curIndex ){
	        				return false;
	        			}
	        			opts.swiper.find('.inner').eq( prevIndex ).empty();
	        		},
	        		onInit : function(){
	        			doAjax( 'start_off' );
	        		}
			    });
			}, 0);
		}
	})();
	
	function doAjax( type ){
		var list = opts.swiper.find('.' + type),
			param = {};
		type && opts.type[type] &&( param.fType = opts.type[type] );
		
		$.getJSON( config.baseUrl, param, function( data ){
			if( $.isArray( data ) && data[0] ){
				$.each( data, function( _, vv ){
					vv.Atd = vv.Atd && vv.Atd.replace(/^[\s]{0,}$/g, '') || '--:--';
					vv.Ata = vv.Ata && vv.Ata.replace(/^[\s]{0,}$/g, '') || '--:--';
					vv.A_time = type == 'start_off' ? vv.Atd : vv.Ata;		//实际时间
					
					vv.Etd = vv.Etd && vv.Etd.replace(/^[\s]{0,}$/g, '') || '--:--';
					vv.Eta = vv.Eta && vv.Eta.replace(/^[\s]{0,}$/g, '') || '--:--';
					vv.E_time = type == 'start_off' ? vv.Etd : vv.Eta;		//预计时间
					
					vv.P_time = vv.P_time && vv.P_time.replace(/^[\s]{0,}$/g, '') || '--:--';		//计划时间
					
					vv.highlight = vv.A_time !== '--:--' ? 'actual' : vv.E_time !== '--:--' ? 'estimate' : 'plan';
					switch( vv.State ){
						case '计划' : vv.mark = 'blue';break;
						case '落地' :
						case '到达' : vv.mark = 'gray';break;
						case '取消' :
						case '延误' : vv.mark = 'red';break;
						default : vv.mark = 'green';break;
					}
				} );
				ajaxBack( list, data );
			}else{
				var html = template('list', {
					tip : data && data.ErrorText || '暂无航班信息',
					hasdata : false,
					noport : data && !data.ErrorText ? 'noport' : ''
				});
				list.after( html );
				config.spinner.close();
			}
		});
	}
	
	function ajaxBack( list, data ){
		var html = template('list', {
				list : data,
				hasdata : true
			});
		list.append( html );
		config.spinner.close();
	}
	
	function doHtml( type ){
		var dom = opts.swiper.find('.' + type),
			content = '', noport = '';
		
		hairport = store.get('airport.' + type , '');
		if( hairport ){
			var html = template('html', {
				html : hairport,
				noport : noport
			});
			dom.append( html );
			config.spinner.close();
			return false;
		}
		
		var url = config.getUrl( type );
		if( !url ){
			return false;
		}
		$.getJSON( url, null, function( data ){
			if( $.isArray( data ) && data[0] && data[0]['content'] ){
				content = escape2Html( data[0]['content'] );
				store.set('airport.' + type, content);
			}else{
				content = '暂无' + (type && opts.type[type] || '航班') + '信息';
				noport = 'noport';
			}
			var html = template('html', {
				html : content,
				noport : noport
			});
			dom.append( html );
			config.spinner.close();
		});
	}
	
	function escape2Html(str) {
	 	var arrEntities={
		 	'lt':'<',
		 	'gt':'>',
		 	'nbsp':' ',
		 	'amp':'&',
		 	'quot':'"',
		 	'#039' : '"'
 		};
	 	return str.replace(/&(lt|gt|nbsp|amp|quot|#039);/ig,function(all,t){return arrEntities[t];});
	};
});
