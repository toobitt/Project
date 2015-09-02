{template:head}
{template:head/nav}
{css:ad_style}
{css:mms_control_list}
{code}
/*hg_pre($formdata);*/
{/code}
<script type="text/javascript">

	function hg_beibo_files_css(obj)
	{
		$("a[id^='beibo_file_']").css('font-weight','');
		$(obj).css('font-weight','bold');
	}
	function hg_button_disabled(obj)
	{
		
		$("span[class^=btn]").attr('canclick','true');
		$("span[class^=btn]").removeClass('btn2');
		$(obj).attr('canclick','false');
		$(obj).addClass('btn2');
	}
	function hg_play_url(id, url,name)
	{
		if(!url)
		{
			url = null;
			return;
		}
		$('#current_bkfile').val(url);
		$('#current_bkfile_id').val(id);
		document.getElementById('backup_colorollor').play(name, url);
	}
	var gControllor = {};
	
	gControllor.mainStream = '';
	gControllor.Stream = '{$formdata[primary_stream_url][0]}';
	gControllor.curStreams = {};
	gControllor.curStreams['stream{$formdata['stream_id']}'] = {name:'{$formdata['stream_display_name']}', url:'{$formdata['primary_stream_url'][0]}'};
	gControllor.change = function(name, url, source)
	{
		if (this.Stream == url)
		{
			return;
		}
		var canSwitch = true;
		if (source) /*当是来自状态检测的请求时*/
		{
			if (this.mainStream == this.Stream) /*当主信号与当前信号一致时，主信号随自动检测到的信号变动*/
			{
				canSwitch = true;
			}
			else 
			{
				if(this.mainStream != '') /*第一次设置主信号，同时信号随变动切换*/
				{
					canSwitch = false;
				}
				else
				{
					this.mainStream = url;
				}
			}
		}
		else /*非来自状态检测的请求直接切换流*/
		{
			canSwitch = true;
		}
		if (!canSwitch)
		{
			return;
		}
		this.Stream = url;
		document.getElementById('pri_big_colorollor').play(name, url);
	};
	/*
	function hg_play_in_pri(url,name,ch_id)
	{
		document.getElementById('pri_big_colorollor').setStream(ch_id, name, url);
	}
	*/
	function hg_close_output_stream()
	{
		$('#view_output_stream').attr('title', '打开');
		$('#view_output_stream').text('打开输出流');
		document.getElementById('pri_big_colorollor').picIn('',null,'');
		$('#view_output_stream').one('click', hg_view_output_stream);
	}
	function hg_view_output_stream()
	{
		var url = $('#current_down_stream_name').val();
		var drmUrl = $('#drmUrl').val();
		var name = '输出流';
		$('#view_output_stream').removeAttr('onclick');
		$('#view_output_stream').attr('title', '关闭');
		$('#view_output_stream').text('关闭输出流');
		document.getElementById('pri_big_colorollor').picIn(name,url,drmUrl);
		$('#view_output_stream').one('click', hg_close_output_stream);
	}
	function hg_stream_qiebo(obj,channel_id,stream_id,source)
	{
		/*$('#view_output_stream').attr('onclick',hg_view_output_stream);*/
		if($(obj).attr('canclick')=="true")
		{
			hg_emergency_change(channel_id,stream_id,source);
			
		}
		else
		{
			return;
		}
		
	}
	function hg_emergency_change(channel_id,stream_id,source)
	{
		if(stream_id)
		{
			var url = "./run.php?mid="+gMid+"&a=emergency_change&channel_id=" + channel_id + "&stream_id=" + stream_id;
		}
		else
		{
			var source = $('#current_bkfile').val();
			var bkfile_id = $('#current_bkfile_id').val();
			gControllor.curStreams['file' + bkfile_id] = source;
			var url = "./run.php?mid="+gMid+"&a=emergency_change&channel_id=" + channel_id + "&source=" + source + '&bkfile_id='  + bkfile_id;
		}
		hg_ajax_post(url,'','','hg_live_emergency_change');
		
	}
	function hg_live_emergency_change(obj)
	{	
		var obj = obj[0];
		try
		{
			var strid = obj['stream_id'];
		}
		catch (e)
		{
			return;
		}
		/*
		var name = gControllor.curStreams[obj['chg_type'] + obj['stream_id']]['name'];
		var url = gControllor.curStreams[obj['chg_type'] + obj['stream_id']]['url'];
		gControllor.change(name, url);
		*/
		var bobj = $('#chg_' + obj['chg_type'] + obj['stream_id']);
		hg_button_disabled(bobj);
	}
	function hg_resume(channel_id)
	{
		var url = "./run.php?mid="+gMid+"&a=resume&channel_id=" + channel_id;
		hg_ajax_post(url,'','','hg_live_resume');
	}
	function hg_live_resume(obj)
	{
		/*
		var name = gControllor.curStreams[obj['chg_type'] + obj['stream_id']]['name'];
		var url = gControllor.curStreams[obj['chg_type'] + obj['stream_id']]['url'];
		gControllor.change(name, url);
		*/
	}
	
	var stream_status_id = "";
	function  hg_output_stream_status(id)
	{
		stream_status_id = id;
		var url = "./run.php?mid=" + gMid + "&a=stream_state&id=" + id + "&infrm=1";
		hg_ajax_post(url,"","",'hg_stream_states');
	}
	function hg_stream_states(obj)
	{
		var obj = obj[0];
		if(obj.status == '1')
		{
			$('#a_' + stream_status_id).html('停止流输出');
			$('#a_' + stream_status_id).attr('title','已启动流');
			$('#out_uri_' + stream_status_id +' a').css('background-color','#8CC7ED');
		}
		else
		{
			$('#a_' + stream_status_id).html('启动流输出');
			$('#a_' + stream_status_id).attr('title','已停止流');
			$('#out_uri_' + stream_status_id +' a').css('background-color','');
		}
	}

	$(function(){
		var channel_id = '{$formdata["id"]}';
		var url = "./run.php?mid="+gMid+"&a=status&channel_id=" + channel_id;
		var get_status = function(){
			hg_request_to(url, '', 'get', 'hg_liv_status', 1);
			setTimeout(get_status, 5000);
		};
		setTimeout(get_status, 1000);
	});

	function hg_liv_status(obj)
	{
		var obj = obj[0];
		var name, url;
		switch (obj.chg_status)
		{
		   case "normal": 
			    $('#liv_status').html("正在直播中...");
				if (obj['stream_id'])
				{
					var bobj = $('#chg_stream' + obj['stream_id']);
					name = gControllor.curStreams['stream' + obj['stream_id']]['name'];
					url = gControllor.curStreams['stream' + obj['stream_id']]['url'];
					gControllor.change(name, url, 1);
					hg_button_disabled(bobj);
				}
				hg_getNowChange();
		   break;
		  
		   case "error": $('#liv_status').html("切播服务不可用");
		   break; 
		   
		   case "init": $('#liv_status').html("正在联系直播流");
		   break; 

		   case "empty": $('#liv_status').html("正在播放底片");
		   break; 

		   case "Failed": $('#liv_status').html("切播服务未启动");
		   break; 

		   case "switching": $('#liv_status').html("正在切播中...");
		   break;

		   case "emergency": 
				if (obj['chg2_stream_id'] != '0')
				{
					name = gControllor.curStreams[obj['chg_type'] + obj['chg2_stream_id']]['name'];
					url = gControllor.curStreams[obj['chg_type'] + obj['chg2_stream_id']]['url'];
					gControllor.change(name, url, 1);
					var bobj = $('#chg_' + obj['chg_type'] + obj['chg2_stream_id']);
					hg_button_disabled(bobj);
					name = '信号:' + name;
				}
				else
				{
					 name = '备播文件';
				}
				$('#liv_status').html("切播成功，正在播放" + name);
		   break;

		   default: $('#liv_status').html("未知状态");
		}
		$('#show_live_delay').html(obj['live_delay']);
	}
	/*获取当前串联单*/
	function hg_getNowChange()
	{
		var channel_id = '{$formdata["id"]}';
		var url = './run.php?mid=' + gMid + '&a=getNowChange&channel_id=' + channel_id;
		hg_ajax_post(url);
	}
	function getNowChange_back(obj)
	{
		var obj = eval('('+obj+')');
		if(obj != null && obj.length !=0)
		{
			$('#now_change').html('['+obj+']');
		}
		else
		{
			$('#now_change').html('');
		}
	}
	$(function(){
		setTimeout(hg_view_output_stream, 2000);
	});
