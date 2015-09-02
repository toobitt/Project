
/*删除站点*/
function hg_delStation(id)
{
	if (confirm('确定删除吗？'))
	{
		var url = './run.php?mid=' + gMid + '&a=delete&id=' + id;;
		hg_ajax_post(url);
	}
}

/*编辑*/
function hg_stationForm(id)
{
	var url = './run.php?mid=' + gMid + '&a=form&id=' + id + '&infrm=1';
	window.location.href = url;
}

function hg_get_city_by_province(obj)
{
	var url = "run.php?mid="+gMid+"&a=get_city&province_id="+$(obj).attr('attrid');
	hg_ajax_post(url);
}

function city_back(html)
{
	$('#city_box').html(html);
}

function hg_get_area_by_city(obj)
{
	var url = "run.php?mid="+gMid+"&a=get_area&city_id="+$(obj).attr('attrid');
	hg_ajax_post(url);
}

function area_back(html)
{
	$('#area_box').html(html)
}

function show_map()
{
	var url= './run.php?mid='+gMid+'&a=get_map&address='+$('#address').val();
	hg_ajax_post(url);
}
function map_back(json)
{	
	alert(json);
	$('#map').html(json);
	hg_resize_nodeFrame();
}

var  gAuditId = '';
function hg_stateAudit(id,audit)
{
	gAuditId = id;
	var url = './run.php?mid=' + gMid + '&a='+ audit+'&id=' + id;
	hg_ajax_post(url,'','','hg_audit_callback');

}

function hg_audit_callback(json)
{
	var obj = eval("("+json+")");
	var con = '';
	if(obj.status == 'audit')
	{
		con = '已打回';
		color = '#f8a6a6';
	}
	else if(obj.status == 'back')
	{
		con = '已审核';
		color = '#17b202';
	}
	
	for(var i = 0;i<obj.id.length;i++)
	{
		$('#audit_' + obj.id[i]).text(con);
		$('#audit_' + obj.id[i]).removeAttr('onclick');
		$('#audit_' + obj.id[i]).attr('onclick','hg_stateAudit('+obj.id[i]+',"'+obj.status+'")');
		$('#audit_' + obj.id[i]).removeAttr('title');
		$('#audit_' + obj.id[i]).attr('title', con );
		$("#audit_" + obj.id[i]).css('color', color);
	
	}
	
}





