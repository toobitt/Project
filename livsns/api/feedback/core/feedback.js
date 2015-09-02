var platform = navigator.userAgent.toLowerCase();
var isIosDevice = (/iphone|ipod|ipad/gi).test(platform),
	isIPad = (/ipad/gi).test(platform),
	isAndroid = (/android/gi).test(platform),
	isAndroidOld = (/android 2.3/gi).test(platform) || (/android 2.2/gi).test(platform),
	isSafari = (/safari/gi).test(platform) && !(/chrome/gi).test(platform);
var MC = $('body , html');
var control = {
	init : function(){
		//this.getInfo();
		if( !(isIosDevice || isAndroid) ){
			 var action = 'click';
		}else{
			 var action = 'touchstart';
		}

		MC
		.on(isIosDevice ? 'touchstart' : 'click' , '.question' , $.proxy(this.select , this))
		.on('change' , 'input[type="file"]' , $.proxy(this.change , this))
		.on('change' , 'select' , $.proxy(this.changeSelect , this))
		.on('input' , 'input[type="text"] , textarea' , $.proxy(this.checkinput , this))
		.on( action , '.down , .up' , $.proxy(this.down , this))
		.on( action , '.replay-btn' , $.proxy(this.replay , this))
		.on( action , '.touch-del' , $.proxy(this.delPic , this))
		.on( action , '.verify-code' , $.proxy(this.getVerifycode , this))
		this.submitContent();
	},

	getInfo : function(){
		 if( true || !( isIosDevice || isAndroid  )){
			 MC.find('.main-wrap').show();					/*pc端 桌面*/	
			 this.msg('数据加载中...');
			 this.msgHide(0,false);
			 var jum_url = $.trim($('input[name="jump_to"]').val());
			 if(jum_url){
				 window.location.href = jum_url; 
			 }
			 else if(getQueryString('access_token')){
				 this.access_token = getQueryString('access_token');
				 this.appkey = getQueryString('appkey');
				 this.appid = getQueryString('appid');
				 MC.find('input[name="appid"]').val( this.appid );
				 MC.find('input[name="appkey"]').val( this.appkey );
				 MC.find('input[name="access_token"]').val( this.access_token );
			 }
			 else{
				 this.msg('对不起，该页面加载失败!');
			 }
		 }
	},

	getResult : function( data ){
		var _this = this,
			param = getParam() ,
			is_edit = param.is_edit ? param.is_edit : 0,
			url = '../feedback.php?a=check_feedback&is_edit=' + is_edit + '&is_result_page=' + getQueryString("is_result_page") + '&pid=' + getQueryString("pid");
		this.ajax( url , data , function( json ){
			if( json.ErrorCode ){
					MC.find('.main-wrap').show();
					if(json.ErrorCode == 'NO_ACCESS_TOKEN'){
						setTimeout(function(){
							hgClient.goLogin();
						}, 2400);
					}
					_this.msg(json.ErrorText);
                    if(json.ErrorCode == 'NO_DEVICE_TOKEN' || json.ErrorCode == 'FB_END' || json.ErrorCode == 'FB_PROCESS')
                    {
                        setTimeout(function(){
                            hgClient.goBack();
                        }, 1000);
                    }
					_this.msgHide(2000 , false);
			}else{
				if( json[0].back == 0){
					MC.find('.main-wrap').show();
					_this.msgHide(0,false);
					if(json[0].field)
					{
						$.each(json[0].field, function(Key, value){
							var objinput = MC.find('input[name="'+ Key +'"]');
							var objselect = MC.find('select[name="'+ Key +'"]');
							if(objinput.length > 0)
							{
								objinput.val(value);
								objinput.parent().find('ul li[_value="'+ value +'"]').addClass('select');
							}
							if(objselect.length > 0)
							{
								var selected = objselect.find('option[value="'+ value +'"]');
								if(selected && selected.length > 0)
								{
									selected.attr("selected",true);
									objselect.parent().find('.select').text(value);
								}
								if(selected && selected.length > 0 && objselect.parent().hasClass('prov'))
								{
									var citys = '<option>' + '- 请选择 -' + '</option>'; 
									if(json[0].address.city)
									{
										$.each(json[0].address.city, function(Index, city){
								               citys += '<option _id="'+ Index +'" value="'+ city +'">' + city + '</option>';
								         });
										MC.find('.city select').html(citys);
									}
								}
								if(selected && selected.length > 0 && objselect.parent().hasClass('city'))
								{
									var areas = '<option>' + '- 请选择 -' + '</option>'; 
							         $.each(json[0].address.area, function(Index, area){
							               areas += '<option _id="'+ Index +'" value="'+ area +'">' + area + '</option>';
							         });
							         MC.find('.area select').html(areas);
								}
							}
				         });
					}
				}else{
					if(json[0].html && !is_edit){
						MC.find('.result-wrap').show();
						MC.find('.result').empty().html( json[0].html );
						MC.find('.talk-record').empty().html( json[0].message );
						_this.msgHide(0,false);
					}
					if(json[0].html && is_edit){
						MC.find('.main-wrap').show();
						MC.find('.fb-form').empty().html( json[0].html );
						MC.find('input[name="func"]').val('update');
						MC.find('input[name="pid"]').val( json[0].pid );
						_this.msgHide(0,false);
					}
				}						
			}	
		})
	},

	select : function( event ){
		event.stopPropagation();
		var self = $(event.currentTarget),
			item = self.closest('ul'),
			id = item.attr('_id'),
			type = item.attr('_type'),
			tp = item.attr('_tp');
		if(type == 1){
			var value = self.attr('_value');
			self.find('.pointer').show();
			self.addClass('select').siblings().removeClass('select');
			item.parent().find('input[type="hidden"]').val(value);								
		}else{
			self.toggleClass('select');
			var value = item.find('li').map(function(){
				if( $(this).hasClass('select') ){
					return $(this).attr('_value');
				}
			}).get().join(',');	
			item.parent().find('input[type="hidden"]').val(value);
		}
	},

	change : function( e ){
		var self = e.currentTarget;
		$(self).closest('.file-item').find('.icon-ok').addClass('icon-loading').show();
		if(isIosDevice){
			file = self.files[0],
	   		type = file.type,
	   		box = $(self).prev('.img-box');
			var reader=new FileReader();
			reader.onload=function(event){
				imgData=event.target.result;
				var img = box.find('img');
				!img[0] && (img = $('<img />').prependTo( box ));
				img.attr('src', imgData);
				box.show();
			}
	    	reader.readAsDataURL( file );
		}
		$(self).closest('.file-item').find('.icon-ok').removeClass('icon-loading');
	},
	
	changeSelect : function( event ){
		var _this = this,
			self = $( event.currentTarget ),
			item = self.closest('.select-box'),
			id =  self.find('option').not(function(){
				return !this.selected;
			}).attr('_id'),
			value = self.val() ? self.val() : '- 请选择 -';
		item.find('.select').text( value );
		if( item.hasClass('prov') ){
			var data = {
					province_id : id,
					appid : _this.appid ,
					appkey : _this.appkey,
				},
				url = "../address.php?a=show_city";
			this.ajax( url , data , function( json ){
				_this.getCity( self , json );
			})
		}else if( item.hasClass('city') ){
			var data = {
					city_id : id,
					appid : _this.appid ,
					appkey : _this.appkey,
				},
				url = "../address.php?a=show_area";
			this.ajax( url , data , function( json ){
				_this.getArea(self, json );
			})
		}
	},

	getCity : function( self , data ){
		 var value;	data = data[0];
		 var obj = self.closest('.file-item');
         obj.find('.city select').empty();   //清空resText里面的所有内容
         obj.find('.area select').empty();   //清空resText里面的所有内容
         var citys = '<option>' + '- 请选择 -' + '</option>'; 
         if( data ){
	         $.each(data, function(Index, city){
	               citys += '<option _id="'+ Index +'" value="'+ city +'">' + city + '</option>';
	         });
         }
         var areas = '<option>' + '- 请选择 -' + '</option>'; 
         obj.find('.city select').html(citys);
         obj.find('.area select').html(areas);
         obj.find('.city .select').text('- 请选择 -');
         obj.find('.area .select').text('- 请选择 -');
         
	},

	getArea : function( self , data ){
		 var value;	data = data[0];
		 var obj = self.closest('.file-item').find('.area select');
		 obj.empty();   //清空resText里面的所有内容
         var areas = '<option>' + '- 请选择 -' + '</option>'; 
         if( data){
	         $.each(data, function(Index, area){
	               areas += '<option _id="'+ Index +'" value="'+ area +'">' + area + '</option>';
	         });
         }
         obj.html(areas);
         self.closest('.file-item').find('.area .select').text('- 请选择 -');
	},

	delPic : function( event ){
		var self = $( event.currentTarget ),
			box = self.closest('.file-item'),
			name = box.find('input[type="file"]').attr('name');
		box.find('img').remove()
		box.find('.img-box').hide();
		box.find('.icon-ok').hide();
		box.find('input[type="file"]').remove();
		box.append( $('<input name="'+ name +'" type="file" class="file" style="opacity:0.00000000000001;" />') );
	},
	
	checkinput : function( event ){
		var self = $(event.currentTarget),
			txt = $.trim( self.val() );
		if( txt.substr(0, 1) == "@" ){
			alert('首字符不能是@');
			self.val('');
		}
	},

	down : function( event ){
		event.stopPropagation();
		MC.find('.my-info').hasClass('up') ? MC.find('.my-info').removeClass('up') : MC.find('.my-info').addClass('up');
		MC.find('.info-list').toggle();
	},

	replay : function( event ){
		var self = $( event.currentTarget ),
			item = MC.find('input[name="replay"]'),
			msg = item.val(),
			url = '../feedback.php?a=send_message',
			box = MC.find('.talk-list');
		var info ={};
		info.id = MC.find('input[name="id"]').val();
		info.access_token = this.access_token;
		info.message = msg;
		box.append( this.loading ); 
		if( msg ){
			self.removeClass('replay-btn');
			this.ajax( url , info , function( json ){
				if( json.ErrorText || json.ErrorCode ){
					var error = json.ErrorText ? json.ErrorText : json.ErrorCode;
					alert( error );
					return false;
				}else{
					box.html( json[0].html);
					self.addClass('replay-btn');
					item.val('');
				}	
			})
			
		}	
	},
	
	getVerifycode : function( event ){
		var self = $( event.currentTarget ),
			type = self.attr('_type'),
			url = '../feedback.php?a=get_verifycode',
			_this =this,
			param = {verifycode_type : type ,appid : _this.appid ,appkey : _this.appkey};
		self.text('获取中...');
		this.ajax(url , param , function( json ){
			self.html('<img src="'+ json[0].img +'" />');
			MC.find('input[name="session_id"]').val( json[0].session_id );
		})
	},
	
	ajax : function( url, param, callback ){	
		 $.getJSON( url, param, function( data ){
			 if( $.isFunction( callback ) ){
				 callback( data );
			 }
		 });
	},

	msg : function( txt ){
		var item = MC.find('.loading');
			
		item.show().text( txt );
	},

	msgHide : function( time ,refresh ){
		setTimeout(function(){
			MC.find('.loading').hide();
			refresh && location.reload();
		},time);
	},
	
	submitContent : function(){
		MC.find('form').submit(function(){
			MC.find('.loading');
			MC.find('.submit').attr('disabled',true); 
			MC.find('.submit').text('正在提交中...');
		})
	},
	
}
$(function(){
	control.init();
});

function showTip(tip){
	var body = $('body');
	var r = confirm(tip);
	body.find('.submit').text('确定');
	setTimeout(function(){
		if(tip == '提交成功！')
		{
			location.search = (location.search.replace('is_edit=1', 'is_edit=0').indexOf('?') != -1 ? '&' : '?') + ('hash=' + Math.ceil(Math.random()* 1000)); 
			//location.replace(location.href);
			return;
		}
		else
		{	
			MC.find('.submit').removeAttr('disabled'); 
			var code = body.find('.verify-code');
			code.find('img').remove();
			code.text('点击获取验证码');
			body.find('.submit').text('确定');
		}
		
		setTimeout(function(){
			body.find('#backMsg').remove();
			$('<iframe name="backMsg" id="backMsg" style="display:none;"></iframe>').appendTo( body );
		},0);
	},'1000');
}

function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]); return null;
    }

function getParam(){
	var search = location.search.substring(1),
		pairs = search.split('&');
	var args = {}, param = {};
	for(var i=0; i< pairs.length; i++){
		var pos = pairs[i].indexOf('=');
		if( pos == -1 ) continue;
		var arg = pairs[i].substring(0, pos);
		args[arg] = pairs[i].substring(pos+1);
	}
	return args;
}