$(function(){

  (function($){
	  $.widget('station.picupload', {
			options : {
				del_url : "./run.php?mid="+gMid+"&a=del_pic",
				setIndex_url : "./run.php?mid="+gMid+"&a=set_indexpic",
			     upload:'.photo-item',
			     uploadFile:'.photo-file',
			     add : '.photo-add',
			     photo_tpl:'#photo-list-tpl',
			     photo_list:'.photo-item-list',
			     'delete' : '.delete',
			     setIndex : '.set-index'

	        },
	        _create : function(){
	        	this.uploadFile=this.element.find(this.options['uploadFile']);
	        },
	        _init:function(){
	        	var _this=this,
	        	    handlers={};
	        	this.id = $('#channel_form').data('id') || '';
	        	var url = "./run.php?mid="+gMid+"&a=upload&cid="+this.id;
	        	handlers['click '+this.options['upload']] ='_upload';
				handlers['click '+this.options['delete']] ='_delete';
				handlers['click '+this.options['add']] ='_addPhoto';
				handlers['click '+this.options['setIndex']] ='_setIndex';
				this.info={};
				this._on(handlers);
				this.uploadFile.ajaxUpload({
	                url : url,
	                phpkey : 'Filedata',
	                before : function(info){
	                    _this._uploadBefore(info['data']['result']);
	                },
	                after : function(json){
	                    _this._uploadAfter(json);
	                }
	            });
	        },
	        _addPhoto:function(){
	        	$(this.options['photo_list']).append($(this.options['photo_tpl']).html());
	        },
	        _delete:function(event){
	        	var obj=$(event.currentTarget).closest(this.options['upload']),
	        	    url=this.options['del_url'],
        	        id=obj.find('input').val();
	        	$.post(url,{id:id},function(){
		        	obj.remove();
	        	});
	        	event.stopPropagation();
	        },
	        _setIndex:function(event){
	        	var obj = $(event.currentTarget).closest(this.options['upload']),
	        	    url = this.options['setIndex_url'],
	        	    img_id = obj.find('input').val();
	        	var _this = this;
	        	id = _this.id;
	        	if(!id)
        		{
	        		$('#indexpic_id').val(img_id);
        		}
	        	
	        	event.stopPropagation();
	        	$.post(url,{img_id : img_id,id:_this.id},function(){
	        		$(event.currentTarget).addClass('current');
	        		obj.siblings().find(_this.options['setIndex']).removeClass('current');
	        	});
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
	            var data = json.data;
	            var id = data.id;
	            console.log(id);
	            if(!this.box.find('input')[0]){
		            $('<input name="img_id[]" type="hidden" />').val(id).appendTo(this.box[0]);
	            }else{
	            	
	            }	            
	        },
	        _avatar : function(src){
	            var img=this.box.find('img'),
	                del=this.box.find('.delete');
	            this.box.hasClass('default') && this.box.removeClass('default');
	            !img[0] && (img= $('<img />').appendTo(this.box[0]));
	            !del[0] && $('<span class="delete">x</span>').appendTo(this.box[0]);
	            img.attr('src',src);
	        }
		});
  })($);
  $('.photo-box-area').picupload();
});