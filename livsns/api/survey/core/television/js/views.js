/*
 * 全局配置项
 * */
define("export/config", function( require, exports, modules ){
	var config = {
		base_url : '{liv_assist_url}',
		getUrl : function( method ){
			var file = ( method === 'create' ) ? 'survey_update.php' : 'survey.php';
			return this.base_url + file + '?a=' + method;
		},
		inval : {
			id : $('.form-block').find('input[name="id"]').val() || 0
		},
		bar_color : ['#fc6', '#69f'],
		msg : {
			device_limit : '您可通过下载<a href="http://www.appwuhan.com/appdown/down/">掌上武汉客户端</a>参与投票',
			start : '本场投票将于{start_time}开始',
			end : '本场投票已结束',
			success_voted : '投票成功'
		},
		lmt_device : JSON.parse( $('.deviceLimit').html() || '')		//登录和设备限制
	};
	modules.exports = config;
});

/*
 * 判断用户登录形式和登录状态
 * */
define(function( require, exports, modules ){
	var utils = require("exports/utils"),
		config = require('export/config'),
		status = require('exports/status');
	
	utils.spinner.show( $('body') );
	var device = utils.getMobileDevice();
	if( device === 'iOS' || device === 'Android' ){
		if( device === 'iOS' ){
			$( 'html' ).addClass( 'ios' );
			$('.page-content').find('a').each(function(){
				var $this = $(this),
					href = $this[0].href;
				if( href && href.indexOf('_ddtarget') ){
					$this[0].href = href.replace( /_ddtarget=push(&*)/, '' );
				}
			});
		}
		require.async('package', function(){
			hgClient.getUserInfo(function( response ){		//获取用户登录信息
				var userInfo = response && (response.userInfo || response) || '';
				if( userInfo && (userInfo.userid || userInfo.userId) ){
					config.inval.access_token = userInfo.userTokenKey;
				}
			});
			
			hgClient.getSystemInfo(function( response ){		//获取设备信息
				var systemInfo = response.deviceInfo || response;
				var device_token = systemInfo.device_token || systemInfo.devicesToken;
				if( !device_token ){
					var msg = config.lmt_device.no_device_error;
					$('.page-content').show().find('.form-block').show().find( '.btn-area' ).hide().before('<p class="nodata">' + msg + '</p>');
					utils.spinner.close();
					return false;
				}
				var salt = '' + (+new Date()); 
				
				device_token = device_token.substr(0, 10) + salt.substr(0, 5) + device_token.substr(10) + salt.substr(5); 
				
				config.inval.device_token = device_token;
				config.inval.salt = salt;
				config.inval.appid = systemInfo.appid;
				config.inval.appkey = systemInfo.appkey;
				
				status.form();
			});
			
			hgClient.getPlat(function( device ){
				if( device === 'other' ){
					if( config.lmt_device.is_login == 1 || config.lmt_device.is_device == 1 ){
						status.invalid( config.msg.device_limit, 'client' );
					}else{
						status.form();
					}
				}
			});
		});
		
	}else if( config.lmt_device.is_login == 1 || config.lmt_device.is_device == 1 ){
		status.invalid( config.msg.device_limit, 'client' );
	}else{
		status.form();
	}
});

