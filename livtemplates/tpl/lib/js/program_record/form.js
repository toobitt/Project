function hg_request_program()
{
	var channel_id = parseInt($("#channel_id").val()) ? parseInt($("#channel_id").val()) : 0;
	var url = './run.php?mid=' + gMid + '&a=get_greater_program&channel_id=' + channel_id;
	$.stop = $.globalLoad( $('.channel-toggle') );
	hg_request_to(url);	
}
function hg_reponse_program(html)
{
	$.stop();
	$(".program-box").html(html);
	hg_init_program();
}
function hg_init_program(){
	//$(".content-list").on("click",".program_li",function(){
	$(".program_li").bind('click',function(){
		if($(this).hasClass('hidde_program')) return;
		if($(this).hasClass('shift')){
			var shift_date = $(this).data('date') + ' ',
			    shift_start = $(this).data('start') + ':00',
			    shift_end = $(this).data('end') + ':00',
			    _title = $(this).find('.program_theme').html();
			$("#title").val(_title);
			$('#start_time').val(shift_date + shift_start);
			$('#end_time').val(shift_date + shift_end);
		}
		var str_time = $(this).children('span.program_time').html().split("~");
		var start = str_time[0];
		var end = str_time[1];
		var title = $(this).children('a.program_theme').html();
		var dates = $(this).parent('ul.program_ul').children('.program_dates').children('span').html();
		
		$('#show_span').trigger('click');
        $(this).addClass('on').siblings().removeClass('on');
		$("#titles").val(title);
		$("#dates").val(dates);
		$("#start_times").val(start);
		$("#end_times").val(end);
		$("#other_list").slideUp();	
	});
}
function hg_request_plan()
{
		var channel_id = parseInt($("#channel_id").val()) ? parseInt($("#channel_id").val()) : 0;
		var url = './run.php?mid=' + gMid + '&a=get_plan&channel_id=' + channel_id;
		hg_request_to(url);
}

function hg_reponse_plan(html)
{
	$(".plan-box").html(html);
	hg_init_plan();
	
}
function hg_init_plan(){
	$(".plan_li").bind('click',function(){
		var str_time = $(this).children('span.plan_time').html().split("~");
		var start = str_time[0];
		var end = str_time[1];
		var title = $(this).children('a.plan_name').html();
		var myDate = new Date();
		var dates = myDate.getFullYear() + '-' + ((myDate.getMonth()+1) < 10 ? '0'+(myDate.getMonth()+1) : (myDate.getMonth()+1)) + '-' + (myDate.getDate() < 10 ? '0'+myDate.getDate() : myDate.getDate());
		$("#titles").val(title);
		$("#dates").val(dates);
		$("#start_times").val(start);
		/*
		$("#end_times").val(end);*/
		$("#other_list").slideUp();
		var week_array = $(this).children('span.plan_week').attr('_week') ? $(this).children('span.plan_week').attr('_week').split(',') : 0;
		hg_clear_week();
		if(week_array.length>0)
		{
			if(week_array.length == 7)
			{
				$(".n-h").attr("checked",true);	
			}
			else
			{
				$(".n-h").each(function(index,e){
					if(!index || week_array.indexOf($(e).val()) > -1)
					{
						$(this).attr("checked",true);
					}
				});
			}
			$("#week_date").slideDown();
		}
		else
		{
			
		}
	});

	function hg_clear_week()
	{
		$(".n-h").each(function(){
			if($(this).is(":checked"))
			{
				$(this).attr("checked",false);
				$("#week_date").slideUp();
			}
		});
	}
}
$(function(){
	function setStatus(obj,status){
		obj.find('input').attr('checked',false);
		if(status){
			obj.find('input:first').attr('checked',true);
		}else{
			obj.find('input:last').attr('checked',true);
		}
	}
	(function($){
		$('.common-switch').each(function(){
			var val = 0,
			    status = false;
			$(this).hasClass('common-switch-on') ? val = 100 : val = 0;
			var obj = $(this).closest('.m2o-item');
			$(this).hg_switch({
				'value' : val,
				'callback':function(event,val){
					val >= 50 ? status = true : status = false;
					setStatus(obj,status);
				}
			});
		});
	})($);
	
	
	(function($){
		$('#show_span').on('click',function(){
			$('.channel-toggle').slideToggle();
		});
		$('.channel-list').on('click','.channel-item',function(event){
			var self=$(this),
			    id=self.data('id'),
			    name=self.find('.name').text();
			$("#channel_id").val(id);
			$('#default_value').show();
			$('#channel_name').html(name);
			$('#show_span').html("重新选择频道");
			if(self.data('plan')){
				hg_request_program();
				hg_request_plan();
			}else{
				hg_ajax_program();
			}
		});
		$('.date-list').on("click",".date-li",function(){
			var self = $(this);
			if(self.hasClass('active')){
				return;
			}else{
				self.addClass('active')
				.siblings().removeClass('active');
			}
			$('.date-li').each(function(index){
				if($(this).hasClass('active')){
					$('.content:eq(' + index + ')').show().siblings().hide();
				}
			})
		});
	})($);
	
	function hg_ajax_program(){
		var channel_id = parseInt($("#channel_id").val()) ? parseInt($("#channel_id").val()) : 0;
		var url = './run.php?mid=' + gMid + '&a=get_greater_program&channel_id=' + channel_id;
		$.globalAjax( $('.program-box'), function(){
			return $.getJSON(url,function(data){
			var info={},
			    dates = [],
			    programes = [],
			    data = data[0];
		    if(data){
			    $.each( data, function(key,value){
			    	var dates_val = {},
			    		pgm_obj = [],
			    		pgm = {};
			    	dates_val.date = key;
			    	dates.push(dates_val);
			    	pgm.date = key;
			    	obj = data[key];
			    	$.each( obj, function(key,value){
			    		var pgm_objval = {};
			    		pgm_objval.period = key;
			    		pgm_objval.programe = obj[key];
			    		pgm_obj.push(pgm_objval);
			    	})
			    	pgm.programeList = pgm_obj;
			    	programes.push(pgm);
			    });
			    $('.nodata').hide();
			    $('.date-list').empty();
			    $('.content-list').empty();
			    $( "#date-tmpl" ).tmpl( dates ).appendTo( ".date-list" );
			    $( "#movieTemplate" ).tmpl( programes ).appendTo( ".content-list" );
				$('.date-li:eq(0)').addClass('active');
				$('.content:eq(0)').show();
				//info.lists = data;
				//console.log(info.lists );
				//var box = $('.program-box');
				//box.html('');
				//$('#program-tpl').tmpl(info).appendTo(box[0]);
				hg_init_program();
			}else{
				 $('.nodata').show();
				 $('.date-list').empty();
			     $('.content-list').empty();
			}
		});
		} );
	}
	
})