</script>
<div class="ad_middle" style="width:825px">
<h2 class="show_channel_info">
	<span class="t1">{$formdata['name']}播控</span>
	<span class="t2">当前信号流:[{$formdata['stream_display_name']}]</span>
	<span class="t2" id="liv_status"></span>
	<span class="t2" id="now_change"></span>
	<span class="stream_status">
		<a {if $formdata['stream_state']}title="已启动流"{else}title="已停止流"{/if} id="a_{$formdata['id']}" onclick="hg_output_stream_status({$formdata['id']});" href="javascript:void(0)">{if $formdata['stream_state']}停止输出流{else}启动输出流{/if}</a>
	</span>
</h2>
<div class="zb_div">
	<div class="zb_left">
    	<div class="zb_ck">
		<object id="pri_big_colorollor" type="application/x-shockwave-flash" data="{$RESOURCE_URL}swf/mms_control.swf?12012901" width="404" height="309">
			<param name="movie" value="{$RESOURCE_URL}swf/mms_control.swf?12012901"/>
			<param name="allowscriptaccess" value="always">
			<param name="wmode" value="transparent">
			<param name="flashvars" value="streamName={$formdata['stream_display_name']}&streamUrl={$formdata[primary_stream_url][0]}&connectName=synTime_{code}echo TIMENOW;{/code}&keepAliveUrl=keep_alive.php?access_token={$_user['token']}&clientCount=4">
		</object>
		<input type="hidden" id="drmUrl" value="{if $formdata['drm']}{$_configs['antileech']}{/if}" />
		<!--
		<img src="{$RESOURCE_URL}tu2.jpg" title="name={$formdata['name']}&id={$formdata['ch_id']}&url={$formdata[primary_stream_url][0]}&keepAliveUrl=http://vapi.thmz.com/api/player/swf/keep_alive.php"/>
		-->
		</div>
        <div class="qh_div">
        	<div class="left"></div>
            <div class="center" id="qb_button">
			<div id="debug"></div>
				<div class="ys_div ys_div_a">切播到:</div>
            	<span id="chg_stream{$formdata['stream_id']}" class="btn overflow {if !$formdata['chg2_stream_id'] || $formdata['chg2_stream_id'] == $formdata['stream_id']} btn2{/if}"  id="primary_stream_name"  onclick="hg_stream_qiebo(this,{$formdata['id']},{$formdata['stream_id']},'');" {if $formdata['chg2_stream_id']} canclick="true"{else} canclick="false"{/if}>
					{$formdata['stream_display_name']}
				</span>
				{if $formdata['beibo']}
					{foreach $formdata['beibo'] as $beibo_id=>$beibo_name}
					{code}
					if ($formdata['chg_type'] . $formdata['chg2_stream_id'] == 'stream' . $beibo_id)
					{
						$class = ' btn2';
						$disabled = ' canclick="false"';
					}
					else
					{
						$class = '';
						$disabled = ' canclick="true"';
					}
					{/code}
					<span class="btn overflow {$class}" id="chg_stream{$beibo_id}" onclick="hg_stream_qiebo(this,'{$formdata['id']}','{$beibo_id}','');" {$disabled}>
						{$beibo_name}
					</span>
					{/foreach}
				{/if}
				<span id="chg_file0" class="btn overflow {if $formdata['chg_type'] == 'file' && $formdata['chg2_stream_id']} btn2{/if}"  {if $formdata['chg_type'] == 'file' && $formdata['chg2_stream_id']} canclick="false"{else} canclick="true"{/if} onclick="hg_stream_qiebo(this,{$formdata['id']},'','');"  >
				备播
				</span>
               <div class="clr"></div>
               <div class="ys_div">
                  <span><a href="javascript:void(0)" onclick="hg_view_output_stream($('#current_down_stream_name').val())" title="查看输出流" id="view_output_stream">查看输出流</a><input type="hidden" id="current_down_stream_name" value="{$formdata['down_stream_url'][0]}"></span>当前延时<span id="show_live_delay" style="float: none;padding: 0 2px;">{$formdata['live_delay']}</span>分钟
               </div>
            </div>
            <div class="right"></div>
        </div>
    </div>
    <div class="zb_right">
    	<ul class="sp_pic">
        	<li>
			<object id="pri_small_colorollor" type="application/x-shockwave-flash" data="{$RESOURCE_URL}swf/mms_player.swf?12012901" width="190" height="150">
			<param name="movie" value="{$RESOURCE_URL}swf/mms_player.swf?12012901"/>
			<param name="allowscriptaccess" value="always">
			<param name="wmode" value="transparent">
			<param name="flashvars" value="mute=true&streamName={$formdata['stream_display_name']}&streamUrl={$formdata[primary_stream_url][0]}&connectName=synTime_{code}echo TIMENOW;{/code}&connectIndex=1&jsNameSpace=gControllor">
			</object></li>
			<!--
				同步索引从0-4
			-->
			{code}
				$syn_index = 2;
				$js_streams = '';
			{/code}
			{if $formdata['beibo_stream_urls']}
				{foreach $formdata['beibo_stream_urls'] as $beibo_id=>$beibo_stream_url}
				{code}
					$js_streams .= 'gControllor.curStreams[\'stream' . $beibo_id . '\'] = {name:\'' . $formdata['beibo'][$beibo_id] . '\', url:\'' . $beibo_stream_url[0] . '\'};';
				{/code}
				<li>
				<object type="application/x-shockwave-flash" data="{$RESOURCE_URL}swf/mms_player.swf?12012901" width="190" height="150">
				<param name="movie" value="{$RESOURCE_URL}swf/mms_player.swf?12012901"/>
				<param name="allowscriptaccess" value="always">
				<param name="wmode" value="transparent">
				<param name="flashvars" value="mute=true&streamName={$formdata['beibo'][$beibo_id]}&streamUrl={$beibo_stream_url[0]}&connectName=synTime_{code}echo TIMENOW;{/code}&connectIndex={$syn_index}&jsNameSpace=gControllor">
				</object>
				{code}
					$syn_index++;
				{/code}
				</li>
				{/foreach}
			{/if}
            <li>
			<!--备播flash-->
			<object id="backup_colorollor" type="application/x-shockwave-flash" data="{$RESOURCE_URL}swf/backup.swf?12012901" width="190" height="150">
			<param name="movie" value="{$RESOURCE_URL}swf/backup.swf?12012901"/>
			<param name="allowscriptaccess" value="always">
			<param name="wmode" value="transparent">
			<param name="flashvars" value="mute=true&streamName={$all_beibo_files[0]['title']}&streamUrl={$all_beibo_files[0]['beibo_file_url']}&connectName=synTime_{code}echo TIMENOW;{/code}&connectIndex={$syn_index}&jsNameSpace=gControllor">
			</object>
			<input type="hidden" id="current_bkfile" value="{$all_beibo_files[0]['beibo_file_url']}" name="current_bkfile" />
			<input type="hidden" id="current_bkfile_id" value="{$all_beibo_files[0]['id']}" name="current_bkfile_id" />
			</li>
        </ul>
		<!--
		时间同步FLASH
		
		<object id="keep" type="application/x-shockwave-flash" data="{$RESOURCE_URL}swf/keep.swf" width="1" height="1">
			<param name="movie" value="{$RESOURCE_URL}swf/keep.swf"/>
			<param name="allowFullScreen" value="true">
			<param name="allowscriptaccess" value="always">
			<param name="flashvars" value="id={$formdata['id']}&url=http://vapi.thmz.com/api/player/live/keep_alive.php&connectName=synTime_{code}echo TIMENOW;{/code}">
		</object>-->
        <div class="clr"></div>
        <div class="jm_div">
        	<div class="top"></div>
            <div class="center">
            	<ul>
				{if $all_beibo_files}
					{foreach $all_beibo_files as $k=>$v}
						<li><span>{code}echo $k+1 {/code}</span>
						<a style="{if $k==0}font-weight:bold;{/if}"id="beibo_file_{$k}" href="javascript:void(0);" onclick="hg_play_url('{$v[id]}','{$v[beibo_file_url]}','{$v['title']}'), hg_beibo_files_css(this);" title="{$v['title']}">{$v['title']}</a>
						</li>
					{/foreach}
				{/if}
                </ul>
            </div>
            <div class="bottom"></div>
        </div>
    </div>
</div>
</div>
<div class="right_version">
		<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
</div>
<script type="text/javascript">
{$js_streams}
</script>
{template:foot}