/*表单状态*/
define("exports/status", ["exports/utils", "export/config", "package"], function( require, exports, modules ){
	var utils = require("exports/utils"),
		config = require('export/config');
	// require("package");
	// var toast = $.hg_toast({
		// delay : 1500
	// });
	var page = $('.page-content'),
		form = $('.form-block');
	
	var num_voted_limit = config.lmt_device && config.lmt_device.vote_num ? parseInt( config.lmt_device.vote_num ) : 0,
		isVoted = utils.storage.get( 'isVoted_' + config.inval.id );
	
	var status = {
		checked : function( cbk ){		//检测投票信息
			var param = {
				id : config.inval.id,
				device_token : config.inval.device_token || '',
				salt : config.inval.salt || '',
				appkey : config.inval.appkey || '',
                appid : config.inval.appid || ''
			};
			utils.getAjax( config.getUrl( 'check_voted' ), param, function( data ){
				if( data && ( data.ErrorCode || data.ErrorText ) ){
					status.invalid( data.ErrorText || data.ErrorCode );
				}else if( $.isArray( data ) && data[0] ){
					data = data[0];
					$('.header-block').find('.vote-info').show().find('.vote-num').html( data.total || 0 );
					
					isVoted = data.vote_num;
					utils.storage.set( 'isVoted_' + config.inval.id, isVoted );
					
					config.back = data.back;
					$.isFunction( cbk ) && cbk();
					
					return false;
					if( data.back == 0 ){
						$.isFunction( cbk ) && cbk();
					}else{			//次数错误
						var msg = data.back == 1 ? config.lmt_device.device_num_error : config.lmt_device.device_time_error;
						toast.show( msg, function(){
							require.async('exports/preview', function( preview ){
								preview( data, data.vote_num );
							});
						});
					}
				}
			} );
		},
		
		result : function( cbk ){			//请求结果数据
			var param = {
				id : config.inval.id,
				appkey : config.inval.appkey || '', 
				appid : config.inval.appid || ''
			};
			utils.getAjax( config.getUrl( 'get_result_cache' ), param, function( data ){
				require.async('exports/preview', function( preview ){
					if( data && ( data.ErrorCode || data.ErrorText ) ){
						preview( data.ErrorText || data.ErrorCode );
					}else if( $.isArray( data ) && data[0] ){
						$('.header-block').find('.vote-info').show().find('.vote-num').html( data[0].total || 0 );
						preview( data[0], isVoted );
						$.isFunction( cbk ) && cbk();
					}
				});
			} );
		},
		
		form : function(){		//'form'处于表单状态
			this.progress();
			page.show().find('.view-result').addClass('show');
			require.async('exports/form', function( doSubmit ){
				form.show();
				doSubmit();
				utils.spinner.close();
			});
		},
		
		progress : function(){			//判断表单是否有效
			var _this = this;
			var start = config.lmt_device.start_time ? parseInt( config.lmt_device.start_time + '000' ) : 0,
				end = config.lmt_device.end_time ? parseInt( config.lmt_device.end_time + '000' ) : 0;
			
			page.show();
			
			
			var time = this.time( start, end );
			if( time ){
				( time === 'start' ) ? no_start() : has_end();
				return false;
			}else{
				var area = $('.time-info').addClass('show').find('.time');
				status.valid();
				var gress = progress();
				if( gress ){
					var interval = setInterval(function(){
						time = _this.time( null, end );
						if( time ){
							clearInterval( interval );
							time === 'start' ? no_start() : has_end();
						}else{
							progress();
						}
					}, 1000);
				}
			}
			
			function no_start(){
				var msg = config.msg.start.replace('{start_time}', utils.transfer( start,  'year年month月date日hours时min分'));
				form.show().find('.btn-area').hide().before('<p class="nodata">' + msg + '</p>');
				$('.time-info').removeClass('show');
			}
			function has_end(){
				form.show().find('.btn-area').before('<p class="nodata">' + config.msg.end + '</p>').find('.form-to-json').hide();
				$('.time-info').removeClass('show');
				status.valid();
			}
			function progress(){
				if( !end ){
					$('.time-info').removeClass('show');
					return false;
				}

				var now = (+new Date()),
					interval = end - now;
				area[0].innerHTML = utils.counter( interval,  'hour:min:sec');
				return true;
			}
		},
		
		time : function( start, end ){
			var now = (+new Date());
			if( start && start > now ){
				return 'start';
			}else if( end && end < now ){
				return 'end';
			}else{
				return false;
			}
		},
		
		valid : function( cbk ){		//表单有效
			this.checked( cbk );
			return false;
			if( isVoted === false ){
				this.checked( cbk )
			}else if( config.lmt_device.is_device == 0 || isVoted < num_voted_limit ){
				$.isFunction( cbk ) && cbk();
			}else{
				var _this = this;
				_this.result(function(){
					toast.show( config.lmt_device.device_time_error );
				});
			}
		},
		
		invalid : function( error_msg, type ){		//无效
			page.show().find('.btn-area').hide();
			if( error_msg ){
				form.show().append( '<p class="nodata ' + ( type || "" ) + '">' + error_msg + '</p>' );
			}
			utils.spinner.close();
		}
	};
	
	modules.exports = status;
});

/*
 * 表单提交投票
 * */
