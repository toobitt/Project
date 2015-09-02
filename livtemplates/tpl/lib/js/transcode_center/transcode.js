$(function($){

$('.common-switch').each(function(){
	var val = 0,
	    status=false,
	    id=$(this).closest('li').attr('name');
	$(this).hasClass('common-switch-on') ? val = 100 : val = 0;
	$(this).hg_switch({
		'value' : val,
		'callback':function(event,val){
			val >= 50 ? status = true : status = false;
			setStatus(status,id);
		}
	});
});

function setStatus(status,id){
	$.post('./run.php?mid=' + gMid + '&a=set_state',{
		is_open:status,
		id:id
	},function(data){
		
	},'json');
}
	
	$('.trans-module').on('click','.trans-module-del',function(){
		hg_closeAuth();
	});
	$('.trans-module').on('click','.menu-item',function(){
		var id=$(this).data('id'),
		    text=$(this).text(),
		    current=$('.trans-module').find('.current');
		if(+id){
			$('#is_carry_file').attr('checked',true);
		}else{
			$('#is_carry_file').attr('checked',false);
		}
		current.text(text);
	});
	/*上次切换*/
	$('.switch-vedio-box').each(function() {
		var id=$(this).data('id'),
		    arrow_controll=$(this).closest('.transcode-con-area').find('.arrow-controll');
		if($(this).find('li').length <=4){
			arrow_controll.hide();
		}else{
			arrow_controll.show();
		}
		$(this).switchable({
			triggers:null,
		    effect: 'scrollUp',
		    steps: 4,
		    panels: 'li',
		    easing: 'ease-in-out',
		    visible: 4, // important
		    loop: true,
		    end2end: true,
		    autoplay:false,
		    prev: $('#vedio-prev'+id),
		    next: $('#vedio-next'+id),
		    onSwitch: function(event, currentIndex) {
		        var api = this,
		            len=this.length;
		        api.prevBtn.toggleClass('prev-disable', currentIndex === 0);
		        api.nextBtn.toggleClass('next-disable', currentIndex === len - 1);
		    }
	   });
	});
	(function($){
		var iframe = $('#transFrame');
		$('.switch-vedio-box').on('click',function(event){
			var url = $(this).attr('_src'),
			    id = $(this).data('id'),
			    name = $('#r_'+ id).find('>.title').html();
			$('.middle-module').hasClass('trans-module-show') || $('.middle-module').addClass('trans-module-show')
			$('#trans_title').html(name);
			$('#top-loading').show();
			iframe.attr('src',url);
			iframe.data('id',id);
		});
		
		$('#trans-close').on('click',function(event){
			$(this).closest('.trans-module').removeClass('trans-module-show');
			iframe.attr('src','');
		});
	})($);
	
	(function($){
		
		var server_id =parent.$('#transFrame').data('id');
		/*暂停转码*/
		$('#tasklist').on('click','.pause',function(event){
			var self =  $(event.currentTarget);
			var flag = self.hasClass('pended');
			var box = self.closest('div');
			var data = {
					video_ids : self.attr('_id'),
					server_id : server_id
			};
			if(flag){
				var url = './run.php?mid=' + gMid + '&a=pause_transcode_task';
			}else{
				var url = './run.php?mid=' + gMid + '&a=resume_transcode_task';
			};
			$.globalAjax( box, function(){
				return $.getJSON(url, data, function(json){
					if(flag){
						self.addClass('continue').removeClass('pended');
					}else{
						self.addClass('pended').removeClass('continue');
					}
				});
			});
		});
		/*删除转码*/
		$('#tasklist').on('click','.delete',function(event){
			var data = {
					video_ids : $(this).attr('_id'),
					server_id : server_id
			},
				url = './run.php?mid=' + gMid + '&a=stop_transcode_task';
			trans_controll(url,data);
			$(this).closest('.common-list-data').remove();
		});
		/*优先级转码*/
		$('#tasklist').on('click','.take-precedence',function(event){
			var self = $(event.currentTarget);
			self.closest('li').find('.precedence-box').toggle();
			self.closest('li').siblings().find('.precedence-box').hide();
		});
		$('#tasklist').on('click','.precedence-list li',function(event){
			var self =  $(event.currentTarget),
				text = self.text(),
				data = {
						id : self.attr('_id'),
						server_id : server_id,
						weight : self.attr('_weight')
				},
		    	url = './run.php?mid=' + gMid + '&a=set_weight';
			$.get(url,data,function(){

			});
			self.closest('.common-list-data').find('.take-precedence').text(text);
			self.closest('.precedence-box').hide();
		});
		
	})($);
	
});
function hg_bacth_trans(obj,a,name)
{	
	var data = {},
		server_id =parent.$('#transFrame').data('id'),
		select_item = $(obj).closest('form').find('.common-list-data.selected'),
		ids = select_item.map(function() { return $(this).attr('_id'); }).get().join(','),
		url = './run.php?mid=' + gMid + '&a='+ a + '_transcode_task';
	if (!ids) {
		var msg = '请选择要'+name+'的记录',
			tip = name + '提醒';
		jAlert ? jAlert(msg, tip).position(obj) : alert(msg);
		return false;
	}
	data.server_id = server_id;
	data.video_ids = ids;
	jConfirm( '您确认批量' + name +'选中记录吗？', name + '提醒', function( result ){
		if( result ){
			trans_controll(url,data, { type: name, select_item : select_item  });
		}
	} ).position(obj);
}

