$(document).ready(function()
{
	hg_view_var_value = function ()
	{
		var jsvar = $('#jsvar').val();
		try
		{
			eval("var jsvalue=" + jsvar);
		}
		catch (e)
		{
			hg_debug_info(e.message);
			return;
		}
		if (!jsvalue)
		{
			hg_debug_info(jsvalue + '不存在');
		}
		else
		{
			var s = object2str(jsvalue);
			hg_debug_info(jsvar + ' = ' + s);
		}
	}

	hg_debug_info = function (info)
	{
		var html = $('#debuginfo').html();
		html = info + '<br />';
		$('#debuginfo').html(html);
	}
}
);