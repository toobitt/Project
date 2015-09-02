{template:head}
{css:vod_style}
{css:edit_video_list}
{template:list/common_list}
{css:live_beibowj}
{js:vod_opration}
{code}
$livmedia = $live_backup_list['livmedia'];
if ($_INPUT['server_id'])
{
	$server_info = $live_backup_list['server_info'];
	unset($live_backup_list['server_info']);
}
unset($live_backup_list['livmedia']);
$list = $live_backup_list;
{/code}

<script type="text/javascript">
function hg_backup_flv(id)
{
	var box = $('#flv_box').show();
	box.css({
	    /*position : 'fixed',*/
	    left : ($(window).width() - box.width())/ 2,
	    /*top : ($(parent.window).height() - 100 - box.height()) / 2*/
	    top : $(parent.window.document).scrollTop() + 100
	});
	var name = $('#name_' + id).html();
	var uri = $('#hidden_uri_' + id).val();
	if(uri)
	{
		$('#flv_box').html('<div class="flv-close"><span id="flv_colse" onclick="hg_flv_close();" title="关闭"></span></div><object id="backup_colorollor" type="application/x-shockwave-flash" data="{$RESOURCE_URL}swf/backup.swf?12012901" width="400" height="300"><param name="movie" value="{$RESOURCE_URL}swf/backup.swf?12012901"/><param name="allowscriptaccess" value="always"><param name="wmode" value="transparent"><param name="flashvars" value="mute=false&streamName='+name+'&streamUrl='+uri+'&connectName=synTime_{code}echo TIMENOW;{/code}&connectIndex={$syn_index}&jsNameSpace=gControllor"></object>');
	}
}
function hg_flv_close()
{
	$('#flv_box').hide();
	$('#flv_colse').hide();
	$('#backup_colorollor').remove();
}
$(function(){
	/*$("#flv_box").draggable({containment:'document'});*/
});
</script>
<script type="text/javascript">
	/*打开投票滑动窗口*/
function hg_fastAddStreamForm()
{
	if($('#streamInfo').css('display')=='none')
	{
		$('#streamInfo').css({'display':'block'});
		$('#streamInfo').animate({'right':'45%','margin-right':'-300px'},'normal',function()
		{
			hg_resize_nodeFrame();
		});
	}
}
/*关闭滑动窗口*/
function hg_closeStreamForm()
{
	$('#streamInfo').animate({'right':'120%'},'normal',function(){
		$('#streamInfo').css({'display':'none','right':'0'});
		hg_resize_nodeFrame();
	});
	
	if ($('input[name="s_name"]').val())
	{
		$('input[name="s_name"]').val('');
	}
	
	if ($('input[name="ch_name"]').val())
	{
		$('input[name="ch_name"]').val('');
	}
	
	if ($('input[name="name_0"]').val())
	{
		$('input[name="name_0"]').val('');
	}
	
	if ($('#no_uri_0').val())
	{
		$('#no_uri_0').val('');
	}
	
	if ($('input[name="uri_0"]').val())
	{
		$('input[name="uri_0"]').val('');
	}
	$('#sourceNameBox').html('');
}

function hg_fastAddStream()
{
	var flag = '',
		server_flag = '',
		i = 0,
		server_id = 0;
	$('input[name^="infolist"]').each(function(){
		if ($(this).attr('checked') == 'checked')
		{
			flag = 1;
			
			if (i == 0)
			{
				server_id = $(this).attr('server_id');
			}
			
			if (server_id != $(this).attr('server_id'))
			{
				server_flag = 1;
			} 
			i ++;
		}
	});
	
	if (!flag)
	{
		jAlert('至少选择一个备播文件');
		return false;
	}
	if (server_flag)
	{
		jAlert('请选择同一台服务器备播文件');
		return false;
	}
	
	$('#_server_id').val(server_id);
	
	hg_fastAddStreamForm();
	
	if($('#streamInfo').css('display')=='block' && !$.trim($('#sourceNameBox').html()))
	{
		$('input[name^="infolist"]').each(function(){
			if ($(this).attr('checked') == 'checked')
			{
				hg_checkBackup2Stream($(this).attr('value'));
			}
		});
	}
}

function hg_checkBackup2Stream(id)
{
	if ($('#primary_key_' + id).attr('checked') == 'checked')
	{
		var videofileid = $('#videofileid_' + id).val();
		var videofilename = $('#videofilename_' + id).val();
		var server_id = $('#primary_key_' + id).attr('server_id');
		var input = '<input id="source_name_'+id+'" type="hidden" name="source_name_0[]" value="' + videofileid + '" />';
			input += '<input id="backup_title_'+id+'" type="hidden" name="backup_title_0[]" value="' + videofilename + '" />';
			input += '<input id="server_id_'+id+'" type="hidden" name="server_id_0[]" value="' + server_id + '" />';
		$('#sourceNameBox').append(input);
	}
	else
	{
		$('#source_name_' + id).remove();
		$('#backup_title_' + id).remove();
		$('#server_id_' + id).remove();
	}
}
</script>
<script type="text/javascript">