define('exports/form', ["exports/utils", "export/config", "package", "exports/status"], function( require, exports, modules ){
	var utils = require("exports/utils"),
		config = require("export/config"),
		status = require("exports/status");
		
	var form = $('.form-block form');
	require("package");
	var toast = $.hg_toast();
	
	var events = {
		submit : function( event ){
			event && event.preventDefault();
			
			var msg;
			if( config.back == 1 ){
				msg = config.lmt_device.device_num_error;
			}else if( config.back == 2 ){
				msg = config.lmt_device.device_time_error;
			}
			if( msg ){
				toast.show( msg, function(){
					utils.spinner.show( form.find('.form-to-json') );
					status.result(function(){
						config.back == 2 && $('.preview-block').find('.view-form').addClass('show');
					}, 2500);
				});
				return false;
			}

			var is_invalid = events.before();
			if( is_invalid ){
				toast.show( is_invalid );
				return false;
			}
	
			var serialize = form.serializeArray(),
				formdata = new FormData();
			$.each(serialize, function( _, vv ){
				if( config.inval[vv.name] ){
					return;
				}
				formdata.append( vv.name, vv.value );
			});
			$.each( config.inval, function( kk, vv ){
				formdata.append( kk, vv );
			} );
			
			events.doform( formdata );
		},
		
		doform : function( formdata ){
			var isVoted = utils.storage.get( 'isVoted_' + config.inval.id ) || 0;
			utils.spinner.show( form.find('.form-to-json') );
			utils.doForm( config.getUrl( 'create' ), formdata, function( data ){
				if( data && ( data.ErrorCode || data.ErrorText ) ){
					toast.show( data.ErrorText || data.ErrorCode );
					utils.spinner.close();
					return false;
				}
				
				if( $.isArray( data ) && data[0] ){
					var msg;
					if( data[0].back == 1 ){			//次数错误
						msg = config.lmt_device.device_num_error;
					}else if( data[0].back == 2 ){			//时间错误
						msg = config.lmt_device.device_time_error;
					}else{
						msg = config.msg.success_voted;
					}
					toast.show( msg, function(){
						$('.header-block').find('.vote-info').show().find('.vote-num').html( data[0].total || 0 );
						require.async('exports/preview', function( preview ){
							preview( data[0], isVoted );
						});
						!data[0].back && utils.storage.set('isVoted_' + config.inval.id, ++isVoted);
					}, 2500);
				}
			} );
		},
		
		before : function(){
			var is_invalid = false;
			form.find('.liv_item').each(function(){
				var $this = $(this),
					required = $this.data('required') ? parseInt( $this.data('required') ) : 0;
				if( required ){
					if( $this.is('.liv_choose') ){
						var is_checked = $this.find('.cell_element input:checked').length;
						if( !is_checked ){
							is_invalid = '请先选择' + $this.find('.cell_name').html() + '选项';
							return false;
						}
					}else if( $this.is('.liv_inputs') ){
						var is_val = false;
						if( $this.is('.liv_inputs') ){
							$this.find('input[type="text"]').each(function(){
								if( $(this).val() ){
									is_val = true;
									return;
								}
							});
						}
						if( !is_val ){
							is_invalid = '请先填写' + $this.find('.cell_name').html();
							return false;
						}
					}else{
						var is_val = $this.is('.liv_textarea') ? $this.find('textarea').val() : $this.find('input[type="text"]').val();
						if( !is_val ){
							is_invalid = '请先填写' + $this.find('.cell_name').html();
							return false;
						}
					}
				}
			});	
			return is_invalid;
		},
		
		result : function( event ){	//请求结果页数据
			event && event.preventDefault();
			utils.spinner.show( $(this) );
			status.result(function(){
				$('.preview-block').find('.view-form').addClass('show');
			});
		},
		
		form : function(){		//回到表单页
			status.checked( function(){
				$('.form-block').show().find('form')[0].reset();
				$('.preview-block').hide().find('.progress-line').each(function(){
					$(this).find('.progress-valid').css({
						width : '0%'
					})
				});
			} );
		},
		
		init : function(){
			form.on('click', '.form-to-json', events.submit)
			.on('click', '.view-result', events.result);
			$('.preview-block').on('click', '.view-form', events.form);
		}
	};
	
	modules.exports = events.init;
});

/*
 * 投票结果预览
 * */
define('exports/preview', function( require, exports, modules ){
	var utils = require("exports/utils"),
		config = require('export/config');
	function preview( data, vote_num ){
		$('.form-block').hide();
			var preview = $('.preview-block').show();
			
			var num_voted_limit = config.lmt_device && config.lmt_device.vote_num ? parseInt( config.lmt_device.vote_num ) : 0;
			
			if( vote_num >= num_voted_limit && config.lmt_device.is_device == 1 ){
				preview.find('.view-form').removeClass('show');
			}
			
			if( data && data.data ){
				data = data.data;
				var bar_len = config.bar_color.length;
				
				setTimeout(function(){
					preview.find('.liv_item').each(function(){
						var item = $(this),
							total = 0;
						var id = item.attr('_id');
						
						$.each( data, function( kk, vv ){
							if( kk.indexOf('_' + id + '_' ) > -1 ){
								total += vv ? parseInt( vv ) : 0;
							}
						} );
						
						item.find('.progress-line').each(function( i ){
							var $this = $(this),
								num = data[ $this.attr('_name') ] ? parseInt( data[ $this.attr('_name') ] ) : 0;
							var cent = total > 0 && num > 0 ? num/total : 0
							cent = cent && utils.ForDight(num/total, 2) + '%' || '0%';
							$this.find('.progress-valid').css({
								width : cent,
								'background-color' : config.bar_color[i% bar_len]
							});
							$this.find('.choice-num').html( num );
							$this.find('.choice-cent').html( '(' + cent + ')' );
						});
					});
				}, 20);
			}else{
				preview.find('.preview-list')[0].innerHTML = '<p class="nodata">' + data + '</p>';
				
			}
			utils.spinner.close();
	}
	modules.exports = preview;
});

