(function( $ ){
	$.widget('client.clientPicupload', {
		options : {
			'avatar-url':"./run.php?mid="+gMid+"&a=upload&admin_id=" + gAdmin.admin_id + "&admin_pass=" + gAdmin.admin_pass,
		     client_logo_item:'#client_logo_item'
        },
        
        _create : function(){
        	this.uploadFile = this.element.find('.client-file-data');
        	this.info={};
        },
        
        _init:function(){
        	var _this = this;
        	_this._on({
                'click .client-item' : '_upload',
                'click .client_logo' : '_upload',
            });
			
			this.uploadFile.ajaxUpload({
                url : _this.options['avatar-url'],
                phpkey : 'Filedata',
                before : function(info){
                    _this._uploadBefore( info['data']['result'] );
                },
                after : function(json){
                    _this._uploadAfter(json);
                }
            });
        },
        
        _upload : function(event){
        	var self=$(event.currentTarget);
        	this.info['id']=self.data('id');
        	this.info['client_name']=self.data('name');
        	this.edit=false;
        	this.event=self;
        	if(self.closest('.client_logo_item').length){
        		this.edit=true;
        	}
        	this.uploadFile.click();
        },

        _uploadBefore : function(src){
            this._avatar(src);
        },

        _uploadAfter : function(json){
            var data = json.data,
                client_logo_item = $(this.options['client_logo_item']+this.info.id);
            this.info['picinfo']=data['pic'];
            if(this.edit){
            	client_logo_item.find('input').remove();
        		this.edit=false;
            }else{
	            this.event.hide();
            }
            $('<input type="hidden"  name="client_top_pic['+this.info['id']+']"/>').val( this.info.picinfo ).appendTo( client_logo_item );
        },
        
        _avatar : function(src){
        	if(this.edit){
        		this.event.closest('.client_logo_item').find('img').attr('src',src);
        	}else{
	        	this.info['url']=src;
	            $('#client-tpl').tmpl(this.info).prependTo( $('.client_log_all') );
        	}
        }
	});
})(jQuery);
$(function(){
	$('.ad_form').submit(function(){
		var $this = $(this),
			btn = $this.find('input[type="submit"]');
		$this.ajaxSubmit({
			beforeSubmit:function(){
				var tips = '';
				var title = $this.find('input[name="column_name"]').val();
				if(!title){
					tips = '信息不完整';
				}
				if( tips ){
					$this.triggerHandler('_myTip', [btn, tips]);
					return false;
				}
				btn[0].disabled = true;
			},
			dataType : 'json',
			success:function( data ){
				btn[0].disabled = false;
				if(data['callback']){
					eval( data['callback'] );
					return;
				}
				if( $.isArray(data) && data[0] && data[0].id ){
					$this.triggerHandler('_myTip', [btn, '栏目更新成功']);
					setTimeout(function(){
						parent.$('.column-lujin-cancel').trigger('click');
					}, 2200);
				}
			},
			error:function(){
				$this.triggerHandler('_myTip', [btn, '栏目更新失败']);
				setTimeout(function(){
					parent.$('.column-lujin-cancel').trigger('click');
				}, 2200);
			}
		});
		return false;
	}).on({
		_init : function(){
		 	this.op = $(this).triggerHandler('_options');
			 $(this).triggerHandler('_initC');
			 $(this).triggerHandler('_initInput');
			 $(this).clientPicupload();
		},
		
		_handlers : function(event, name){
            return $.proxy($._data(this).events[name][0].handler, this);
        },
        
        _initC : function(){
        	var $this = $(this);
        	$this.find('.client-index').on({
                'click' : $this.triggerHandler('_handlers', ['_toggleC'])
            });
            $this.find('#childdomain').on({
            	'change' : $this.triggerHandler('_handlers', ['_checkDomain'])
            });
            $this.find('.special-bigpic').on({
            	'click' : $this.triggerHandler('_handlers', ['_triggerFile'])
            });
         	$this.find('#bigFiledata').on({
            	'change' : $this.triggerHandler('_handlers', ['_handlerFile'])
            });
        },
        
        _triggerFile : function(){
        	$('#bigFiledata').trigger('click');
        },
        
        _handlerFile : function( event ){
        	var file = event.currentTarget.files[0],
				reader = new FileReader();
			var imageType=/image.*/;
			if( file ){
				if(!file.type.match(imageType)){
					$(this).triggerHandler('_myTip', [$(this).find('.special-bigpic'), '请上传图片文件']);
					return;
				}
				var self = $(event.currentTarget),
					img = self.prev('.special-bigpic').find('img');
				reader.readAsDataURL(file);
				reader.onload =  function(e){
					var result = e.target.result;
					img.attr('src',result);
					img.hasClass('hide') && img.removeClass('hide');
					img.parent().find('.bigpic-flag').hide();
					img.parent().find('.client_title').addClass('show');
				};
			}
        },
        
        _toggleC : function( event ){
    	 	var target = $(event.currentTarget);
        	target.toggleClass('client_logo_click');
			$(this).find('.client-list').slideToggle();
        },
        
        _checkDomain : function( event ){
        	var target = $(event.currentTarget);
			var info = {
				column_id : $('#column_id').val(),
				weburl : $('#childdomain_suffix').val(),
				sub_weburl : target.val(),
				column_dir : $('#site_dir').val() || ''
			}
			if( info.weburl && info.sub_weburl){
				var url= "./run.php?mid=" + gMid + "&a=check_domain";
		    	$.ajax({
					type:'get',
					url:url,
					data: info,
					dataType:'Json',
					success:function(msg){
						if( msg != 1 ){
							$this.triggerHandler('_myTip', [target, '该域名,子域名已存在']);
						}
					},
					error:function(){
						
					}
				})
			}
        },
        
        _myTip : function( event, dom, str, left ){
			dom.myTip({
				string : str,
				delay: 2000,
				dtop : 5,
				dleft : left || 80,
				width : 'auto',
				padding: 10
			});
		},
        
        _initInput : function(){
        	var $this = $(this);
        	$this.find('input[name="is_outlink"]').on({
        		'click' : $this.triggerHandler('_handlers', ['_toggleOutlink'])
        	});
        	$this.find('#col_con_maketype').on({
        		'change' : $this.triggerHandler('_handlers', ['_changeMaketype'])
        	});
        	$this.find('#maketype').on({
        		'change' : $this.triggerHandler('_handlers', ['_changetype'])
        	})
        },
        
        _toggleOutlink : function( event ){
        	var target = $(event.currentTarget),
        		outlink = target.closest('.form_ul_div').data('isoutlink');
        	if( target.val() == outlink ){
        		return;
        	}
        	$('.lm').add('.sy').toggle();
        	target.closest('.form_ul_div').data('isoutlink', target.val());
        },
        
        _changeMaketype : function( event ){
        	var target = $(event.currentTarget),
        		col_maketype = target.closest('.form_ul_div').data('maketype');
        	if( target.val() == col_maketype ){
        		return;
        	}
        	$('.cf').add('.ffr, .ff').toggle();
        	target.closest('.form_ul_div').data('maketype', target.val());
        },
        
        _changetype : function( event ){
        	var target = $(event.currentTarget),
        		value = target.val();
    		$('#suffix').get(0).selectedIndex = (value == '2' ? 0 : 1);
        },
	}).triggerHandler('_init');
});
