$(function(){
	var MC = $('body');
	var statustext = ['未处理','已通过','已打回'],
		status_color = ['#8ea8c8','#17b202','#f8a6a6'];
	var listTpl = 
				'{{if list}}'+
				'{{each list}}'+
					'<div class="sys-each" _bundleid={{= bundle_id}} _id={{= $value.content_id}}>'+
			  		 '<div class="sys-line sys-flex sys-flex-center">'+
			  		 '<div class="sys-item sys-flex-one list-title">'+
			  			 '<div class="sys-title-transition max-wd">'+
			  				 '<div class="index-pic pull-left">'+
						   		 '<img src="{{if $value.indexpic}}{{= $value.indexpic}}{{/if}}" id="img_1" class="biaoti-img">'+
						     '</div>'+
						    '<a class="sys-title-overflow" title="{{= $value.title}}" href="content_web.html?id={{= $value.content_id}}&bundle_id={{= bundle_id}}">'+
			                   '<span class="video-title overhidden">{{= $value.title}}</span>'+
			                '</a>'+
			  			 '</div>'+
			  		 '</div>'+
			  		 '<div class="sys-item list-code">{{= $value.user_name}} </div>'+
			  		'<div class="sys-item list-state">'+
			  			'{{if !group}}'+
							'<span class="sys-switch-status list-publish list-check {{if $value.status == "1"}}list-check-status{{/if}}" _id="{{= $value.content_id}}" _status="{{= $value.status}}" style="{{if $value.status == 1 }}color:#8ea8c8{{else if  $value.status ==2}}color:#17b202{{else}}color:#f8a6a6{{/if}}">{{= $value.status_show}}</span>'+
	        			'{{/if}}'+
	        		'</div>'+
	                '<div class="sys-item list-style" title="更新时间">2015-05-28 15:06</div>'+	                
			  		'<div class="sys-item list-create" title="创建人/时间">'+
		                '<span class="name">{{= $value.create_user}}</span>'+
		                '<span class="time">{{= $value.create_time}}</span>'+
	                '</div>'+
	                 '<div class="sys-item cloud-filter list-designate">'+
			  			 '<div class="form_dropdown search-dropdown">'+
			  				 
				           	 '<span class="select_designate select-item">{{if $value.is_power}}{{if islast}}无指派人{{else}}指派人{{/if}}{{else}}已指派{{/if}}</span>'+
				             '{{if !$value.is_power || islast }}'+
				             '<span class="cover"></span>'+
				             '{{/if}}'+
				             '{{if user}}'+
				             '<ul class="search-list date_search_list search-list-designate">'+
				               '{{each user}}'+
				    	  	    '<li class="date_search_designate" _value="{{= $value.user_id}}">{{= $value.user_name}}</li>'+
				    	  	    '{{/each}}'+
				             '</ul>'+
				             '<input type="hidden" name="designate" value="1">'+
				             '{{/if}}'+
				        '</div>'+
			  		 '</div>'+
			  		 '<div class="sys-item list-set" style="{{if $value.state == 1 }}color:#17b202{{else if  $value.state ==2}}color:#f8a6a6{{else}}color:#8ea8c8{{/if}}">{{= $value.state_show}}</div>'+
			  	 '</div>'+
			  	
			  	'{{if $value.is_power}}'+
			  	'<div class="status-box">'+			  	
					  	'<div class="set-item">'+
							'<label class="radio " _status="2">'+
								'<span class="icons">'+
									'<span class="first-icon fui-radio-unchecked"></span>'+
									'<span class="second-icon fui-radio-checked"></span>'+
								'</span>'+
								'<input type="radio" class="radio_auto" name="auto_play" value="1" data-toggle="radio">通过'+
							'</label>'+
							'<label class="radio checked" _status="3">'+
								'<span class="icons">'+
									'<span class="first-icon fui-radio-unchecked"></span>'+
									'<span class="second-icon fui-radio-checked"></span>'+
								'</span>'+
								'<input type="radio" class="radio_auto" name="auto_play" checked="checked" value="0" data-toggle="radio">打回'+
							'</label>'+
						'</div>'+
						'<textarea name="beizhu" class="form-control" placeholder="备注" ></textarea>'+
						'<span class="audit-btn">确定</span>'+
						'<span class="audit-cancel-btn">取消</span>'+
				  	'</div>'+
			  	'{{/if}}'+
			  	
			  	'</div>'+
	          '{{/each}}'+
	          '{{else}}'+
	          '<div class="no-data">暂无数据！</div>'+
	          '{{/if}}';
	   
	var control = {
	      init : function(){
	    		this.getInfo();
	    		this.getUserInfo();
	    		$('body').on('click' , function( e ){
					var target = $(e.target),
						item = MC.find('.search-list'),
						dialog = MC.find('.status-box');
				    if(target.closest('.select-item').length == 0 && target.closest('.search-list').length ==0 ){ 
				    	item.hide();
				    }
				    if( target.closest('.list-check').length == 0 && target.closest('.status-box').length ==0 ){
				    	dialog.hide();
				    }
				})
	    		MC
	    		.on('click , touchstart','.list-menu',$.proxy(this.showSort , this))
	    		.on('click','.select-item , .select-item-info',$.proxy(this.state , this))
	    		.on('click','.date_search_status',$.proxy(this.statu , this))
	    		.on('click','.date_search_designate',$.proxy(this.designate , this))
	    		.on('click','.panel-sort-ul li',$.proxy(this.sort , this))
	    		.on('click','.input-group-btn',$.proxy(this.search , this))
	    		.on('click','.list-check-status',$.proxy(this.show , this))
	    		.on('click','.audit-btn',$.proxy(this.auditPlay , this))
	    		.on('click', '.audit-cancel-btn' , $.proxy(this.hideAudit , this))
	       },
	       
	       getUserInfo : function(){
	    	   var url = 'rules.php';
	    	   $.doajax( null , url , {a : 'getUserInfo'} , function( json ){
	    		   var data = JSON.parse( json );
	    		   if( data.message){
	    				$.tip( data.message );
	    				window.location.href="login_web.html";
	    				return;
	    			}
	    		   var userInfo =  data[0];
	    		   if( userInfo ){
	    			   MC.find('.user-name').text( userInfo.user_name ).attr('title' , userInfo.user_name );
	    		   }
	    	   });
	       },
	       
	       showSort : function(){
	    	   MC.find('.panel-list-fl').toggleClass('show');
	       },
	       
	       getInfo : function( bundid , page , statu ){
				var _this = this;
					
				var info = {};
				info.bund_id = bundid ? bundid : MC.find('.panel-sort-ul li.current').attr('_id');
				info.keywords = MC.find('#search-key').val();
				//info.statu = statu ? statu : MC.find('input[name="statu"]').val();
				if(page){
					info.page = page
				}else{
					info.page = 1
				}

	       		var _this = this;
	       		$.doajax(null,'rules.php?a=rules', info ,function( data ){
	    			var json = JSON.parse( data );
	    			if( json.message){
	    				$.tip( json.message );
	    				window.location.href="login_web.html";
	    				return;
	    			}
	    			// _this.getSort( json.app_list , json.bundle_id );
	    				_this.getListInfo( json.list_info , json.user_group, json.user_list ,json.bundle_id,json.islast);
	    				if( json.page_info ){
	    					_this.getPage( json.page_info );
	    				}
	    		});
	       },
	       //文稿、视频分类
	       sort : function( event ){
	       		var target = $(event.currentTarget);
	       		var id = target.attr('_id');
	       		var _this = this;
	       		target.addClass('current').siblings().removeClass('current');
	  			_this.getInfo( id , 1 , null);
	       },
	       
	       getListInfo : function( json, group, user ,bundleid,islast){
	       		var info = {};
	       			info.list = json;
	       			info.group = group;
	       			info.bundle_id =bundleid;
	       			info.user = user;
	       			info.islast = islast;
	       			//console.log( info.user );
	       		
	       		var list = template.compile( listTpl ),
	            	listHtml = list( info );
	            	//console.log(info);
	            MC.find('.panel-body-list').html( listHtml );	
	       },
	       //分页
	       getPage : function( option ){
	       		var page_box = MC.find('.page_size'),
					_this = this;
				option.show_all = true;
				if( page_box.data('init') ){
					page_box.page('refresh',option);
				}else{
					option['page'] = function( event, page, page_num){
						_this.refresh( page );
					}
					page_box.page( option );
					this.page_num = option.page_num;
					page_box.data('init', true);
				}
	       },
	       //刷新
	       refresh : function( page ){
				this.getInfo( null , page , null );
		   },
		   //全部状态操作
		   state : function( event ){
				var self = $( event.currentTarget ),
					ul = self.closest('.search-dropdown').find('ul');
				ul.toggle();
				//ul.show().end().siblings().find('.status-box').hide();
		   },
		   
		   //全部状态：未处理、已打回、已通过操作
		   statu : function( event ){
			   	var self = $( event.currentTarget ),
					ul = self.closest('ul'),
					item = self.closest('.search-dropdown'),
					type = item.attr('_type'),
					text = self.text(),
					status = self.attr('_value'),
					_this = this;
				ul.hide();
			    item.find('input[name="statu"]').val( status );
				item.find('.select-item').text( text );
				_this.getInfo( null , 1 , status );
		   },
		   //指派操作
		   designate : function(event){
		   		var self = $( event.currentTarget ),
				   	ul = self.closest('ul'),
				   	parent = self.closest('.sys-each'),
					item = self.closest('.search-dropdown'),
					text = self.text(),
					status = self.attr('_value'),
					_this = this;
				ul.hide();
			    
				var param ={
					id : parent.attr('_id'),
					bundle_id :parent.attr('_bundleid'),
					user_id : self.attr('_value'),
					user_name :$.trim(self.text()),
					a : 'designate'
				};
				$.doajax(ul,'rules.php', param ,function( data ){	
	    			var data = JSON.parse(data);
					if( data[0].error ){
						// self.closest('.sys-each').find('.status-box').hide();
						
						parent.find('.list-code').html(text);
						item.find('input[name="designate"]').val( status );
						item.find('.select_designate').text( text );
						parent.find('.select_designate ').html('<span class="cover">已指派</span>');
						
					}else{
						alert( data[0].message );
					}
	    		});
		   },
		   //搜索
		   search : function(){
		   	    var keywords = MC.find('#search-key').val(),
		   	    	_this = this;
		   	    _this.getInfo( null , 1 ,null);
		   	    
		   },
		   // 审核状态操作
	        auditPlay : function( event ){	
				var self = $(event.currentTarget),
					parent = self.closest('.sys-each'),
					id = parent.attr('_id'),
					status = parent.find('.radio.checked').attr('_status'),
					remark = parent.find('textarea[name="beizhu"]').val();
				// if( status == 1 ){
					this.audit( self, id, status , remark);
				// }
			},
			audit : function( self, id , status , remark){
				var _this = this,
					url = 'rules.php';
				var param = {
					bundle_id : MC.find('.panel-sort-ul li.current').attr('_id'),
					id : id,
					status : status,
					remark : remark,
					a : 'audit'
				};	
				$.doajax(self,url,param,function( data ){
					var parent = self.closest('.sys-each');
					var data = JSON.parse(data);
					if( data[0].error ){
						self.closest('.sys-each').find('.status-box').hide();
						var status = param['status'] -1,
							status_text = statustext[status],
					    	status_colors = status_color[status];
					    	console.log( status , status_text , status_colors);
						parent.find('.list-check').text( status_text ).css({'color' : status_colors }).attr('_status',status);
						parent.find('.list-check').removeClass('.list-check-status');
					}else{
						alert( data[0].message );
					}
	    		});
			},
			
			// 弹窗显示 
			show : function(event){
				var self = $(event.currentTarget),
					parent = self.closest('.sys-each');
				parent.find('.status-box').show().end().siblings().find('.status-box').hide();
			},
			
			hideAudit : function( event ){
				var self = $(event.currentTarget),
					parent = self.closest('.sys-each');
				parent.find('.status-box').hide();
			}
    }
    control.init();
});
