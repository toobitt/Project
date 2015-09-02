$(function(){
	(function($){
		$.widget('feedback.feedback_form',{
			options : {
				delUrl : '',
				moveUrl : '',
				getUrl : '',
				one_text_tpl : '',
				multiline_text_tpl : '',
				more_choice_tpl : '',
				select_tpl : '',
				upload_tpl : '',
				divide_box_tpl : '',
				address_box_tpl :'',
				time_box_tpl : '',
				date_box_tpl : '',
				defaultOptions :'',    				/*默认配置*/
				standarddata : '',					/*组织数据*/
				standardname : '',
				fixeddata : '',
				fixedname : ''								
			},
			
			_create : function(){
			},
			
			_init : function(){
				this._on({
					'click .indexpic' : '_setIndexPic',
					'change .upload-file' :'_changefile',
					'click .detail' : '_detail',
					'click .nav li' : '_nav',
					'click .common-use li' : '_adduse',
					'click .manage' : '_managetag',
					'click .common-widget-hide' : '_widgethide',
					'click .other-widget-delete' : '_widgetdel',
					'click .widget-show-list a' : '_move',
					'click .list li' : '_add',
					'click .m2o-m .delete' : '_delete',
					'click .widget-show-list p' : '_deluse',
					'click .m2o-r .add' : '_addoption',
					'click .m2o-r .delete' : '_deloption',
					'blur .is-number' : '_isnum',
					'click .m2o-r .save' : '_save',
					'click .cancel' : '_cancel',
					'click .save_as' : '_saveas',
					'click .other_save' : '_othersave',
					'click .limit-box' : '_limit',
					'click .member' : '_member'
				});
				$.globalData.id && this._initDom();
				this._sortable();
				this._submit();
				this.element.data('change' , true);
			},
			
			_initDom : function(){
				var data = $.globalData['forms'],
					_this = this;
				$.each(data , function(key , value){
					_this._switch(value);
				});
				this._cover();
				this._organizedata();
				this._divideline();/*分割线页码*/
			},
			
			_limit : function(event){
				var self = $(event.currentTarget);
				var obj = self.parent().find('.limit-hour');
				if(self.prop('checked')){
					self.val(1);
					obj.show();
				}else{ 
					self.val(0);
					obj.hide();
				}
				
			},
			
			_setIndexPic:function(event){
				var self=$(event.currentTarget),
				    img=self.find('img'),
				    _indexFile=this.element.find('.upload-file'),
				    flag=true;
				var flagobj=self.find('.indexpic-suoyin');
				_indexFile.trigger('click');
				_indexFile.data({imgk:img,flagk:flag,suoyink:flagobj})
			},
			
			_changefile:function(event,img,flag,flagobj){
				var _this=this,
				    self=event.currentTarget,
					file=self.files;
				var data=$(self).data(),
				    img=data.imgk,
				    flag=data.flagk,
				    flagobj=data.suoyink;
					_this._handleFiles(file,img,flag,flagobj);
			},
			_handleFiles:function(files,img,flag,flagobj){
				var _this=this,
				    imgData;
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
						img.attr('src',imgData);
						img.hasClass('hide') && img.removeClass('hide');
						if(flag){
							flagobj.addClass('indexpic-suoyin-current');
						}
					}
					reader.readAsDataURL(file);
				}
				return imgData;
			},
			
			_member : function(){
				var obj = this.element.find('input[name="member"]'),
					mf = this.element.find('.member_field');
				if(obj.prop('checked')){
					mf.show();
				}else{
					mf.hide();
				}
			},
			_switch : function(value){
				var type = value.form_type,
					op = this.options,
					obj = this.element.find('.feedback-info');
				switch(type){
					case 1:
						$.tmpl(op.one_text_tpl , value).prependTo(obj);
						break;
					case 2:
						value.type == 'standard' ? $.tmpl(op.multiline_text_tpl , value).prependTo(obj) : $.tmpl(op.one_text_tpl , value).prependTo(obj);
						break;
					case 3:
						value.type == 'standard' ? $.tmpl(op.more_choice_tpl , value).prependTo(obj) : $.tmpl(op.one_text_tpl , value).prependTo(obj);
						break;
					case 4:
						value.type == 'standard' ? $.tmpl(op.select_tpl , value).prependTo(obj) : $.tmpl(op.address_box_tpl , value).prependTo(obj);
						break;
					case 5:
						value.type == 'standard' ? $.tmpl(op.upload_tpl , value).prependTo(obj) : $.tmpl(op.date_box_tpl , value).prependTo(obj);
						break;
					case 6:
						value.type == 'standard' ? $.tmpl(op.divide_box_tpl , value).prependTo(obj) : $.tmpl(op.time_box_tpl , value).prependTo(obj);
						break;
				}
			},
			
			_cover : function(){ /*表单发布使用的覆盖层*/
				var height = $(document).height();
				this.element.find('.cover').css({'height':height});
			},
			
			_divideline : function(){
				this.element.find('.spilter-line').not('.pagehide').each(function(key ,value){
					$(this).find('.index').text(key+1);
				})
			},
			
			/*拖动*/
			_sortable : function(){
				var obj = this.element.find('.feedback-info'),
					_this = this;
				obj.sortable({
			        cursor: "move",
			        scrollSpeed: 100,
			        delay : 200,
			        axis : 'y',
			        placeholder: "ui-state-highlight",
			        start : function(event , ui){
			        	ui.item.addClass('color');
			        },
			        stop : function(event , ui){
			        	ui.item.removeClass('color');
			        	_this._divideline()
			        }
				}).disableSelection();
			},
			
			/*编辑组件*/
			_detail : function(event){
				var self = $(event.currentTarget),
					tpl = self.attr('_tpl'),
					obj = this.element.find('.attachinfo:eq(1)'),
					item = $('#'+ tpl);
				self.addClass('new').siblings().removeClass('new');
				this.element.find('.nav li:eq(1)').trigger('click');
				data = this._getdata(self);
				item.tmpl(data).appendTo(obj.empty());
			},
			
			/*详情切换到编辑整理数据*/
			_getdata : function(obj){
				data = {},
				data.name = obj.find('.title').text(),
				data.brief =obj.find('.detail-brief').text(),
				data.width = obj.attr('_width'),
				data.height = obj.attr('_height'),
				data.char_num = obj.attr('_num'),
				data.required = obj.attr('_required'),
				data.member = obj.attr('_member'),
				data.field = obj.attr('_field'),
				data.common = obj.attr('_common'),
				data.back = obj.attr('_back'),
				data.options = obj.find('.select .option').map(function(){
					return $(this).text();
				}).get(),
				data.cor = obj.attr('_cor'),
				data.limit = obj.attr('_limit'),
				data.op = obj.attr('_op'),
				data.spilter = obj.attr('_spilter'),
				data.province = obj.attr('_province'),
				data.city = obj.attr('_city'),
				data.county = obj.attr('_county'),
				data.detail = obj.attr('_detail'),
				data.hour = obj.attr('_hour'),
				data.min = obj.attr('_min'),
				data.second = obj.attr('_second'),
				data.start = obj.attr('_start'),
				data.end = obj.attr('_end'),
				data.spilter = obj.attr('_spilter');
				data.sign = obj.attr('_sign');
				data.type = obj.attr('_type');
				data.unique = obj.attr('_unique');
				if(obj.attr('_type') != 6 || obj.attr('_sign') != 'standard')
				{
					address = obj.attr('_field').split(',');
					data.field_addr = new Array();
					$.each(address,function(key,val){
						if(key%2 == 1){
							data.field_addr[address[key-1]] = val
							}
					})
				}
				return data;
			},
			
			/*导航切换*/
			_nav : function(event){
				var self = $(event.currentTarget),
					index = self.index(),
					obj = this.element.find('.attachinfo:eq('+ index +')'),
					item = this.element.find('.new')[0],
					det = this.element.find('.detail')[0];
				if(!item && index == 1 ){
					if(det){
						this.element.find('.detail:eq(0)').trigger('click');/*如果没有选中组件时，直接点击编辑组件，默认第一条组件触发click*/
					}else{
						this._myTip(self , '请先添加组件'); /*如果没有组件提示*/
						return;
					}
				}else{
					self.addClass('selected').siblings().removeClass('selected');
					obj.show().siblings('.attachinfo').hide();
				}
			},
			
			/*点击常用组件*/
			_adduse : function(event){
				var self = $(event.currentTarget),
					_this = this,
					data = {
						id : self.attr('_id')
					},
					url = this.options.getUrl;
				this._ajax(self , url, data , function( json ){
					_this._switch(json[0]);
				});
			},
			
			/*常用组件管理*/
			_managetag : function(){
				this.element.find('.cover').show();
				this.element.find('.common-widget').addClass('widget-show');
			},
			
			_widgethide : function(){
				this.element.find('.cover').hide();
				this.element.find('.common-widget').removeClass('widget-show');
			},
			
			/*移动组件*/
			_move : function(event){
				var self = $(event.currentTarget),
					obj = self.closest('li'),
					data = {
						id : obj.attr('_id'),
						is_display : obj.attr('_display')
				 	},
				 	index = this.element.find('.common li').length; /*为了侧边栏顺序*/
				 	if(data.is_display==0){
				 		data.c_id = index+1
				 	}
				 	url = this.options.moveUrl,
				 	item = this.element.find('.common-use li').filter(function(){
						return $(this).attr('_id') == data.id;
					});
				this._ajax(obj , url, data , function( json ){
					var clone = obj.clone();
					obj.remove();
					if(data.is_display == 1){  															/*组件管理框与侧边栏同步*/
						clone.attr('_display', 0).prependTo('.other'); 									/*点击常用域内的组件，显示到下方其他组件区域*/
						item.remove();																	/*同时侧边栏内相同组件去除*/
					}else{ 
						clone.clone().attr('_display', 1).appendTo('.common');							/*点击其他区域的组件，显示到上方常用组件区域*/
						clone.attr('_display', 1).appendTo('.common-use');								/*同时添加到侧边栏内*/
					}
				} );
			},
			
			/*请求事件*/
			_ajax : function(obj , url ,data , callback){
				$.globalAjax(self, function(){
			        return $.getJSON(url,data,function(json){
			        	 callback( json );
			            });
			    });
			},
			
			_widgetdel : function(event){
				var item = this.element.find('.other li'),
					self = $(event.currentTarget);
				if(self.data('init')){
					item.removeClass('animate');
					item.find('p').hide();
					self.data('init', false).html('删除');
				}else{
	            	item.addClass('animate');  
	            	item.find('p').show();
	            	self.data('init', true).html('取消');
				}
			},
			
			/*添加标准组件*/
			_add : function(event){
				var self = $(event.currentTarget),
					tpl = self.attr('_tpl'),
					obj = this.element.find('.feedback-info');
					item = $('#'+ tpl),
					style = self.attr('_style'),
					type = self.attr('_type');
				var option = {};
				option.name = self.text();
				option.type = style;
				option.form_type = type;
				if(style=="standard"){
					if(type == 4){	
						option.cor = 0
					}else if(type == 3){
						option.cor = 1
					}
				}	
				if(style=="fixed"){					/*新增时配置地址与时间的默认数据*/
					if(type == 4){
						option.element = {
								0 : {id : 8},
								1 : {id : 9},
								2 : {id : 10},
								3 : {id : 11}
						}
					}else if(type == 6){
						option.element = {
								0 : {id : 1},
								1 : {id : 2},
								2 : {id : 3}
						}
					}
				}
				var options = $.extend( this.options.defaultOptions , option );/*默认数据*/
				item.tmpl(this.options.defaultOptions).prependTo(obj);
				this._divideline();
			},
			
			/*删除组件*/
			_delete : function(event){
				var self = $(event.currentTarget),
					item = self.closest('.detail');
				item.remove();
				this.element.find('.nav li:eq(0)').trigger('click');/*删除某一条数据时,默认从编辑页切换到添加页*/
				this.element.find('.attachinfo:eq(1)').empty(); /*清空添加页*/
			    this._divideline();	
			    event.stopPropagation();
			},
			
			/*常用组件标签删除*/
			_deluse : function(event){
				var self = $(event.currentTarget),
					obj = self.closest('li'),
					data={
						id : obj.attr('_id')
					},
					url = this.options.delUrl;
				
				this._ajax(self , url, data , function( json ){
					obj.remove();
				} );
			},
			
			/*增加选项*/
			_addoption : function(event){
				var self = $(event.currentTarget),
					obj = self.closest('.option');
					clone = obj.clone();
				clone.find('input').val('').end().insertAfter(obj);
			},
			
			/*删除选项*/
			_deloption : function(event){
				var self = $(event.currentTarget),
					obj = self.closest('.option'),
					index = this.element.find('.attachinfo .option').length;
				index == 1 ? this._myTip(self , '至少保留一项') : obj.remove();
			},
			
			_isnum : function(event){
				var self = $(event.currentTarget),
					val = self.val();
				if(isNaN(val) || val<0 || val == ''){
					this._myTip(self , '必须为正整数');
					self.val(0);
				}else if(self.hasClass('num_count')){
					self.val(parseInt(val));
				}else{
					self.val(val);
				}
			},
			
			/*保存编辑*/
			_save : function(event){
				var self = $(event.currentTarget),
					item = this.element.find('.new');
				this._getdatainfo(self , item);	
			},
			
			_getdatainfo : function(self , item){
				var _this = this;
					obj = self.closest('.attachinfo'),
					title = $.trim(obj.find('input[name="widget-title"]').val()),
					brief = $.trim(obj.find('textarea[name="brief"]').val()),
					width = obj.find('input[name="width"]').val(),
					height = obj.find('input[name="height"]').val(),
					limit = obj.find('input[name="limit"]').val(),
					required = obj.find('input[name="required"]').prop('checked'),
					member = obj.find('input[name="member"]').prop('checked'),
					field = obj.find('select[name="member_field"] option').filter(function(){
						return $(this).prop('selected')
					}).val(),
					field_addr = obj.find('input[name="is_field"]').map(function(){
						if($(this).prop('checked')){
							return $(this).val() + ',' +  $(this).parent().find('select option:checked').val()
						}	
					}).get().join(','),
					common = obj.find('input[name="common"]').prop('checked'),
					back = obj.find('input[name="is_name"]').prop('checked'),
					cor = obj.find('input[name="options"]').filter(function(){
						return $(this).prop('checked')
					}).val();
					sel = obj.find('select option').filter(function(){
						return $(this).prop('selected')
					}).val();
					unique = obj.find('input[name="unique"]').prop('checked'),

					op_num = obj.find('input[name="op_num"]').val();
					option = obj.find('.option').map(function(index){
						var oval = $(this).find('input').val();
						if(oval.indexOf("@")>=0){
							_this._myTip(self , '选项不能含@符号');
							obj.find('.option:eq('+ index +')').find('input').val('请填写选项');
							oval = '请填写选项';
						}
						return oval;
					}).get();

					info = ['province','city','county','detail','hour','min','second'];
					$.each(info,function(key , value){
						 var check = obj.find('input[name="'+ info[key] +'"]').prop('checked');
						 var data = obj.find('input[name="'+ info[key] +'"]').val();
						 item.attr('_'+ info[key], check ? data : '');
					});
					line = obj.find('input[name="divideline"]').filter(function(){
						return $(this).prop('checked')
					}).val();
					start = obj.find('input[name="start"]').val();
					end = obj.find('input[name="end"]').val();
					field = item.attr('_sign') == 'fixed' && item.attr('_type') == '4' ? field_addr : field;
				item.find('.title').text(title);
				item.find('.detail-brief').text(brief);
				item.attr('_name' , title);
				item.attr('_brief' , brief);
				item.attr('_width' , width);
				item.attr('_height' , height);
				item.attr('_num' , limit);
				item.find('.num').text(limit);
				item.attr('_required' , required ? 1 : 0);
				item.attr('_member' , member ? 1 : 0);
				item.attr('_field' , member ? field : '');
				item.attr('_common' ,common ? 1 : 0);
				item.attr('_back' ,back ? 1 : 0);
				item.attr('_cor' , cor);
				item.attr('_limit' , sel);
				item.attr('_op' , op_num ? op_num : 0);
				item.attr('_options' , option);
				item.attr('_start' , start);
				item.attr('_end' , end);
				item.attr('_spilter' ,line);
				item.attr('_unique' , unique ? 1 : 0);
				if(obj.find('input[name="widget-title"]')[0]){
					if(!title){
						this._myTip(self , '组件标题不能为空');
						this.element.data('change' , false);
						return;
					}else{
						this.element.data('change' , true);
					}
					if(title.indexOf("@")>=0){
						this._myTip(self , '标题不能含@符号');
						this.element.find('input[name="widget-title"]').val('标题');
						item.find('.title').text('标题');
						return;
					}
				};
				
				if(brief.indexOf("@")>=0){
					this._myTip(self , '描述不能含@符号');
					this.element.find('textarea[name="brief"]').val('描述');
					item.find('.detail-brief').text('描述');
					return;
				}
//				var _this = this;
//				$.each(option,function(key , value){
//					if(value.indexOf("@")>=0){
//						_this._myTip(self , '选项不能含@符号');
//						obj.find('.option:eq('+ key +')').find('input').val('请填写选项');
//						return;
//					}
//				})
				
				if(obj.find('input[name="width"]')[0]){  /*宽高为空，默认*/
					if(!width && !height){
						this._myTip(self , '宽高均为空,设为默认');
						obj.find('input[name="width"]').val(350);
						obj.find('input[name="height"]').val(30);
						width = 350;
						height = 30;
					}else if(!width){
						this._myTip(self , '宽为空,设为默认');
						obj.find('input[name="width"]').val(350);
						width = 350;
					}else if(!height){
						this._myTip(self , '高为空,设为默认');
						obj.find('input[name="height"]').val(30);
						height = 30;
					}
					if(width>550 || width <100){
						this._myTip(self , 'W设在100至550间');
						obj.find('input[name="width"]').val(350);
						width = 350;
					}
					if(height>450 || height<30){
						this._myTip(self , 'H设在30至450间');
						obj.find('input[name="height"]').val(30);
						height = 30;
					}
				};
				item.find('.text').css({'width' : width , 'height' : height});
				if(item.attr('_sign') == 'standard'){   /*选择题与下拉题选项设置*/
					if(item.attr('_type') == 3){
						item.find('.select').remove();
						$.each(option , function(key , value){
							$('#choices-tpl').tmpl({option : value}).appendTo(item.find('.option-box'));
						});
						if(cor == 2){
							var cop = obj.find('.op-choose select option').filter(function(){
								return $(this).prop('selected')
							}).text();
							item.find('.choice').html('(多选:'+ cop +' '+ (op_num ? op_num : 0) +'项)');
						}else{
							item.find('.choice').html('(单选)');
						}
					}else if(item.attr('_type') == 4){
						item.find('.option').remove();
						$.each(option , function(key , value){
							item.find('.select').append('<option class="option">'+ value +'</option>');
						})
					}
					if(item.attr('_type') == 6){  /*分割线切换*/
						if(line==1){
							item.addClass('pagehide');
							item.find('.line-divide:eq(1)').css('display' , '-webkit-box').siblings('.line-divide').css('display', 'none');
							this._divideline();/*分割线分页*/
						}else{
							item.removeClass('pagehide');
							item.find('.line-divide:eq(0)').css('display' , '-webkit-box').siblings('.line-divide').css('display', 'none');
							this._divideline();/*分割线分页*/
						}
					}
				};
				required ? item.find('.mark').show() : item.find('.mark').hide();  /*必填*/
				if(item.attr('_sign') == 'fixed' && item.attr('_type') == 4 || 6){    /*地址和时间*/
					var length = obj.find('.r_label').filter(function(){
						return $(this).find('input[type="checkbox"]').prop('checked')
					}).length,
					detail = obj.find('input[name="detail"]').prop('checked');
					item.find('.address').remove();
					for(i=0 ; i<length ; i++){
						item.find('.detail-info').append('<select class="address"><option>请选择</option></select>');
					}
					detail ? item.find('.more-address').show() : item.find('.more-address').hide();
				};
				back ? item.find('.back').show() : item.find('.back').hide(); /*设为回收表单名称*/
			},
			
			/*组织数据*/
			_organizedata : function(){
				var _this = this,
				obj = this.element.find('.detail').filter(function(){
					return $(this).attr('_sign') == 'standard';
				});
				item = this.element.find('.detail').filter(function(){
					return $(this).attr('_sign') == 'fixed';
				});
				this._standarddata(obj);	/*标准组件*/
				this._fixeddata(item);		/*固定组件*/
				this._order();        		/*排序*/
			},
			
			_standarddata : function(obj){
				var op = this.options,
					datainfo = op.standarddata,
					name = op.standardname,
					sign = 'standard';
				for(i=1;i<=6;i++){
					this._eachdata(obj ,datainfo , name , sign , i);
				}
			},
			
			_fixeddata : function(obj){
				var op = this.options,
					datainfo = op.fixeddata,
					name = op.fixedname,
					sign = 'fixed';
				for(i=1;i<=6;i++){
					this._eachdata(obj ,datainfo , name ,sign , i);
				}
			},
			
			/*隐藏域数据*/
			_eachdata : function(obj ,datainfo , name , sign , i){
				var _this = this;
				var item = obj.filter(function(){
					 return $(this).attr('_type') == i
				});
				$.each(name , function(k , v){
					var data = item.map(function(){
						return $(this).attr('_' + datainfo[k]);
					}).get().join('@');
					_this.element.find('input[name="'+ sign +'['+i+']['+name[k]+']').val(data);
				})
			},
			
			/*排序*/
			_order : function(){
				var obj = this.element.find('.detail'),
					order = '';
				$.each(obj,function(){
					 order += $(this).attr('_sign') +'_'+$(this).attr('_type')+',';
				}).get().join(',');
				this.element.find('input[name="order"]').val(order);
			},
			
			/*另存为 相当于创建*/
			_saveas : function(){
				this.element.find('.save-button').prop('disabled' , false);
				this.element.attr('_publish' , 0);
				this.element.find('input[name="a"]').val('create');
				this.element.find('input[name="sub"]').trigger('click');
			},
			
			/*保存*/
			_othersave : function(){
				this.element.find('.save-button').prop('disabled' , false);
				this.element.attr('_publish' , 0);
				this.element.find('input[name="a"]').val('update');
				this.element.find('input[name="sub"]').trigger('click');
			},
			
			/*取消*/
			_cancel : function(){
				var _this = this;
				this.element.attr('_publish' , 1);
				this.element.find('input[name="a"]').val('update');
				this.element.find('.notice-box').animate({height:'0px',width:'0px'},100,function(){
					_this.element.find('.notice-box').hide();
				});
				this.element.find('.cover').hide();
				this.element.find('.save-button').prop('disabled' , false);
			},
			
			_myTip : function(self , tip ){
				self.myTip({
					string : tip,
					delay: 1000,
					dtop : 0,
					dleft : -10
				});
			},
			
			_submit : function(){
				var sform = this.element,
					_this = this;
				sform.submit(function(){
					var savebtn = sform.find('.save-button'),
						publish = sform.attr('_publish'),
						length = sform.find('.feedback-info').has('div').length;
					if( length == 0){
						_this._myTip(savebtn , '请先选择组件');
						return false;
					}
					else if(!sform.data('change')){
						_this._myTip(savebtn , '组件标题不能有空');
						return false;
					}
					else if(publish == 1){
						sform.find('.cover').show();
						sform.find('.notice-box').show().animate({height:'118px',width:'280px'},200);
						return false;
					}
					else{
						savebtn.prop('disabled' , true);
					}
					_this._organizedata();
//					return false;
				});
			},
		});
		
	})($);
	$('.m2o-form').feedback_form({
		delUrl : 'run.php?mid='+ gMid +'&a=delete_common',
		moveUrl : 'run.php?mid='+ gMid +'&a=update_common',
		getUrl : 'run.php?mid='+ gMid +'&a=get_common',
		one_text_tpl : $('#one-text-tpl'),
		multiline_text_tpl : $('#multiline-text-tpl'),
		more_choice_tpl : $('#more-choice-tpl'),
		select_tpl : $('#select-tpl'),
		upload_tpl : $('#upload-tpl'),
		divide_box_tpl : $('#divide-box-tpl'),
		address_box_tpl : $('#address-box-tpl'),
		time_box_tpl : $('#time-box-tpl'),
		date_box_tpl : $('#date-box-tpl'),
		defaultOptions :  {
			name : '标题',
			type : '',
			brief : '',
			char_num : 20,
			options : {
				0: '选项一',
				1: '选项二',
				2: '选项三'
			},
			limit_type : 2,
			is_name : 1,
			op_num : 1,
			width : '450',
			height: '30',
			is_common : 0,
			is_required : 0,
			start_time : '2010',
			end_time : '2014',
			spilter : 2,
		},
		standarddata : ['id','name','brief','width','height','num','required','common','back','cor','limit','op','options','spilter','member','field','unique'],
		standardname : ['id','name','brief','width','height','char_num','is_require','is_common','is_name','cor','limit_type','op_num','option','spilter','is_member','member_field','is_unique'],
		fixeddata : ['id','name','brief','width','height','num','required','common','back','hour','min','second','province','city','county','detail','start','end','member','field','unique'],
		fixedname : ['id','name','brief','width','height','char_num','is_require','is_common','is_name','hour','min','second','province','city','county','detail','start_time','end_time','is_member','member_field','is_unique'],
	});
	$(window).scroll(function(){ /*滚动条事件 根据滚动条位置定位编辑框位置*/
		var scrollTop = $(this).scrollTop();
		if(scrollTop<130){
			$('.m2o-form').find('.regular').css('top' , 40);
		}else{
			$('.m2o-form').find('.regular').css('top' , scrollTop-90);
		}
		
	})
});