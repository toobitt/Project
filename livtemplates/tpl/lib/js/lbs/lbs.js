
function hg_get_city_by_province(obj)
{
	var url = "run.php?mid="+gMid+"&a=get_city&id="+$(obj).attr('attrid');
	hg_ajax_post(url);
}

function city_back(html)
{
	$('#city_box').html(html);
}

function hg_get_area_by_city(obj)
{
	var url = "run.php?mid="+gMid+"&a=get_area&id="+$(obj).attr('attrid');
	hg_ajax_post(url);
}

function area_back(html)
{
	$('#area_box').html(html)
}

function show_map()
{
	var url= './run.php?mid='+gMid+'&a=get_map&id='+$('#address').val();
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