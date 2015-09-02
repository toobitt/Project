$(function(){
	(function($){
		$.widget('epaper.epaper_list',{
			options : {
				'epaper-list' : '.epaper-list',
				'epaper-each' : '.epaper-each',
				'current' : 'current',
				'reaudit' : '.reaudit',
				'del' : '.del',
				'addpapers' : '.add-papers',
				'editnews' : '.edit-news',
				'editlink' : '.edit-link',				
				'batdelete' : '.batdelete',
				'checkall' : '#checkAll',
				'batback' : '.batback',
				'bataudit' : '.bataudit',
				'result_tip' : '.result-tip',
				'add-show' : 'add-show',
				'epaper-img' : '.epaper-img',
				'current' : 'current',
				'dialog' : '.dialog',
				'display_type_show' :'#display_type_show',
			},
			
			_create : function(){
				this.status = ['待审核','已审核','已打回'];
				this.status_color = ['#8ea8c8','#17b202','#f8a6a6'];  
			},
			
			_init : function(){
				var _this = this,
					op = this.options,
					handlers = {};
				handlers['click' + op['bataudit'] ] = '_auditall';
				handlers['click' + op['reaudit'] ] = '_auditPlay';
				handlers['click' + op['batback'] ] = '_batback';
				handlers['click' + op['del'] ] = '_delPlay';
				handlers['click' + op['batdelete'] ] = '_delall';
				handlers['click' + op['epaper-each'] ] = '_checkPlay';
				handlers['click' + op['checkall'] ] = '_checkall';
				handlers['click' + op['addpapers'] ] = '_addpapers';
				handlers['click' + op['deletepic'] ] = '_deletepic';
				this._on(handlers);
			},
			
			_batback : function(){
				var method = function(){
					var object = $(".epaper-list li");
					var ids =object.map(function(){
						return $(this).attr("_id");
					}).get().join(",");
					var url = './run.php?mid=' + gMid + '&a=audit&ajax=1';
					$.globalAjax( window, function(){
						return $.getJSON(url,{id : ids},function( data ){
							if( data['callback'] ){
								eval( data['callback'] );
							}else{
								object.find('.reaudit')
								  .text('已打回')
								  .css({'color':'#f8a6a6'})
								  .attr('_status',2);
							}
						});
					});
				}
				this._remind( '您确认批量打回选中记录吗？', '打回提醒' , method );	
			},
			
			_auditall : function(){
				var method = function(){
					var item = $(".epaper-list li");
					var ids =item.map(function(){
						return $(this).attr("_id");
					}).get().join(",");
					var url = './run.php?mid=' + gMid + '&a=audit&ajax=1';
					$.globalAjax( window, function(){
						return $.getJSON(url,{id : ids},function( data ){
							if( data['callback'] ){
								eval( data['callback'] );
								return;
							}else{
								item.find('.reaudit')
								.text('已审核')
								.css({'color':'#17b202'})
								.attr('_status',1);
							}
						});
					});
				}
				this._remind( '您确认批量审核选中记录吗？', '审核提醒' , method );	
			},
			
			_auditPlay : function(event){
				var self = $(event.currentTarget);
				var obj = self.closest('li');
				var _this = this;
				var url = './run.php?mid=' + gMid + '&a=audit&ajax=1';
				var data = {
		        		id : self.attr("_id"),
				};
				$.globalAjax( self, function(){
					return $.getJSON( url,data,function( data ){
						if( data['callback'] ){
							eval( data['callback'] );
							return;
						}else{
							var data = data[0];	
							status = data['status'],
							status_text = _this.status[status],
							status_color = _this.status_color[status];	
							self.text( status_text )
								.css({'color' : status_color })
								.attr('_status',status);
						}
					});
				} );
			},
			
			_checkPlay : function(){
			},
			
			_delPlay : function(event){ 
				var method = function(){
					var self = $(event.currentTarget);
					var obj = self.closest('li' );
					var _this = this;
					var url = './run.php?mid=' + gMid + '&a=delete';
					var data = {
							id : obj.attr("_id")		 
					}; 
					$.globalAjax( window, function(){
						return $.ajax({
							type : 'POST',
							url : url,
							data : data,
							dataType : 'json',
							success : function(){
								obj.remove();
							},
							error : function(){
								alert("请先删除该报刊下的期刊");
							},	 
						});
					} );
				}
				this._remind( '确定要删除么?', '删除提醒' , method, $(event.currentTarget) );
			},
			
			_remind : function( title , message , method, posTarget ){
				if( posTarget ){
					jConfirm( title, message , function(result){
						if( result ){
							method();
						}
					}).position(posTarget);
				}else{
					jConfirm( title, message , function(result){
						if( result ){
							method();
						}
					});
				}
			},
			
			_delall : function(){
				var method = function(){
					var object = $(".epaper-list li");
					var ids =  object.map(function(){
						return $(this).attr("_id");
					}).get().join(",");
					var _this = this;
					var url = './run.php?mid=' + gMid + '&a=delete';
					$.post(url,{id : ids},function(){
						object.remove();
					})
				}
				this._remind( '是否要删除此内容?', '删除提醒' , method );
			},
			
			_addpapers : function(){
				var op = this.options,
					top = $(op['addpapers']).offset().top;
				if(top<405){
					$(op['dialog']).addClass( op['add-show'] ).css({'top': 20+'%'});
				}else{
					$(op['dialog']).addClass( op['add-show'] ).css({'top':top-250 + 'px'});
				}
				$(op['display_type_show']).text("--选择类型--");
			},
			
			_checkall : function(){ 
				var op = this.options,
				    item = $(op['epaper-img']),
				    isSelected = $('#checkAll').prop('checked');
				item[(isSelected ? 'add': 'remove') + 'Class' ]( op['current'] );
			},
		});
		
		$.widget('epaper.epaper_add',{
			options : {
				'close-btn' : '.pop-close-button2',
				'pic' : '.pic',
				'photo-file' : '.photo-file',
				'news-form' : '#news-from',
				'dialog-tpl' :'#dialog-tpl',
				'add-papers':'.add-papers',
				'list' : '.list',
				'li' : '.type_show li',
				'type_show' : '.type_show',
				'dialog' : '.dialog',
				'initial_stage' : '.initial_stage',
				'epaper-list' : '.epaper-list li',
				'input-item' : '.input-item',
				'pic img' : '.pic img',
				'add-pic' : '.add-pic',
				'photo-file' : '.photo-file',
				'add-pic' : '.add-pic',
				'news-form' : '#news-form',
				'add-show' : 'add-show'
			},
			
			_create : function(){
			},
			
			_init : function(){
				var _this = this,
					op = this.options,
					handlers = {};
				handlers['click' + op['close-btn'] ] = '_closebtn';
				handlers['click' + op['pic'] ] = '_pic';
				handlers['change' + op['photo-file'] ] = '_addpic';
				handlers['click' + op['list'] ] = '_list';
				handlers['click' + op['li'] ] = '_li';
				this._on(handlers);
				this._on({
					'click .pop-save-button' : '_submitForm'
				});
				this._submit();
				this._removeonmouse();
			},
			
			_removeonmouse : function(){
				var op = this.options;
				$(op['list']).removeAttr("onmouseover").removeAttr("onmouseout").removeAttr("onmousemove");
			},
			
			_list : function(){
				var _this = this;
				_this._tog();
			},
			
			_li : function(){
				var _this = this;
				_this._tog();
			},
			
			_tog : function(){
				var op = this.options;
				$(op['type_show']).toggle();
			},
			
			_closebtn : function(){   
				var op = this.options;
				var _this = this;
				$(op['type_show']).hide();
				_this._remove();
			},
			
			_remove : function(){
				var op = this.options;
				$(op['input-item']).val('');
				$(op['pic img']).remove();
				$(op['add-pic']).show();
				$(op['dialog']).removeClass(op['add-show']).css({'top':-400 + 'px'});
			},
			
			_pic : function(){ 
				var op = this.options;
				$(op['photo-file']).trigger('click');	
			},
			
			_addpic : function( event ){
				var op = this.options;
				$(op['topic-pic']).trigger('click');
				var _this = this,
				self = event.currentTarget;
				var file=self.files;
				_this._handleattachFiles(file);
			},
			
			_handleattachFiles: function(files){
				var widget = this.element,
					_this = this,
				    op = this.options;
				for(var i=0;i<files.length;i++){
					var file=files[i];
					var imageType=/image.*/;
					if(!file.type.match(imageType)){
						alert("请上传图片文件");
						continue;
					}
					var reader=new FileReader();
					reader.onload=function(event){
						imgData=event.target.result;
						var box=$( op['pic'] );
						var img = box.find('img');
					    !img[0] && (img = $('<img style="width:100px;height:100px;"/>').appendTo(box));
					    img.attr('src', imgData);
					    _this.src = box.find('img').attr('src');
						$(op['add-pic']).hide();
					}
					reader.readAsDataURL(file);
				}
			},
			_submitForm : function( event ){
				var target = $( event.currentTarget ),
					form = target.closest('form'),
					initPeriodHid = form.find('[name="period"]'),
					initPeriodVal = initPeriodHid.val().trim();
				var requiredNames = ['title', 'type', 'period', 'date'];
				var requiredTips = function( pos, str ){
					pos.myTip({
						string : str,
						delay : 2000,
						color : '#ee8176'
					});
					event.preventDefault();
				};
				for( var i=0,len=requiredNames.length; i<len; i++ ){
					var me = form.find('[name="'+ requiredNames[i] +'"]'),
						parent = me.closest('.form-item'),
						name = parent.find('.item-name'),
						subName = name.text().substring(0, name.text().length-1);
					if( parent.find('.down_list').length ){	//下拉类型
						if( me.val() == -1 ){
							requiredTips( parent, '请选择'+subName );
							return;
						}
					}else if( parent.find('.date-picker').length ){//日期选择器
						if( !me.val().trim() ){
							requiredTips( parent, '请选择'+subName );
							return;
						}
					}else{									//普通输入框
						if( !me.val().trim() ){
							requiredTips( parent, '请填写'+subName );
							return;
						}
					}
				}
				if(!(initPeriodVal > 0 && parseInt(initPeriodVal) == initPeriodVal)){
					event.preventDefault();
					initPeriodHid.closest('.form-item').myTip({
						string : '初始期数应为正整数，请重新填写',
						delay : 3000,
						color : '#ee8176',
						width : 200
					});
					var timer = setTimeout(function(){
						initPeriodHid.focus();
					},1000);
				}
			},
			_submit : function(){
				var op = this.options,
					_this = this,
					sform = this.element.find(op['news-form']);
					sform.submit(function(){
						var load = $.globalLoad( _this.element );
						sform.ajaxSubmit({
							dataType : 'json',
							success:function( data ){
								var data = data[0]; 
								if(data == 1){
									sform.myTip({
										string : '该报刊名称已存在，请重新填写',
										delay : 3000,
										color : '#ee8176',
										width : 200
									});
								}else{
									var info = $.extend({}, data, {
										picture : _this.src,
										value : $.globalselect[data.sort_id]
									});
									$( op['dialog-tpl'] ).tmpl( info ).insertAfter('.add-papers');
									_this._remove();
									load();
								} 
							},
						});
						return false;
					});
			},	
		});
})($);
		$('.epaper-wrap').epaper_list();
		$('.dialog').epaper_add();	     
});