var gBackupId = '';
function hg_delBackup(obj, id , name)
{
	var tmp = obj;
	obj = hg_find_nodeparent(obj, 'FORM');
	var ids = hg_get_checked_id(obj);

	if (id)
	{	
		gBackupId = id;
	}
	else
	{
		gBackupId = ids;
	}
	
	if(typeof jAlert != 'undefined')
	{
		if(!gBackupId)
		{
			jAlert('请选择要' + name + '的记录', name + '提醒').position(tmp);
			return false;
		}

		var url = './run.php?mid=' + gMid + '&a=check_backup&id=' + gBackupId;
		hg_ajax_post(url,'', '', 'check_backup_back');
	}
}

function check_backup_back(obj)
{
	var obj = obj[0];
	if (obj)
	{
		var stream = '';
		if (obj.stream)
		{
			var streamName = '';
			for (var i in obj.stream)
			{
				streamName += obj.stream[i] + ' ';
			}
			stream = '[' + streamName + '] 信号流 正在使用 该备播文件<br/>';
		}
		
		var chg = '';
		if (obj.chg)
		{
			var chgName = '';
			for (var i in obj.chg)
			{
				chgName += obj.chg[i] + ' ';
			}
			chg = '[' + chgName + '] 频道 正在切播 该备播文件<br/>';
		}
		
		var chg_plan = '';
		if (obj.chg_plan)
		{
			var chgPlanName = '';
			for (var i in obj.chg_plan)
			{
				chgPlanName += obj.chg_plan[i] + ' ';
			}
			chg_plan = '[' + chgPlanName + '] 串联单 正在使用 该备播文件<br/>';
		}
		
		var change_plan = '';
		if (obj.change_plan)
		{
			var changePlanName = '';
			for (var i in obj.change_plan)
			{
				changePlanName += obj.change_plan[i] + ' ';
			}
			change_plan = '[' + changePlanName + '] 串联单计划 正在使用 该备播文件<br/>';
		}
		
		gConfirm = stream + chg + chg_plan + change_plan;
		jAlert(gConfirm + '<br/><span style="color:red;">不能删除！！！</span>');
		/*
		if (confirm('确定删除' + gConfirm + '？'))
		{
			var url = './run.php?mid=' + gMid + '&a=delete&id=' + gBackupId;
			hg_ajax_post(url);
		}
		*/
	}
	else
	{
		if (confirm('确定删除该条备播文件吗？'))
		{
			var url = './run.php?mid=' + gMid + '&a=delete&id=' + gBackupId;
			hg_ajax_post(url);
		}
	}
}
</script>
<body class="biaoz">
<div id="hg_page_menu" class="head_op_program"{if $_INPUT['infrm']} style="display:none"{/if}>
	{if $livmedia}
	<a class="blue mr10"  href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" target="formwin">
	               <span class="left"></span>
	               <span class="middle"><em class="add">新增备播文件</em></span>
	               <span class="right"></span>
	</a>
	{/if}
</div>
<!-- 搜索 -->
	<div class="content clear">
		<div class="right v_list_show" style="float:none;">
			<div class="search_a" id="info_list_search">
			    <span class="serach-btn"></span>
				<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
					<div class="select-search">
						
						{code}
							$_attr_status = array(
								'class' => 'transcoding down_list',
								'show' => '_status_show_',
								'width' => 100,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
								'is_sub'=> 0,
							);
							
							$_INPUT['status'] = $_INPUT['status'] ? $_INPUT['status'] : -1;
							$_configs['backup_status'][-1] = '所有状态';
							
							
							
						{/code}
						{template:form/search_source,status,$_INPUT['status'],$_configs['backup_status'],$_attr_status}
						{if !empty($server_info)}
							{code}
								$_attr_server = array(
									'class' => 'transcoding down_list',
									'show' => '_server_show_',
									'width' => 100,/*列表宽度*/
									'state' => 0,/*0--正常数据选择列表，1--日期选择*/
									'is_sub'=> 0,
								);
								
								$_INPUT['server_id'] = $_INPUT['server_id'] ? $_INPUT['server_id'] : -1;
								$server[-1] = '所有服务器';
								
								foreach($server_info AS $kk =>$vv)
								{
									$server[$vv['id']] = $vv['name'];
								}
							{/code}
							{template:form/search_source,server_id,$_INPUT['server_id'],$server,$_attr_server}
						{/if}
						<input type="hidden" name="a" value="show" />
						<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
						<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
					</div>
					<div class="text-search">
						<div class="button_search">
							<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
						</div>
						{template:form/search_input,k,$_INPUT['k']}                        
					</div>
				</form>
			</div>
		</div>
	</div>
<!-- 搜索 -->
<!-- 快速添加信号流模板开始 -->
<div id="infotip" class="ordertip" ></div>
<div id="streamInfo"  class="single_upload" style="min-height:100px;width:750px;">
	<h2><span class="b" onclick="hg_closeStreamForm();"></span>快速添加信号流</h2>
	<div id="streamInfoForm">
		{template:unit/fastAddStreamForm}
	</div>
