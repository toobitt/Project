/*----------------获取备播文件信息---------------------*/

/*备播文件标题显示与隐藏*/
function hg_backupTitleShow(obj, type)
{
	if (type == 'show')
	{
		$(obj).parent().find('div').show();
		var sid = ($(obj).attr('sid')*1)%10;
		if (!sid)
		{
			$(obj).parent().find('div').css({'position':'relative','left':'-51px'});
		}
	}
	else if (type == 'hide')
	{
		$(obj).parent().find('div').hide();
	}
	
}
/*删除备播文件按钮显示和隐藏*/
function hg_getBackupDeleteShow(obj, type)
{
	if (type == '1')
	{
	//	$(obj).css('background','#97C3E6');
		$(obj).find('span[delName^="getBackupDelete"]').show();
	}
	else if (type == '0')
	{
	//	$(obj).css('background','#F5F6F8');
		$(obj).find('span[delName^="getBackupDelete"]').hide();
	}
	
}
/*删除备播文件*/
function hg_getBackupDelete(obj)
{
	$(obj).parent().remove();
	hg_resize_nodeFrame();
}
/*获取备播文件名*/
function hg_postBackupVideoFileName(obj, id)
{
	if ($(obj).parent().parent().attr('id') == 'undefined')
	{
		
	}
	else
	{
		var backupList = $(obj).parent().parent().attr('id');
		var i = backupList.substr(11);
		if (!i)
		{
			return false;
		}
	}
	
	var backupTitle = $('#backupTitle_' + id).val();
	var backupId = $('#backupId_' + id).val();
	var videFileName = $('#videFileName_' + id).val();

	var li = '<li onmouseover="hg_getBackupDeleteShow(this, 1);" onmouseout="hg_getBackupDeleteShow(this, 0);">';
		li += '<input type="hidden" name="source_name_' + i + '[]" value="' + videFileName + '" />';
		li += '<input type="hidden" name="backup_title_' + i + '[]" value="' + backupTitle + '" />';
		li += '<span class="getBackupTitle">'+backupTitle+'</span>';
		li += '<span delName="getBackupDelete[]" class="getBackupDelete" title="删除" onclick="hg_getBackupDelete(this);"></span>';
		li += '</li>';
	
	$(obj).parent().parent().prev().append(li);
	hg_backupOrder('getBackup_' + i);
	hg_resize_nodeFrame();
}
/*排序*/
function hg_backupOrder(id)
{
	$('#'+id).sortable({
			revert: true,
			cursor: 'move',
			containment: 'document',
			scrollSpeed: 100,
			tolerance: 'intersect' ,
			axis: 'y',
			start: function(event, ui) {gDragMode = true;},
			change: function(event, ui) {},
			update: function(event, ui) {},
			stop: function(event, ui) {/*alert('stop');*/gDragMode = false;}			
	});
}