$(function(){

  (function($){
	  $.widget('station.picupload', {
			options : {
				'avatar-url': "./run.php?mid="+gMid+"&a=upload_real_img",
				del_url : "./run.php?mid="+gMid+"&a=delete_real_img",
			     upload:'.add-photo',
			     uploadFile:'.photo-file',
			     photo_item : '.photo-item',
			     photo_tpl:'#photo-tpl',
			     photo_list:'.photo-list',
			     'delete' : '.delete'

	        },
	        _create : function(){
	        	if($(this.options['photo_item']+':last').attr('_id')){
	        		this.i=+$(this.options['photo_item']+':last').attr('_id') +1;
	        	}else{
		        	this.i=0;
	        	}
	        	this.uploadFile=this.element.find(this.options['uploadFile']);
	        },
	        _init:function(){
	        	var _this=this,
	        	    handlers={};
	        	handlers['click '+this.options['upload']] ='_upload';
				handlers['click '+this.options['delete']] ='_delete';
				this.info={};
				this._on(handlers);
				this.uploadFile.ajaxUpload({
	                url : _this.options['avatar-url'],
	                phpkey : 'Filedata',
	                before : function(info){
	                    _this._uploadBefore(info['data']['result']);
	                },
	                after : function(json){
	                    _this._uploadAfter(json);
	                }
	            });
	        },
	        _delete:function(event){
	        	var obj=$(event.currentTarget).closest(this.options['photo_item']),
	        	    url=this.options['del_url'],
	        	    id=obj.find('input').val();
	        	$.post(url,{id:id},function(){
		        	obj.remove();
	        	});
	        	event.stopPropagation();
	        },
	        _upload : function(event){
	        	var self=$(event.currentTarget),
	        	    uploadFile=$(this.options['uploadFile']);
	        	this.box=$(event.currentTarget);
	        	uploadFile.click();
	        },

	        _uploadBefore : function(src){
	            this._avatar(src);
	        },

	        _uploadAfter : function(json){
	            var data =json.data;
	            var i=this.i;
	            var obj=$(this.options['photo_item']+'[_id="'+i+'"]');
	            $('<input type="hidden" name="photo[]" />').val(data['id']).appendTo(obj);
	        	(this.i)++;
	            
	        },
	        _avatar : function(src){
	        	var box=$($(this.options['photo_tpl']).html()),
	                img=box.find('img');
	        	img.attr('src',src);
	        	box.attr('_id',this.i);
	        	box.appendTo(this.options['photo_list']);
	        }
		});
	  
	  $.widget('carpark.time_pick',{
		  options :{
			  ohms : '',
		  },
		  _create : function(){
		  },
		  _init : function(){
			  var _this = this;
			  this._on({
				  'click .time-copy' : '_copytime',
			  })
			  this.element.on({
				    mousedown : function(){
				        var disOffset = {left : 0, top : 0};
				        _this.options.ohms.ohms('option', {
				            time : $(this).val(),
				            target : $(this)
				        }).ohms('show', disOffset);
				        return false;
				    },
				     set : function(event, hms){
				     	var time = [hms.h, hms.m].join(':');
				     	var self = $(event.currentTarget);
				     	var obj = self.closest('li');
				     	$(this).val(time);
				     	var stime = obj.find('input[name="b_stime[]"]').val();
				     	var etime = obj.find('input[name="b_etime[]"]').val();
				     	var isSelected = obj.find('input[type="checkbox"]').prop('checked');
				     	if(stime && etime && stime > etime){
				     		var tip = '初始时间大于结束时间',
				     			width = 150;
				     		_this._tip(obj ,tip , width);
				     		obj.find('.time').val('');
				     		return false;
				     	}
				     	if(!isSelected){
				     		var tip = '请先选中此条';
				     		_this._tip(obj ,tip);
				     	}
				    }
				}, '.item-t');
		  },
		  
		  _tip : function(obj , tip ,width){
			  obj.myTip({
					string : tip,
					width : width,
					dleft : -20,
					color : '#6ea5e8'
				});
		  },
		  
		  _copytime : function(event){
			  var self = $(event.currentTarget),
		  	  	  obj = self.closest('li'),
		  	  	  s_time = obj.prev().find('input[name="b_stime[]"]').val(),
		  	      e_time = obj.prev().find('input[name="b_etime[]"]').val();
			  obj.find('input[name="b_stime[]"]').val(s_time);
			  obj.find('input[name="b_etime[]"]').val(e_time);
		  },
	  })
  })($);
  $('.photo-area').picupload();
  $('.add-button').on('click',function(){
      var self = $(this),
	      list =self.closest('.add-area').find('.checkbox-list');
	  if( list.data('animate') ){
		  list.animate({'left':-700+'px'},'slow').removeClass('active');
		  list.data('animate',false);
		  return;
	  }
	  self.hide();
	  list.animate({'left':0},'slow',function(){
		  self.show();
	  }).addClass('active');
	  list.data('animate',true);
  });
  
  $('.business-hours').time_pick({
	  ohms : $('#ohms-instance').ohms(),
  });
});


