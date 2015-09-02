{code}
foreach ($formdata As $k => $v) {
	$$k = $v;
}
//hg_pre($formdata);
{/code}
<div class="info clear vider_s"  id="vodplayer_{$id}}">
	<object type="application/x-shockwave-flash" data="{$RESOURCE_URL}swf/backup.swf?12012901" width="400" height="300">
		<param name="movie" value="{$RESOURCE_URL}swf/backup.swf?12012901"/>
		<param name="allowscriptaccess" value="always">
		<param name="wmode" value="transparent">
		<param name="flashvars" value="mute=false&streamName={$title}&streamUrl={$file_uri}&connectName=synTime_{code}echo TIMENOW;{/code}&connectIndex={$syn_index}&jsNameSpace=gControllor">
	</object>
	<span onclick="hg_close_opration_info();" title="关闭/ALT+Q"></span>
</div>
<div class="info clear cz">
 <div id="video_opration" class="clear common-list" style="border:0;height:auto">
	  <div class="common-opration-list">
	    <a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$id}&infrm=1">编辑</a>
        <a class="button_4" onclick="hg_delBackup(this, {$id} , '删除');" href="javascript:void(0);">删除</a>
      </div>
</div>
</div>
<div class="info clear vo">
	<h4 onclick="hg_slide_up(this,'stream')"><span title="展开\收缩"></span>备播文件</h4>
	<div id="stream" class="channel_info_box">
		<ul class="clear">
			<li class="overflow"><span>文件名：</span>{$filename}</li>
			<li class="overflow"><span>状态：</span>{if $status == 1}<span style="color:#17b202;">已上传</span>{else if $status == 2}<span style="color:#f8a6a6;">上传失败</span>{else}<span>上传中</span>{/if}</li>
			<li class="overflow"><span>添加人：</span>{$user_name}</li>
			<li class="overflow"><span>添加时间：</span>{code}echo date('Y-m-d H:i:s', $create_time);{/code}</li>
		</ul>
	</div>
</div>

