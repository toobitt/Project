$(function($){
	(function($){
		$.widget('program.program_list',{
			options : {
				each : '.template-each',
				del : '.del-temp',
				index : '.temp-indeximage',
				checkAll : '.checkAll',
				batdel : '.batch-delete',
			},
			
			_create : function(){
				this.programurl = {
					delProgramUrl : './run.php?a=relate_module_show&app_uniq=program&mod_uniq=program_template&mod_a=delete'
				};
			},
			
			_init : function(){
				var op = this.options,
					handlers = {};
				handlers['click ' + op['del'] ] = 'delTemp';
				handlers['click ' + op['each'] ] = 'chechTemp';
				handlers['click ' + op['index'] ] = '_addIndex';
				handlers['click ' + op['checkAll'] ] = 'checkAll';
				handlers['click ' + op['batdel'] ] = '_delBat';
				this._on(handlers);
				this._uploadIndex();
				this._printIcon();
			},
			
			_addIndex : function( event ){
				var self = $(event.currentTarget);
				this.area = self;
				$('.image-file').click();
				event.stopPropagation();
			},
			
			_printIcon : function(){
				var tid = this.element.find('.save_temp').data('id');
				if(!tid){
					this.element.find('.print').hide();
				}
			},
			
			_uploadIndex : function(){
				var _this = this;
				var url = "./run.php?a=relate_module_show&app_uniq=program&mod_uniq=program&mod_a=upload_indexpic";
				if($('.image-file').length){
					$('.image-file').ajaxUpload({
						url : url,
						phpkey : 'img',
						before : function(){
							_this.area.addClass('item-index');
						},
						after : function( json ){
							var data = json['data'];
							var src = $.globalImgUrl(data, '40x35');
							_this.area.removeClass('item-index').find('img').attr('src',src);
							var item = _this.area.closest('.m2o-each');
							$(".wrap").program('ChangeItem', item);
						}
					});
				}
			},
			
			delItem : function(id, item, self){
				var _this = this;
				var message;
				if(item.length > 1){
					message = '您确定批量删除选中内容吗？';
				}else{
					message = '您确定删除该条内容吗？';
				}
				jConfirm( message , '删除提醒' , function( result ){
					if( result ){
						$.get(_this.programurl.delProgramUrl, {id : id}, function(){
							item.remove();
						});
					}
				}).position(self);
			},
			
			delTemp : function( event ){
				var self = $(event.currentTarget),
					item = self.closest('li');
					id = item.data('id');
				this.delItem(id, item, self);
				event.stopPropagation();
			},
			
			_delBat : function( event ){
				var op = this.options;
				var item = this.element.find( op['each'] + '.select'),
					self = $(event.currentTarget),
					id = item.data('id');
				if(!item.length){
					jAlert('请选择要删除的内容', '删除提醒').position(self);
					return false;
				}
				this.delItem(id, item, self);
			},
			
			chechTemp : function( event ){
				var self = $(event.currentTarget);
				self.toggleClass('select');
			},
			
			checkAll : function( event ){
				var op = this.options;
				var isCheck = $(event.currentTarget).prop('checked');
				this.element.find( op['each'] )[(isCheck ? 'add' : 'remove') + 'Class']('select');
			}
		});
	})($);
	$('.common-list-content').program_list();
	$(".save_temp, .resave").click(function(){
		var template_name= $('.template-name').find('input').val();
		var programid = $(this).data('id');
		if( template_name == '' ){
			jAlert("请填写模板名称","节目单提醒");
			return false;
		}
		var data = '';
	    if($(".wrap").program('option','currentKey'))
	    {
	    	jAlert('有节目未保存！','节目单提醒');
		    return false;
	    }
	    var space = '';
	    if($('#program_menu').css('display') == 'none'){
	    	 var box = ".m2o-each";
	    }else{
	    	 var box = ".program-li";
	     }
	    $(box).each(function(index,event){
			var tmp_data = '{"start":"'+$(event).attr("_start")+'","theme":"' + $(event).find("input[class*=theme]").val() + '","id":"' + $(event).attr("_id") + '","index_pic":"' + $(event).find("div[class*=indeximage] img").attr('src') + '"}';
			data += space + tmp_data;
			space = ',';
		});
		data = '[' + data + ']';
		var url = './run.php?a=relate_module_show&app_uniq=program&mod_uniq=program_template&mod_a=update&html=1';
		hg_request_to(url, {id: programid, data : data, title : template_name}, '', 'program_callback');
    });
    $('.print').click(function(){
    	var id = $(this).data('id');
    	$(this).printscreen({
	   		node : '#program_menu',
	        'upload-url' : "./run.php?a=relate_module_show&app_uniq=program&mod_uniq=program_template&mod_a=screenshotForTemplate&id=" + id,
	        'upload-key' : 'indexpic',
	    });
    });
});
function program_callback()
{	
	gTasks = {};
	// window.location.reload();
	window.location.href="./run.php?a=relate_module_show&app_uniq=program&mod_uniq=program_template&mod_a=show";
}