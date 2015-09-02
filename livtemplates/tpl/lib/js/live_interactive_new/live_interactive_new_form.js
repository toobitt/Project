$(function(){
	var MC = $('.m2o-form');
	
	var control = {
			init : function(){
				MC
				.on('click' , '.indexpic' , $.proxy(this.selectIndexpic , this))
				.on('change' , '.time', $.proxy(this.changeTime , this))
				.on('click' , '.icon' , $.proxy(this.uploadpic , this))
				.on('click' , '.image-option-del' , $.proxy(this.delImg , this))
				.on('click' , '.del-pic' , $.proxy(this.delCitepic , this))
				.on('click' , '.set-suoyin' , $.proxy(this.setSuoyin , this))
				.on('click' , '.cite' , $.proxy(this.getCiteinfo , this))
				.on('click' , '.add-guest' , $.proxy(this.addGuest , this))
				.on('click' , '.save-guest' , $.proxy(this.saveGuest , this))
				.on('click' , '.guest-list li' , $.proxy(this.selectGuest , this))
				.on('click' , '.del-selected' , $.proxy(this.delGuest , this))
				this.initPop();
				this.initInputFile();
			},
			
			initPop : function(){
				var _this = this;
				$.pop({
					title : '引用内容',
					className : 'pubLib-pop-box',
					widget : 'pubLib',
					need_define : true,
					upload_url : './run.php?mid=' + gMid + '&a=upload_other',
					clickCall : function(event , info ,widget){
						_this.clickCall( info, widget );
					}
				});
	            this.datasource = $('.pubLib-pop-box');
	            this.datasource.pubLib('hide');
			},
			
		    initInputFile : function(){
                var _this = this;
                MC.find('input[type="file"]').ajaxUpload({
                    url : './run.php?mid=' + gMid + '&a=upload',
                    phpkey : 'Filedata',
                    after : function( json ){
                        var obj = [];
                        if( json['data'] instanceof Array ){
                            obj = json['data'];
                        }else{
                            obj = [json['data']];
                        }
                        _this.ajaxUploadAfter(obj);
                    }
                });
            },
            
            selectIndexpic : function(event){
                var self = $(event.currentTarget);
                this.externalCall = true;
                self.next('input[type="file"]').trigger('click');
            },
            
            uploadpic : function(event){
                var self = $(event.currentTarget);
                this.externalCall = false;
                self.next('input[type="file"]').trigger('click');
            },
            
            
            ajaxUploadAfter : function(json){
                var data = [],
                    ids_arr = [],
                    _this = this;
                $.each( json, function(k,v){
                    var obj = v;
                    obj._sSrc = $.globalImgUrl( v, '30x30' );
                    obj._mSrc = $.globalImgUrl( v, '80x' );
                    obj._bigsrc = $.globalImgUrl( v );
                    if( !v['id'] ){
                        obj.id = v['material_id']
                    }
                    ids_arr.push( obj['id'] );
                    data.push( obj );
                    var item_tpl  =
                        '<div class="item-box">' +
                            '<span class="del"></span>'+
                            '<div class="item-inner-box">' +
                            '<a class="suoyin set-suoyin"></a>' +
                            '<img class="image" imageid="'+obj.id+'" bigsrc="'+obj._bigsrc+'" src="'+obj._mSrc+'">' +
                            '</div>' +
                            '<div class="nooption-mask"></div>' +
                            '<div class="image-option-box">' +
                            '<span class="image-option-del image-option-item"></span>' +
                            '</div>' +
                            '<input type="hidden" value="'+obj.id+'" name="material_id[]" />' +
                            '</div>';
                    $(item_tpl).prependTo("#img-list");
                });

                if( this.externalCall ){ 
                	MC.find('.indexpic').find('img').attr('src', data[0]._bigsrc);
                	MC.find('.indexpic').find('input[name="indexpic"]').val(data[0].id);
                    if ( MC.find('.indexpic').find('span').hasClass('indexpic-suoyin') ) {
                    	MC.find('.indexpic').find('span').removeClass('indexpic-suoyin').addClass('indexpic-suoyin-current');
                    }
                }
            },
            
            changeTime : function(event){
                var self = $(event.currentTarget),
                    start_time = MC.find('input[name="start_time"]').val(),
                    end_time = MC.find('input[name="end_time"]').val(),
                    start_time = start_time.replace(/-/g,'/'),
                    end_time = end_time.replace(/-/g,'/'),
                    start_time = new Date(start_time),
                    end_time = new Date(end_time),
                    end_time = end_time.getTime(),
                    start_time = start_time.getTime(),
                    tip = '';
                if(start_time > end_time){
                    tip = "初始时间不能大于结束时间";
                    this.myTip(self , tip);
                    self.val('');
                    return false;
                }
            },
			
			delCitepic : function( event ){
				var self = $( event.currentTarget ),
					item = self.closest('.img-info');
				item.remove();
			},
			
			delImg : function (event) {
                var self = $(event.currentTarget);
                var imgid = self.closest('.item-box').find('.image').attr('imageid');
                var indexpicid = $('.indexpic').find('input[name="indexpic"]').val();

                self.closest('.item-box').slideUp(function(){
                    this.remove();
                    if (imgid == indexpicid) {
                        $('.indexpic').find('img').attr('src', '');
                        $('.indexpic').find('input[name="indexpic"]').val(0);
                        $('.indexpic').find('span').removeClass('indexpic-suoyin-current').addClass('indexpic-suoyin');
                    }
                });
            },
			
			setSuoyin : function( event ){
				 var self = $(event.currentTarget),
                 	id = self.siblings('.image').attr('imageid');
				 	src = self.siblings('.image').attr('bigsrc');
	             MC.find('.set-suoyin').not(self).removeClass('suoyin-current');
	             self.toggleClass( 'suoyin-current');
	             if(self.hasClass('suoyin-current')) {
	                 MC.find('.indexpic').find('img').attr('src', src);
	                 MC.find('.indexpic').find('input[name="indexpic"]').val(id);
	                 MC.find('.indexpic').find('span').removeClass('indexpic-suoyin').addClass('indexpic-suoyin-current');
	             } else {
	            	 MC.find('.indexpic').find('img').attr('src', '');
	            	 MC.find('.indexpic').find('input[name="indexpic"]').val(0);
	            	 MC.find('.indexpic').find('span').removeClass('indexpic-suoyin-current').addClass('indexpic-suoyin');
	             }
			},
			
			/*引用*/
			getCiteinfo : function(event){
				this.self = $(event.currentTarget);
				this.showPop();
			},
			
			showPop : function(){
				this.datasource.pubLib('show', {
					top : 0 + 'px',
					'margin-top' : 0,
				});
			},
			
			clickCall : function( info ,widget ){
				this.getattachinfo(info[0] , this.self);
				widget.element.pubLib('hide');
			},
			
			getattachinfo : function( data , self ){
				console.log( data );
				var info = {};
				info.src = this.createImgsrc( data.indexpic );
				info.suoyin = false;
				info.id = data.id;
				info.title= data.title;
				info.bundle_id = data.bundle_id;
				info.content_url = data.content_url;
				info.module_id = data.module_id;
				info.indexpic =JSON.stringify(data.indexpic);
				info.type = data.id ? 1 : 2;
				var box = self.closest('.img-info');
				$('#pic-tpl').tmpl(	info ).insertBefore( box );
			},
			
			addGuest : function(){													/*新增嘉宾*/
				var ids = MC.find('input[name="guests_id"]').val().split(','),
					obj = MC.find('.guest-box li').map(function(){
						return $(this).attr('_id');
					}).get(),
					names = MC.find('.show-guests li').map(function(){
						return $(this).find('.guests').text();
					}).get().join(',');
				if(ids.length){														/*根据隐藏域guests_id的值，选中弹框中已选的项*/
					$.each( ids , function( key , value){
						$.each( obj , function( k , v ){
							if( v == value ){
								MC.find('.guest-box li:eq('+ k +')').addClass('selected');
							}
						})
					
					})
					names && MC.find('.selected-guest').text( names ).show();
				}
				MC.find('.guest-box').css('top', 100 + 'px');
			},
			
			selectGuest : function( event ){										/*选择嘉宾*/
				var self = $( event.currentTarget );
				self.toggleClass('selected');
				this.names = MC.find('.guest-list li').map(function(){
					if( $(this).hasClass('selected') ){
						return $(this).text();
					}
				}).get().join(',');
				this.ids = MC.find('.guest-list li').map(function(){
					if( $(this).hasClass('selected') ){
						return $(this).attr('_id');
					}
				}).get().join(',');
				MC.find('.selected-guest').text( this.names ).show();
				MC.find('input[name="guests_id"]').val( this.ids );
				if( !MC.find('.selected-guest').text() ){
					MC.find('.selected-guest').hide();
				}
			},
			
			saveGuest : function( event ){									/*保存嘉宾*/
				var self = $( event.currentTarget ),
					item = self.closest('.guest-box').find('.selected-guest'),
					txt = item.text(),
					box = MC.find('.show-guests ul'),
					_this = this;
				if(  this.names ){
					var names = this.names.split(','),
						ids = this.ids.split(',');
					box.empty();
					$.each( names , function(k,v){							/*根据弹框选中的项，展示出来已选嘉宾*/
						$('<li _id="'+ ids[k] +'"><span class="guests">'+ v +'</span><span class="del-selected">x</span></li>').appendTo( box );
					});
				}
				item.text('').hide();
				self.closest('.guest-box').find('li').removeClass('selected');
				MC.find('.guest-box').css('top', -600 + 'px');
			},
			
			delGuest : function( event ){
				var self = $( event.currentTarget ),
					item = self.closest('li');
				item.remove();
				this.ids = MC.find('.show-guests li').map(function(){
						return $(this).attr('_id');
				}).get().join(',');
				MC.find('input[name="guests_id"]').val( this.ids );
			},
			
			createImgsrc :function( data, options ){						//图片src创建
				var options = $.extend( {}, {width:80,height:50}, options ),
					data = data || {},
				src = [data.host, data.dir, options.width, 'x', options.height, '/', data.filepath, data.filename].join('');
				return src;
			},
			
			myTip : function(self , tip ){
                self.myTip({
                    string : tip,
                    delay: 1000,
                    width : 180,
                    dtop : 0,
                    dleft : 140,
                });
            },
	};
	control.init();
})