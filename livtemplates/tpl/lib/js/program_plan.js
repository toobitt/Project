function hg_plan_check_day()
{
	if($('#start_date').val())
	{
		if($('#end_date').val())
		{
			if( _dateToUnix($("#start_date").val()+' 00:00:00') < _dateToUnix($("#end_date").val()+' 00:00:00'))
			{
				$("#week_day_form").slideDown(400).find('input[type="checkbox"]').attr("checked",false);
				$("#week_date").slideDown(400);
				var toff = Math.floor((_dateToUnix($("#end_date").val()+' 00:00:00') - _dateToUnix($("#start_date").val()+' 00:00:00')) / 86400);
				if(toff < 6)
				{
					_checkWeek($("#start_date").val(),$("#end_date").val());
				}
				else
				{
					_checkWeek($("#start_date").val(),$("#end_date").val(),1);
				}
			}
			else
			{
				if(_dateToUnix($('#start_date').val() + ' 00:00:00') > _dateToUnix($('#end_date').val() + ' 00:00:00'))
				{
					$("#day_tips").html('选择日期有误！').fadeIn(1000).fadeOut(1000);
					$("#week_day_form").slideDown(400).find('input[type="checkbox"]').attr("checked",false);
					$('#end_date').val($('#start_date').val());
				}
				$("#week_day_form").slideUp(400).find('input[type="checkbox"]').attr("checked",false);
			}
		}
		else
		{
			$("#week_day_form").find('input[type="checkbox"]').each(function(index,event){
				$(this).attr("checked",true);
			});
		}
	}
}

function _checkWeek(start,end,type)
{
	var toff = Math.floor((_dateToUnix(end+' 00:00:00') - _dateToUnix(start+' 00:00:00')) / 86400);
	var week = [];
	var day = new Date(Date.parse(start));
	var tmp_week = day.getDay();
	if(type)
	{
		tmp_week = 1;		
	}
	for(var i = 0;i <= toff;i++)
	{
		if(tmp_week > 7)
		{
			tmp_week = 0;
		}
		week[i] = tmp_week;
		tmp_week++;
	}

	$("#week_day_form").find('input[type="checkbox"]').each(function(index,event){
		if(!index)
		{
			$(this).attr("checked",true);
		}
		if(index == 1)
		{
			if(type)
			{
				$(this).attr("checked",true);
			}
		}
		if(index >= 2)
		{
			for(var i = 0;i <= toff;i++)
			{
				if(week[i] == $(this).val())
				{
					$(this).attr("checked",true);
				}
			}
		}
	});
}

function _dateToUnix(str)
{
	str = str.replace(/(^\s*)|(\s*$)/g, "");
	var new_str = str.replace(/:/g,'-');
	new_str = new_str.replace(/ /g,'-');
	var arr = new_str.split('-');

	var datum = new Date(Date.UTC(arr[0],arr[1]-1,arr[2],arr[3]-8,arr[4],arr[5]));
	return (datum.getTime()/1000);
}

function hg_form_check()
{
	if(!trim($("#title").val()))
	{
		$("#title_tips").html('请填写节目名称').fadeIn(1000).fadeOut(1000);
		return false;
	}

	if(!trim($("#start_date").val()))
	{
		$("#day_tips").html('请填写开始日期').fadeIn(1000).fadeOut(1000);
		return false;
	}
	
	if(trim($('#start_date').val()) && trim($('#end_date').val()))
	{
		if(_dateToUnix($('#start_date').val() + ' 00:00:00') > _dateToUnix($('#end_date').val() + ' 00:00:00'))
		{
			$("#day_tips").html('选择日期有误！').fadeIn(1000).fadeOut(1000);
			$('#end_date').val($('#start_date').val());
			return false;
		}
	}
	
	if(!trim($("#start_time").val()))
	{
		$("#time_tips").html('请填写起始时间').fadeIn(1000).fadeOut(1000);
		return false;	
	}
	else
	{
		//console.log(trim($("#start_time").val()).test(/(^\s*)[0-5]{1}[0-9]{1}:[0-5]{1}[0-9]{1}(\s*$)/g));
		//return false;
	}
	return true;
}

function hg_plan_repeat(e,type)
{
	var toff = Math.floor((_dateToUnix($("#end_date").val()+' 00:00:00') - _dateToUnix($("#start_date").val()+' 00:00:00')) / 86400);
	var week = [];
	var day = new Date(Date.parse($("#start_date").val()));
	var tmp_week = day.getDay();
	if(type==1)
	{
		tmp_week = 1;		
	}
	var key = (toff < 6) ? 0 : 1;
	for(var i = 0;i <= toff;i++)
	{
		if(tmp_week > 7)
		{
			tmp_week = 0;
		}
		if($(e).val() == tmp_week)
		{
			key = 1;
		}
		tmp_week++;
	}
	if(!key)
	{
		$("#day_tips").html('时间范围选择有误！').fadeIn(1000).fadeOut(1000);
		$(e).attr('checked',false);
		return false;
	}	

	if(!type)
	{
		if($(e).attr('checked'))
		{
			$('#week_date').slideDown(400);			
			$("div[id^=week_date] input[type=checkbox]").removeAttr('checked');
			//$('#date_list').hide();
		}
		else
		{
			$('#week_date').slideUp(400);
			//$('#date_list').show();
			$("div[id^=week_date] input[type=checkbox]").removeAttr('checked');
		}
	}
	else
	{
		if(type == 2)
		{
			if($("div[id^=week_date] input[id^=week_day_]:checked").length < 7)
			{
				$("#every_day").removeAttr('checked');
			}
			else
			{
				$("#every_day").attr('checked','checked');
			}
		}
		else
		{
			if($(e).attr('checked'))
			{
				$("div[id^=week_date] input[id^=week_day_]").attr('checked','checked');
			}
			else
			{
				$("div[id^=week_date] input[id^=week_day_]").removeAttr('checked');
			}
		}
		
	}
}

function hg_plan_delete(e)
{
	if(confirm('是否删除'))
	{
		var url = $(e).attr('_href');
		hg_request_to(url);
	}
}

function hg_call_plan_delete(id)
{
	if(id)
	{
		var ret = JSON.parse(id);
		$("#plan_"+parseInt(ret.id)).remove();	
	}
}

$(function(){
	(function(){
		var plan_box = $( '.plan-index' ),
			file = $( '.plan-file' );
		var url = './run.php?mid=' + gMid + '&a=upload_indexpic';
		plan_box.on( 'click' , function(){
			file.click();
		} );
		file.ajaxUpload( {
			url : url,
			phpkey : 'indexpic',
			before : function( info ){
				var imgdata = info['data']['result'];
				plan_box.addClass( 'hide-bg' ).find( 'img' ).attr( 'src' , imgdata );
			},
			after : function( json ){
				var data = json['data'];
				var pic = data['pic'];
				$('#indexpic').val( pic );
			}
		} );
	})($);
});


