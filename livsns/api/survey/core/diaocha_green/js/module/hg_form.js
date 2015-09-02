(function(){
	var platform = navigator.userAgent.toLowerCase(),
		isIosDevice = (/iphone|ipod|ipad/gi).test(platform),
		isAndroid = (/android/gi).test(platform);
	var defaultOpt = {
		form : $('.form-block'),
		errorMsg : {
			'access_token_no' : '请先登录',
			'form_error' : '请传入form表单'
		},
		viewBigTmpl : '<div class="backdrop transition" id="imgbackdrop">' +
				'<div class="backinner m2o-flex m2o-flex-center"><span class="btn btn-close">×</span><img /></div>' + 
				'</div>'
 	}
	function hg_Submit( options ){
		this.opt = $.extend({}, defaultOpt, options);
	}
	$.extend( hg_Submit.prototype, {
		constructor : hg_Submit,
		
		init : function(){
			var opt = this.opt;
			if( !opt.form.is('form') ){
				opt.toast.call( this, opt.errorMsg.form_error );
			}
			this.formdata = new FormData();
			this.imgBox = $( this.opt.viewBigTmpl ).appendTo( 'body' );
			this.bindEvent();
		},
		
		bindEvent : function(){
			var _this = this,
				opt = this.opt;
			opt.form					//上传图片
				.on('change', '.cell_file input[type="file"]', $.proxy(this.upload, this))
				.on( 'click', '.file-set .view', $.proxy(this.viewImg, this))
				.on( 'click', '.file-set .del', $.proxy(this.delImg, this))
			
			this.initVary();
			this.address();
			
			if( opt.submitBtn && opt.submitBtn.length ){		//提交表单
				opt.submitBtn.on('click', function(){
					_this.ajaxform( $(this) );
				});
			}
		},
		
		initVary : function(){
			if( isAndroid ){
				this.opt.form.find('.file-set .view').hide();
			}
		},
		
		/*form submit*/
		ajaxform : function( dom ){
			var opt = this.opt,
				strTip = this.beforeAjax();
			if( strTip ){
				opt.toast && opt.toast.call( this, strTip );
				return false;
			}
			utils.spinner && utils.spinner.show( dom );
			$.ajax({
				url : opt.submitUrl,
				data : this.formdata,
				cache : true,
	        	timeout : 60000,
	        	processData : false,
                contentType : false,
	        	type : 'post',
				dataType : 'json',
				success : function( data ){
					utils.spinner && utils.spinner.close();
					opt.submitBack( data );
					
				},
				error : function(){
	        		opt.toast && opt.toast.call( this, '接口访问错误，请稍候再试' );
	        		utils.spinner && utils.spinner.close();
	        	}
			});
		},
		
		beforeAjax : function(){
			var _this = this,
				opt = this.opt;
			var strTip = '';
			var serialize = opt.form.serializeArray();
			$.each(serialize, function(kk, vv){
				// if( !vv.value && opt.errorMsg[ vv.name + '_no' ] ){
					// strTip = opt.errorMsg[ vv.name + '_no' ];
					// return false;
				// }
				_this.formdata.append(vv.name, vv.value);
			});
			return strTip;
		},
		
		/*view img*/
		viewImg : function( event ){
			var imgBox = this.imgBox;
			imgBox.addClass('in');
			if( !imgBox.hasClass('in') ){
				return false;
			}
			imgBox.on('click', '.btn-close', function( event ){
				imgBox.removeClass('in');
			});
		},

		delImg : function( event ){
			var self = $(event.currentTarget),
				target = self.closest('.liv_file');
			target.find('.file-set .view').attr('attr', '');
			target.find('.file-info .name').html( '' );
			this.showfile( target, false );
		},
		
		/*upload file*/
		upload : function( e ){
			var _this = this,
				opt = this.opt;
			var self = $( e.currentTarget ),
				target = self.closest('.liv_file');
			var file = self[0].files[0],
				name = self[0].name,
				reader = new FileReader();
			reader.onload=function(event){
				var imgData = event.target.result;
				_this.showfile( target, true );
				_this.formdata.append(name, file);
				target.find('.file-info .name').html( file.name );
				if( $('#imgbackdrop').length ){
					$('#imgbackdrop').find('img')[0].src = imgData;
				}
			}
	    	reader.readAsDataURL( file );
		},
		
		showfile : function( target, type ){
			target.find('.file-drop')[(type ? 'add' : 'remove') + 'Class']('show-drop');
			target.find('.cell_file .btn').html( (type ? '重新' : '') + '上传');
		},
		
		address : function(){
			this.opt.form.find('.liv_address').address({
				together  : false,
				provId : '#A_prov',
				cityId : '#A_city',
				areaId : '#A_area'
			});
		}
		
	} );
	$.hg_submit = function( options ){
		if( !window.hg_submit ){
			window.hg_submit = new hg_Submit( options );
		}
		hg_submit.init();
	}
})();