/*
 * 功能utils
 * */
define("exports/utils", function( require, exports, modules ){	
	require('spin');
	var utils = {};
	utils.spinner = (function(){
		return {
			show : function( target, opts ){
				if( $.spinner ){
					return;
				}
				target = target || $('.page-content');
				opts = $.extend({
					lines : 12,
	        		length : 4,
	        		width : 2,
	        		speed : 1.4,
	        		radius : 6,
	        		color : '#999'
				}, opts);
				$.spinner = new Spinner( opts ).spin( target[0] );
			},
			close : function(){
				if( $.spinner ){
					$.spinner.stop();
					delete $.spinner;
				}
			}
		}
	})();
	
	utils.ForDight = function(Dight,How) {
	   Dight = (Dight*Math.pow(10,How)).toFixed(2); 
	   return Dight; 
	};
	
	utils.getAjax = function( url, param, callback, type ){
		var type = type || 'get';
		$.ajax({
			type: type,
            url: url,
            dataType: "json",
            data : param,
            cache : false,
            success: function(json){
            	callback( json );
            },
        	error : function(){
        		callback( { ErrorText : '接口访问错误，请稍候再试' } );
        	}
        });
	};
	
	utils.doForm = function( url, param, callback ){
		$.ajax({
			url : url,
			data : param,
        	timeout : 60000,
        	type : 'post',
			dataType : 'json',
			processData : false,
            contentType : false,
			success : function( json ){
				callback( json );
			},
			error : function(){
				callback( { ErrorText : '接口访问错误，请稍候再试' } );
        	}
		});
	};
	
	utils.getMobileDevice = function(){				//获取移动设备类型
		var mbldevice = navigator.userAgent.toLowerCase();
		
		if( !( /wuhan/.test( mbldevice ) && /m2oapp/.test( mbldevice ) ) ){
			return "other";
		}
		if (/micromessenger/.test( mbldevice ) ){
			return "micromessenger";
		}
		if (/iphone|ipod|ipad/gi.test( mbldevice ))
		{
			return "iOS";
		}
		else if (/android/gi.test( mbldevice ))
		{
			return "Android";
		}
		else
		{
			return "Unknow Device";
		}
	};
	
	utils.transfer = function( range, str ){
		var time = new Date( range );
		var year = time.getFullYear(),
			month = time.getMonth() + 1,
			date = time.getDate(),
			hours = time.getHours(),
			min = time.getMinutes();
			
		return str ? str.replace('year' , year)
					.replace('month', month)
					.replace('date', date) 
					.replace('hours', hours) 
					.replace('min', min) 
				: year + '年' + month + '月' + date + '日';
	};
	
	utils.counter = function( range, str ){
		range = Math.floor( range/1000 );
		var day = Math.floor( range / (24 * 60 * 60) ),
			hour = utils.pad(Math.floor( range % (24 * 60 * 60) /(60 * 60))),
			min = utils.pad(Math.floor( range % (24 * 60 * 60) %(60 * 60) / 60)),
			sec = utils.pad(Math.floor( range % (24 * 60 * 60) %(60 * 60) % 60));
		if( str ){
			if( str.match( 'day' ) ){
				str = str.replace('day', day).replace('hour', hour);
			}else{
				str = str.replace( 'hour', utils.pad(parseInt( hour ) + parseInt( day ) * 24 ));
			}
			str = str.replace('min', min)
				.replace('sec', sec);
		}else{
			str = day + '天' + hour + '时' + min + '分' + sec + '秒'; 
		}
		return str;
	};
	
	utils.pad = function(string, len){
        len = len || 2;
        string += '';
        return len - string.length ? (new Array(len - string.length + 1)).join("0") + string : string;
   };
	
	utils.storage = {
	  	set : function( key, value, time ){
	    	var valid = time * 60 * 1e3,
	        	data = {
	          		value : JSON.stringify( value ),
	          		timestamp : ( new Date ).getTime() + valid
	        	};
	    	return localStorage.setItem( key, JSON.stringify( data ) );
	  	},
	  	
	  	get : function( key ){
		    var data = JSON.parse( localStorage.getItem( key ) );
		    return data ? data.timestamp === null ? data.value && JSON.parse( data.value ) : ( new Date ).getTime() < data.timestamp && JSON.parse( data.value ) : !1;
	  	},
	  	
	  	remove : function( key ){
	    	localStorage.removeItem( key );
	  	},
	  	
	  	clear : function(){
		    localStorage.clear();
	  	}
	};
	
	modules.exports = utils;
});