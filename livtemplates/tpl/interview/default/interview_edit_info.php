{code}
$image_resource = RESOURCE_URL;
{/code}
{if $formdata['id']}
<div class="info clear vider_s"  id="vodplayer_{$formdata['id']}">
  <div id="contribute_pics_show" class="tuji_pics_show">
  	 {if $formdata['vodid']}
  	 <object id="vodPlayer" type="application/x-shockwave-flash" data="{code}echo RESOURCE_URL{/code}swf/vodPlayer.swf?11122713" width="400" height="330">
		<param name="movie" value="{code}echo RESOURCE_URL{/code}swf/vodPlayer.swf?11122713">
		<param name="allowscriptaccess" value="always">
		<param name="allowFullScreen" value="true">
		<param name="wmode" value="transparent">
		<param name="flashvars" value="startTime={$formdata['start']}&duration={$formdata['duration']}&videoUrl={$formdata['video_url']}&videoId={$formdata['vodid']}&snap=false&aspect={$formdata['aspect']}&autoPlay=false&snapUrl={$formdata['snapUrl']}">
	  </object>
	  <span onclick="hg_close_opration_info();" style="position:absolute;z-index:30;" title="关闭/ALT+Q"></span>
	  {else}
	    <div style="color:red;width:100%;height:327px;text-align:center;line-height:327px;font-size:18px;background:#000;border-radius:10px;">此视频不存在</div>
	    <span onclick="hg_close_opration_info();" style="position:absolute;z-index:30;" title="关闭/ALT+Q"></span>
	  {/if}
  </div>
</div>

<div class="info clear cz"  >
	<ul id="video_opration" class="clear" style="border:0;">
		{foreach $_relate_module AS $kkk => $vvv}
		<li>
			<a class="button_4"  href="./run.php?mid={$kkk}&interview_id={$v['id']}&infrm=1" >{$vvv}</a>
		{/foreach}
		</li>
		<li>
			<a class="button_4"   href="./run.php?mid={$_INPUT['mid']}&a=interview_authority&id={$v['id']}&infrm=1">权限</a>
		</li>
		<li>
			<a class="button_4"   href="./run.php?mid={$_INPUT['mid']}&a=form&id={$formdata['id']}&infrm=1">编辑</a>
		</li>
		<li>
			<a class="button_4" onclick="return hg_ajax_post(this, '删除', 1);"  title=""  href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$formdata['id']}">删除</a>
		</li>
	</ul>
</div>

<div class="info clear vo">
	<h4 onclick="hg_slide_up(this,'con_textinfo')"><span title="展开\收缩"></span>内容属性</h4>
	<ul id="con_textinfo" class="clear">	    
		<li class="h"><span>访谈主题：{$formdata['title']}</span></li>
		<li class="h"><span>开始时间：{$formdata['start_time']}</span></li>
		<li class="h"><span>访谈时长：{$formdata['time_out']}</span></li>
		<li class="h"><span>结束时间：{$formdata['end_time']}</span></li>
		<li class="h"><span>是否关闭：{$formdata['isclose']}</span></li>
		<li class="h"><span>预提问：{$formdata['is_pre_ask']}</span></li>
		<li class="h"><span>需要登录：{$formdata['need_login']}</span></li>
		<li class="h"><span>历史：{$formdata['is_lishi']}</span></li>
		<li class="h"><span>主持人：{$formdata['moderator']}</span></li>
		<li class="h"><span>嘉宾：{$formdata['honor_guests']}</span></li>
	</ul>
</div>
{else}
此访谈不存在,请刷新页面更新
{/if}

