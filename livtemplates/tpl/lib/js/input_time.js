function hg_kj_checktimeval(obj,e,id)
{
	 var tval = parseInt($(obj).val());
	 if(e == 1)
	 {
		if(tval < 0 || tval > 24)
		{
			alert('输入的小时必须在0~23之间');
			$(obj).val(0);
		}
	 }
	 else
	 {
		if(tval < 0 || tval > 60)
		{
			alert('输入的小时必须在0~59之间');
			$(obj).val(0);
		}
	 }

	 var hour = parseInt(document.getElementById('hour_'+id).value);
	 var minu = parseInt(document.getElementById('minu_'+id).value);
	 var seco = parseInt(document.getElementById('seco_'+id).value);
	 
	 var total_time = Math.ceil( hour * 60 * 60 + minu * 60 + seco ) * 1000;
	 var o = document.getElementById('time_'+id);
	 o.value = total_time;
}