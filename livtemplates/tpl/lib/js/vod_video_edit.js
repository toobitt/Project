function hg_mouseover(obj)
{
	var id = $(obj).attr('id');
	$('#'+id+'_list').css('display','block');
}

function hg_mouseout(obj)
{
	var id = $(obj).attr('id');
	$('#'+id+'_list').css('display','none');
}

function hg_change_color(obj)
{
	$(obj).css('background','green');
}

function hg_back_color(obj)
{
	$(obj).css('background','');
}

function hg_select_this(obj,txt,fname,flag)
{
  $('#'+txt).text($(obj).text());
  $('#'+fname).css('display','none');
  if(flag)
  {
	  $('#vod_sort_id').val($(obj).attr('attrid'));
  }
  else
  {
	  $('#source').val($(obj).attr('attrid'));
  }
}

































