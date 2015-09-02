/*图片的大图显示*/
function show_pic(id) {
	$('#td_' + id + ' a').lightBox();
}
/* 编辑弹出层 */
function hg_edit_records(id,interviewId) {
	var url = './run.php?mid=' + gMid + '&a=form&id=' + id + '&interview_id=' + interviewId;
	hg_ajax_post(url);
	if ($('#edit_records').css('display')=='none'){
		var  Num=75;
		var h = $(window).height();
		var w = $(window).width();
		var scrollTop = $(window).scrollTop();
		$('#edit_records').show().css( {
			'top':(Num+id*40)+'px'
		});
	}else{
		$('#edit_records').hide();
	}
	
}
/*编辑的回调*/
function hg_show_edit(html){
		$("#show_form").html(html);
}
/*更新回调*/
function hg_update_callback(json){
	var json_data = $.parseJSON(json);
	if(json_data['id'])
	{
		$('#edit_records').hide();
	}
}
/*发布状态更新*/
var gPubId = '';
function hg_con_pub(id,state){
	gPubId = id;
	var url = './run.php?mid=' + gMid + '&a=changePub&id='+ id+ '&state='+state;
	hg_ajax_post(url);
}
/*发布回调*/
function hg_changePub_callback(obj){
	if(obj==1){
		$("#pub_"+gPubId).html('<span style="color:green" onclick="hg_con_pub('+gPubId+','+obj+')">已发布</span>');
	}else{
		$("#pub_"+gPubId).html('<span style="color:red" onclick="hg_con_pub('+gPubId+','+obj+')">未发布</span>');
	}
}
/*批量发布回调*/
function hg_pub_callback(json){
	var json_data = $.parseJSON(json);
	for(var a in json_data)
	{
		$("#pub_"+json_data[a]).html('<span style="color:green" onclick="hg_con_pub('+json_data[a]+',1)">已发布</span>');
	}

}
/*批量取消发布*/
function hg_backpub_callback(json){
	var json_data = $.parseJSON(json);
	for(var a in json_data)
	{
		$("#pub_"+json_data[a]).html('<span style="color:red" onclick="hg_con_pub('+json_data[a]+',0)">未发布</span>');
	}	
}