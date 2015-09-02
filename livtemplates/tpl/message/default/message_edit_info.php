
{if $formdata['id']}
<!--{code}$v = $formdata;{/code}-->
<!--
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
-->

<span onclick="hg_close_opration_info();" title="关闭/ALT+Q" style="background: url('../../.././../livtemplates/tpl/lib/images/bg-all.png') -67px -70px no-repeat;width:26px;height:26px;top:2px;right:3px;display:inline-block;font-size:0;cursor:pointer;position:absolute;"></span> 
<div class="info clear cz"  id="vodplayer_{$formdata['id']}">
	<ul id="video_opration" class="clear" style="border:0;">
		
		<li>
			 <span>{$v['content']}</span>
		</li>
	</ul>
</div>
<div class="info clear cz"  >
	<ul id="video_opration" class="clear" style="border:0;">
		
		<li>
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=reply&id={$v['id']}&tablename={$v['tablename']}&infrm=1">回复</a>
		</li>
		<li>
			<a class="button_4"  href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&tablename={$v['tablename']}&infrm=1">编辑</a>
		</li>
		{if $formdata['state'] != '已审核'}
		<li>
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=audit&id={$formdata['id']}&audit=1&tablename={$v['tablename']}" onclick="return hg_ajax_post(this, '审核', 0,'hg_change_comment_status');">审核</a>
		</li>
		{else}
		<li>
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=audit&id={$formdata['id']}&audit=2&tablename={$v['tablename']}" onclick="return hg_ajax_post(this, '打回', 0,'hg_change_comment_status');">打回</a>
		</li>
		{/if}
		<li>
			<a class="button_4" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}&tablename={$v['tablename']}">删除</a>
		</li>
	</ul>
</div>
<div class="info clear vo">
	<h4 onclick="hg_slide_up(this,'con_textinfo')"><span title="展开\收缩"></span>内容属性</h4>
	<ul id="con_textinfo" class="clear">	    
		<li class="h"><span>留言对象:</span>{$formdata['content_title']}</li>
		<li class="h"><span>标题：</span>{$formdata['title']}</li>
		<li class="h"><span>状态：</span>{$formdata['state']}</li>
		<li class="h"><span>分组：</span>{$formdata['groupname']}</li>
	</ul>
</div>
{else}
	此留言不存在,请刷新页面更新
{/if}

