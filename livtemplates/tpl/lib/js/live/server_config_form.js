jQuery(function($){
		var MC = $('.ad_form');
		var control = {
				init : function(){
					MC
					.on('click' , '.add_btn' , $.proxy(this.add, this))
					.on('click' , '.del_btn' , $.proxy(this.del, this))
					.on('blur' , '.getpath' , $.proxy(this.getpath, this))
					.on('focus' , '.getpath' , $.proxy(this.getval, this))
					.on('mouseover' , '.path-box' , $.proxy(this.mouseover, this))
					.on('mouseout' , '.path-box' , $.proxy(this.mouseout, this))
					.on('click' , '.path-box li' , $.proxy(this.selectpath , this));
				},
				
				add : function( event ){
					var self = $(event.currentTarget),
					item = self.closest('.host-box');
					item.after($('#host-tpl').html());
				},
				
				del : function( event ){
					var self = $(event.currentTarget),
						length = MC.find('.b_host-box').length,
						selected = self.closest('.b_host-box ').find('input[type="radio"]').prop('checked');
					if( selected ){
						this.myTip( self , '选中数据不能删除或清空');
						return false;
					}
					if( length == 1){
						self.closest('.b_host-box').find('input').val('');
						return;
					}else{
						self.closest('.b_host-box').remove();
					}
				},
				
				getval : function( event ){
					var self = $(event.currentTarget);
					this.val = self.val();
				},
				
				getpath : function( event ){
					var self = $(event.currentTarget),
						item = self.closest('.host-box'),
						host = $.trim( item.find('.get_host').val() ),
						api = $.trim( item.find('.get_nginx').val() ),
						_this = this;
					item.find('input[type="radio"]').val(host);
					if( host && api ){
						var url = 'http://' + host + api + 'get/fs_status';
						$.ajax({
							type : "get",
							url : url ,
							dataType : "jsonp",
							success : function(json){
								console.log(json);
								_this.initpath(self ,  json.fs_statuses );
							},
							error : function( data ){
								var tip = "主机名或者ngnix接口不对！";
								_this.myTip( self , tip);
							}
						});
					}
				},
				
				initpath : function( self , data ){
					var box = self.closest('.host-box').find('.path-list');
					var initdata = [];
					$.each(data , function(key , value){
						initdata.push(value.dir  );
					});
					var info = {};
					info.option = initdata ;
					box.empty();
					$('#path-tpl').tmpl( info ).appendTo( box );
				},
				
				mouseover : function( event ){
		    			this.onoff( event, 'over' );
			    	},
			    	
			    	mouseout : function( event ){
			    		this.onoff( event, 'out');
			    	},
			    	
			    	onoff : function( event, type ){
			    		var self = $(event.currentTarget),
			    			box = self.find('ul');
			    		box[ type == 'over' ? 'show' : 'hide' ]();
			    		if(type=='over' && box.find('li').length == 0){
			    			this.getpath( event );
			    		}
			    	},
				
				selectpath : function( event ){
					var self = $( event.currentTarget );
						path = self.text();
					self.closest('.path-box').find('.select-path').text( path );
					self.closest('.path-box').find('input[type="hidden"]').val(path);
					self.closest('ul').hide();
				},
				
				myTip : function( self , tip ){
					self.myTip({
						string : tip,
						width : 170,
						delay: 2000,
						dtop : 0,
						dleft : 80,
						color : '#5394e4',
					});
				},
		};
		control.init();
});