function trans_controll(url,data, options){
	$.get(url,data, function(){
		if( options.type == '删除' ){
			options.select_item.remove();
		}
		if( options.type == '暂停' ){
			options.select_item.find('.pause').addClass('pended').removeClass('continue');
		}
	});
}

function hg_showAddAuth(id)
{
	if(gDragMode)
    {
	   return  false;
    }

	if(id)
	{
		$('#auth_title').html('编辑转码服务器');
	}
	else
	{
		$('#auth_title').html('新增转码服务器');
	}
	   var url = "run.php?mid="+gMid+"&a=form&id="+id;
	   if($('#add_auth').hasClass('trans-module-show')){
		   $('#add_auth').removeClass('trans-module-show');
		   return;
	   }else{
			top.$('#top-loading').show();
	   }
	   hg_ajax_post(url);
	   hg_resize_nodeFrame();
}

//关闭面板
function hg_closeAuth()
{   
	$('#add_auth').removeClass('trans-module-show');
	hg_resize_nodeFrame();
}
//放入模板
function hg_putAuthTpl(html)
{   top.$('#top-loading').hide();
	var obj=$(html),
        switch_obj=obj.find('.common-switch');
	top.a = switch_obj;
	if(switch_obj.hasClass('common-switch-on')){
		var val=100;
	}else{
		var val=0;
	}
	switch_obj.hg_switch({
		'value': val,
		'callback':function(event,val){
			var check = obj.find('#is_open');
			if(val >= 50){
				check.attr('checked',true);
			}else{
				check.attr('checked',false);
			}
		}
	});
	$('#auth_form').html(obj);
	$('#add_auth').addClass('trans-module-show');
}
//create操作的回调
function hg_OverFormAuth(obj)
{
	var obj = eval('('+obj+')');
	var url = "run.php?mid="+gMid+"&a=add_new_transcode&id="+obj.id;
	hg_ajax_post(url);
	hg_closeAuth();
	window.location.href="./run.php?mid="+gMid;
}
//新增一行列表
function hg_insert_authlist(html)
{
	$('#auth_form_list').prepend(html);
}

//更新回调
function hg_change_update_data(obj)
{
	var obj = eval('('+obj+')');
	var item=$('#r_'+obj.id);
	var myswitch=item.find('.common-switch');
	console.log(myswitch);
	item.find('.title').text(obj.name);
	console.log(obj.is_open);
	var val=0;
	if(+(obj.is_open)){
		val=100;
	}else{
		val =0;
	}
	myswitch.hg_switch('refresh',{
		'value':val
	})
	hg_closeAuth();
}