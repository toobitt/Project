$(function(){
	(function($){
		$.widget('epaper.epaper_list',{
			options : {
				'headPic' : '.info-item .photo',
				'headPicBtn' : '.info-item.head input',
				'saveBtn' : '.saveBtn',
				'epaperInfo' : '.epaperInfo',
				'reaudit' : '.reaudit',
				'batback' : '.batback',
				'bataudit' : '.bataudit',
				'checkall' : '.checkAll',
				'batdelete': '.batdelete',
				'current' : 'current',
				'm2o-check' : '.m2o-check',
				'current' : 'current',
				'm2o-each' : '.m2o-each',
				'initdate' : '.initdate',
				'checkAll' : '.checkAll',
				'm2o-check' : '.m2o-check',
				'm2o-flex-center' : '.m2o-flex-center',
				'result-tip' :'.result-tip',
				'list' : '.list',
				'li' : '.type_show li',
				'top-loading' : '#top-loading',
				'initial_stage' : '.initial_stage'
			},
			
			_create : function(){
				this.status = ['待审核','已审核','已打回'];
				this.status_color = ['#8ea8c8','#17b202','#f8a6a6'];  
			},
			
			_init : function(){
				var handlers = {},
					op = this.options;
				handlers['click' + op['headPic'] ] = '_changeHeadPic';
				handlers['change' + op['headPicBtn'] ] = '_addPic';
//				handlers['click' + op['reaudit'] ] = '_auditPlay';
				handlers['click' + op['batback'] ] = '_batback';
				handlers['click' + op['checkall'] ] = '_checkall';
				handlers['click' + op['bataudit'] ] = '_auditall';
				handlers['click' + op['batdelete'] ] = '_batdelete';
				handlers['click' + op['m2o-check'] ] = '_mcheck';
				handlers['click' + op['list'] ] = '_downlist';
				handlers['click' + op['li'] ] = '_li';
				this._on(handlers);
				this._submitForm();
				this._removeonmouse();
			},
			
			_removeonmouse : function(){
				var op = this.options;
				$(op['list']).removeAttr("onmouseover").removeAttr("onmouseout").removeAttr("onmousemove");
			},
			
			_downlist : function(){
				$('.type_show').toggle();
			},
			
			_li : function(){
				$('.type_show').toggle();
			},
			
			_changeHeadPic : function(){
				var op = this.options,
					uploadHead = this.element.find( op['headPicBtn'] );
				$(uploadHead).click();
			},
			
			_addPic:function( event ){
				var _this = this,
				    self = event.currentTarget;
				var file=self.files;
				_this.handleattachFiles(file);
			},
			
			handleattachFiles: function(files){
				var widget = this.element,
					op = this.options;
				for(var i=0;i<files.length;i++){
					var file=files[i];
					var imageType=/image.*/;
					if(!file.type.match(imageType)){
						alert("请上传图片文件");
						continue;
					}
					var reader=new FileReader();
					reader.onload=function(e){
						imgData=e.target.result;
						var box=$( '.head' );
						var img = box.find('img');
						!img[0] && (img = $('<img/>').appendTo(box));
						img.attr('src', imgData);
					}
					reader.readAsDataURL(file);
				}
			},
			
			_submitForm : function(){
				var op = this.options,
					_this = this,
					widget = this.element.find(op['epaperInfo']);
				widget.submit(function(){
					$(this).ajaxSubmit({
						beforeSubmit : function(){
							$(op['top-loading']).show();
							var obj = $(op['initial_stage']);
							var txt = obj.val();
							if($.trim($('.name').val())=='' || $.trim(txt)=='' || $.trim($('input[name="init_time"]').val())=='' || $('.type label').text()=='选择类型'){
								var objc=$(op['result-tip']);
								var tip="前四项必填";
								_this._ajaxTip(objc, tip);
								$(op['top-loading']).hide();
								return false;
							} 
						
							if(isNaN(txt)){
								var obje=$(op['result-tip']);
								var tip="初始期数必须数字";
								_this._ajaxTip(obje, tip);
								$(op['top-loading']).hide();
								obj.val('').focus();
								return false;   
							}
						},
						dataType : 'json',
						success : function(data){
							var data = data[0]; 
							if(data == 1){
								$(op['top-loading']).hide();
								var obj=$(op['result-tip']);
								var tip="该名称已存在";
								_this._ajaxTip(obj, tip);
								$('.name').val('').focus();
							}else{
							var obj=$(op['result-tip']);
							var tip="保存成功";
							_this._ajaxTip(obj, tip);
							$(op['top-loading']).hide();
							$('input[name="old_name"]').val($.trim($('.name').val()));
							}
						},
						error : function(){
							var obj=$(op['result-tip']);
							var tip="保存失败";
							_this._ajaxTip(obj, tip);
						},
					});
					return false;
				});	
			},
			
			_ajaxTip : function(obj,tip){
				obj.html(tip).css({'opacity':1,'z-index':100001});
				setTimeout(function(){
					obj.css({'opacity':0,'z-index':-1});
				},2000);
			},
			
			_batback : function(){
				var op = this.options,
			//	item = this.element.find( op['m2o-each'] + '.current' );
				item = this.element.find( op['m2o-each'] ).filter('.current');
				var ids = item.map(function(){
					return $(this).attr('_id');
				}).get().join(',');
				if(ids){
					var method = function(){
						var url = './run.php?mid=' + gMid + '&a=audit&ajax=1';
						var data={
								id : ids,
								audit : 1
						};
						$.getJSON(url,data,function( json ){
							if( json['callback'] ){
								eval( json['callback'] );
								return;
							}
							item.find('.reaudit')
								.text('已打回')
								.css({'color':'#f8a6a6'})
							    .attr('_status',2);
						});
					}
					this._remind( '您确认批量打回选中记录吗？', '打回提醒' , method );	  
				}else{
					this._remind( '请选择打回内容', '打回提醒' );	  
				}		   
			},
			
			_auditall : function(){
				var op = this.options,
				item = this.element.find( op['m2o-each'] ).filter('.current');
				var ids = item.map(function(){
					return $(this).attr('_id');
				}).get().join(',');
				if(ids){
					var method = function(){
						var url = './run.php?mid=' + gMid + '&a=audit&ajax=1';
						var data={
								id : ids,
								audit : 2
						};
						$.getJSON(url,data,function( json ){
							if( json['callback'] ){
								eval( json['callback'] );
								return;
							}
							item.find('.reaudit')
								.text('已审核')
								.css({'color':'#17b202'})
								.attr('_status',1);
						});
					}
					this._remind( '您确认批量审核选中记录吗？', '审核提醒' , method );	
					}else{
						this._remind('请选择审核的内容','审核提醒');
					}
			},
			
			_auditPlay : function(event){
				var self = $(event.currentTarget);
				var obj = self.closest('div');
				var _this = this;
				var url = './run.php?mid=' + gMid + '&a=audit&ajax=1';
				var data = {
						id : self.attr("_id"),
						audit : self.attr("_status")
				}; 
				$.post( url,data,function( data ){ 
					var data = data[0];	
					status = data['status'],
					status_text = _this.status[status],
					status_color = _this.status_color[status];	
					self.text( status_text )
						.css({'color' : status_color })
						.attr('_status',status);
				},'json');
			},
			
			_checkall : function(){ 
				var op = this.options;
				if($(op['checkAll']).is(':checked')){
					$(op['m2o-check']).attr('checked',true);
				    $(op['m2o-flex-center']).addClass( op['current'] );
				}else{
					$(op['m2o-check']).attr('checked',false);
					$(op['m2o-flex-center']).removeClass( op['current'] );
				}
			},
			
			_mcheck : function( event ){
				var op = this.options,
					self = $(event.currentTarget),
					item = self.closest( op['m2o-each'] );
				var isSelected = item.find('input').prop('checked');
				item[(isSelected ? 'add': 'remove') + 'Class' ]( op['current'] );	
			},
			
			_batdelete : function(){ 
				var op = this.options,
					item = this.element.find( op['m2o-each'] ).filter('.current');
				var ids = item.map(function(){
					return $(this).attr('_id');
				}).get().join(',');
				var data = {id : ids};
				if(ids){
					var method = function(){
						var url = './run.php?mid=' + gMid + '&a=delete&ajax=1';
						$.ajax({
							type : 'POST',
							url : url,
							data : data,
							dataType : 'json',
							success : function(data){
								if( data['callback'] ){
									eval( data['callback'] );
									return;
								}
								var data = data[0];
								if(data){
									if(data == 0){
										alert("请先删除部分期刊下的新闻");
									}
									if(data == -1){
										alert("您没有权限管理");
									}
									if(data == 'success'){
										item.remove();
									}
								}
							 }
						});
					}
					this._remind( '是否要删除此内容?', '删除提醒' , method );
				}else{
					this._remind( '请选择要删除的内容?', '删除提醒'  );	
				}
			},
			
			_remind : function( title , message , method ){
				jConfirm( title, message , function(result){
					if( result ){
						method();
					}else{}
				});
			},
		});
	})($);
	$('.m2o-form').epaper_list();
	$('body').on('click', '.save-button', function(){
		var target = $(this),
			initDateInp = $('input[name="init_time"]'),
			val = initDateInp.val(),
			reg = /^(\d{4})-(\d{1,2})-(\d{1,2})$/;
		var able = reg.test(val);
		var errorStr = '';
		if( !able ){
			errorStr = '初始日期不符合规范，请重新选择'
		}else{
			var cacheArr = val.split('-');
			for( var i=0;i<cacheArr.length;i++ ){
				cacheArr[i] = parseInt( cacheArr[i] );
			}
			if( cacheArr[1] < 1 || cacheArr[1] > 12 ){
				errorStr = '月份应在1-12之间';
			}
			if( cacheArr[2] < 1 || cacheArr[2] > 31 ){
				errorStr = '日期应在1-31之间';
			}
		}
		if( errorStr.length ){
			initDateInp.myTip({
				string : errorStr,
				width : 200,
				delay : '2000',
				color : '#ee8176'
			});
			var time = setTimeout(function(){
				initDateInp.val('').focus();
			},2300);
			return false;
		}
	});
});
$(function(){
	var datePicker = $(top.frames['mainwin'].document.body).find('#searchform .date-picker');
	datePicker.removeClass('hasDatepicker').hg_datepicker();
});
				   