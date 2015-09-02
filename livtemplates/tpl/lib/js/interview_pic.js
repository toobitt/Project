/*开启禁用*/
var gDisableId = '';
var gInterviewId = '';
function hg_pic_disable(id,status,vid)
{
	gDisableId = id;
	gInterviewId = vid;
	var url= './run.php?mid='+gMid+'&a=disable&id='+id+'&status='+status;
	hg_ajax_post(url);
	
}
/*禁用的回调*/
function hg_pic_disable_callback(obj)
{
	if (obj == 0)
	{
		$('#disable_' + gDisableId).html('<span  title="点击可禁用图片" onclick="hg_pic_disable('+gDisableId+','+obj+','+gInterviewId+')"  style="color:green">未禁用</span>');
		$('#cover_'+gDisableId).html('<span title="点击设为封面" onclick="hg_pic_cover('+gDisableId+','+gInterviewId+')" style="color:green">设为封面</span>');
	}
	if (obj == 1)
	{
		$('#disable_' + gDisableId).html('<span  style="color:#ff0000"  title="点击可解除禁用"  onclick="hg_pic_disable('+gDisableId+','+obj+','+gInterviewId+')">已禁用</span>');
		$('#cover_'+gDisableId).html('<span>已禁用</span>');
	}
}
/*设置封面*/
var cover_id='';
function hg_pic_cover(id,vid)
{
	cover_id = $("#cover_pic").val();
	var url= './run.php?mid='+gMid+'&a=cover_pic&id='+id+'&vid='+vid+'&cover_id='+cover_id;
	hg_ajax_post(url);
	
}
/*封面的回调函数*/
function hg_cover_pic_callback(json)
{
	var json_data = $.parseJSON(json);
	//cid为0 ，说明此时为取消封面，若不为0，则为重新设置封面
	if(json_data.cid==0)
	{
		$("#cover_pic").val(json_data.cid);
		$("#disable_"+json_data.id).html('<span  title="点击可禁用图片" onclick="hg_pic_disable('+json_data.id+',0)"  style="color:green">未禁用</span>');
		$("#cover_"+json_data.id).html('<span title="点击设为封面" onclick="hg_pic_cover('+json_data.id+','+json_data.vid+')" style="color:green">设为封面</span>');
	}else{
		if(json_data.id !=json_data.cid)
		{
			$("#cover_pic").val(json_data.id);
			$("#disable_"+json_data.id).html('封面图片');
			$("#cover_"+json_data.id).html('<span title="点击设为封面" onclick="hg_pic_cover('+json_data.id+','+json_data.vid+')" style="color:red">取消封面</span>');
			$("#disable_"+json_data.cid).html('<span  title="点击可禁用图片" onclick="hg_pic_disable('+json_data.cid+',0)"  style="color:green">未禁用</span>');
			$("#cover_"+json_data.cid).html('<span title="点击设为封面" onclick="hg_pic_cover('+json_data.cid+','+json_data.vid+')" style="color:green">设为封面</span>');
		}else{
			$("#cover_pic").val(json_data.id);
			$("#disable_"+json_data.id).html('封面图片');
			$("#cover_"+json_data.id).html('<span title="点击设为封面" onclick="hg_pic_cover('+json_data.id+','+json_data.vid+')" style="color:red">取消封面</span>');
		}
	}
	
}
/*显示大图*/
function show_pic(id){
	 $('#pic_' +id+ ' a').lightBox();
}
/*取上传文件名*/
function picChange()
{
	$('#title').val(getFileName($('#Filedata').val()));
}

function getFileName(url)
{
	var pos = url.lastIndexOf("/");
	if(pos == -1)
		pos = url.lastIndexOf("\\")
	var filename = (url.substr(pos +1)).split('.');
	return filename[0];
}
var interviewId = 2;
function hg_addFileDom()
{
	
	var div = "<div class='form_ul_div clear'><span class='title'>图片名：</span><input type='text' style='width:150px;' id='upload"+interviewId+"_name'  name='upload"+interviewId+"_name'/><input type='file' id='upload"+interviewId+"' onchange='uploadChange(this.id)' name='uploadinput"+interviewId+"' style='width: 64px;height:24px;line-height:24px;'/><input type='hidden' name='picid[]' value='"+interviewId+"'/><span class='option_del_box'><span  class='option_del' title='删除' onclick='hg_fileDel(this,0);' style='display: inline;'></span></span></div>";
	$('#addfile').append(div);
	interviewId++;
} 
function hg_fileDel(obj,id)
{
	$(obj).parent().parent().remove();
} 
function uploadChange(id)
{
	$('#'+id+'_name').val(getFileName($('#'+id).val()));
}