</div>
<!-- 快速添加信号流模板结束 -->

<div class="common-list-wrap">
{if !($list && $livmedia)}
	<p style="color: #da2d2d; text-align: center; font-size: 20px; line-height: 50px; font-family: Microsoft YaHei;">{if $livmedia}没有您要找的内容！{else}媒体库应用没有安装，无法使用！{/if}</p>
	<script>hg_error_html('p',1);</script>	
{else}
	<form method="post" action="" name="listform" style="position:relative;" >
		<ul class="common-list">
			<li class="common-list-head clear public-list-head">
				<div class="common-list-left">
					<div class="common-list-item paixu">
					  <a class="fz0">排序</a>
					</div>
				</div>
				<div class="common-list-right">
				    <!--  <div class="common-list-item live-guanli wd100">管理</div>-->
				    {if !empty($server_info)}
					<div class="common-list-item live-name wd90">所属服务器</div>
					{/if}
					<div class="common-list-item live-name wd150">文件名</div>
<!-- 					<div class="common-list-item live-fabu">发布时间</div> -->
					<div class="common-list-item live-zhuangtai wd60">状态</div>
					<div class="common-list-item live-yonghu wd100">添加人/时间</div>
				</div>
				<div class="common-list-biaoti">
					<div class="common-list-item">标题 </div>
				</div>
			</li>
		</ul>
		<ul class="common-list backup-list public-list" id="backuplist">
		{foreach $list as $k => $v}
			<li class="common-list-data clear" id="r_{$v['id']}" name="{$v['id']}">
				<div class="common-list-left paixu">
					<div class="common-list-item ">
							<a name="alist[]">
								<input onclick="hg_checkBackup2Stream({$v['id']});" id="primary_key_{$v['id']}" type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}" server_id="{$v['server_id']}" />
							</a>
					</div>
				</div>
				<div class="common-list-right ">
				<!-- 
				    <div class="common-list-item live-guanli wd100">
							<a title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">编辑</a>
							<a onclick="hg_delBackup(this, {$v['id']} , '删除');" href="javascript:void(0);">删除</a>
					</div> -->
					<div class="common-list-item live-name wd90">
							<span class="common-list-overflow">{$v['server_name']}</span>
					</div>
					<div class="common-list-item live-name wd150">
							<span class="common-list-overflow max-wd150">{$v['filename']}</span>
					</div>
<!--
					<div class="common-list-item live-fabu">
						<div class="common-list-cell">
							<span class="overflow live-durati">{$v['create_time']}</span>	
						</div>
					</div>	
-->
					<div class="common-list-item live-zhuangtai wd60">
							{if $v['status'] == 1}<span style="color:#17b202;">已上传</span>{else if $v['status'] == 2}<span style="color:#f8a6a6;">上传失败</span>{else}<span>上传中</span>{/if}
					</div>
					<div class="common-list-item live-yonghu wd100">
							<span class="bb-name">{$v['user_name']}</span>
							<span class="bb-time">{$v['create_time']}</span>
					</div>
				</div>
				<div class="common-list-biaoti min-wd">
				  {if $v['img']}
					<div class="common-list-item biaoti-content">
							<span id="img_{$v['id']}"><img width=40 height=30 src="{$v['img']}" style="margin-right:10px;vertical-align:middle;" /></span>
					</div>
				  {/if}
					<div class="common-list-item biaoti-content biaoti-transition min-wd280">
						<a title="点击编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
							<span id="name_{$v['id']}" class="common-list-overflow fz14">{$v['title']}</span>
							<span id="toff" style="margin-left:5px;" class="live-duration">{if $v['toff']}{$v['toff']}{/if}</span>
						</a>
					</div>
				</div>
				<div class="common-list-i"  onclick="hg_show_opration_info({$v['id']});"></div>
				<input type="hidden" id="hidden_uri_{$v['id']}" value="{$v['file_uri']}" />
				<input type="hidden" id="videofileid_{$v['id']}" value="{$v['fileid']}" />
				<input type="hidden" id="videofilename_{$v['id']}" value="{$v['title']}" />
			</li>
		{/foreach}	
		</ul>
		<ul class="common-list public-list">
			<li class="common-list-bottom clear">
				<div class="common-list-left">
					<input type="checkbox" name="checkall"  value="infolist" title="全选" rowtag="LI" /> 
			<!-- 	<a onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');" name="batdelete">批量删除</a> -->
					<a onclick="hg_delBackup(this, '' , '删除');">批量删除</a>
					<a onclick="hg_fastAddStream();" >快速添加信号流</a>
				</div>
				{$pagelink}
			</li>
		</ul>
		<div class="edit_show">
			<span class="edit_m" id="arrow_show" style="position:absolute;"></span>
			<div id="edit_show"></div>
		</div>
	</form>
{/if}
</div>	

<div style="cursor:move;position:absolute;top:81px;left: 30%;width:400px;height:300px;background:#000000;display:none;border-radius:3px;box-shadow: 0 0 10px #000000;" id="flv_box"></div>
</body>

{template:foot}