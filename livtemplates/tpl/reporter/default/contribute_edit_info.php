{code}
$image_resource = RESOURCE_URL;
{/code}
{css:jquery.lightbox-0.5}
{js:jquery.lightbox-0.5}
{code}
	//print_r($formdata);
{/code}
{if $formdata['data']['id']}
<div class="info clear vider_s"  id="vodplayer_{$formdata['data']['id']}">
  <div id="contribute_pics_show" class="tuji_pics_show">
  	 {if $formdata['data']['video_url']}
  	 <object id="vodPlayer" type="application/x-shockwave-flash" data="{code}echo RESOURCE_URL{/code}swf/vodPlayer.swf?11122713" width="400" height="330">
		<param name="movie" value="{code}echo RESOURCE_URL{/code}swf/vodPlayer.swf?11122713">
		<param name="allowscriptaccess" value="always">
		<param name="allowFullScreen" value="true">
		<param name="wmode" value="transparent">
		<param name="flashvars" value="startTime={$formdata['data']['start']}&duration={$formdata['data']['duration']}&videoUrl={$formdata['data']['video_url'][0]['url']}&videoId={$formdata['data']['video_url'][0]['vodid']}&snap=false&aspect={$formdata['data']['aspect']}&autoPlay=false&snapUrl={$formdata['data']['snapUrl']}">
	  </object>
	  <span onclick="hg_close_opration_info();" style="position:absolute;z-index:30;" title="关闭/ALT+Q"></span>
	  {else}
        <div class="pub-con">{$formdata['data']['text']}</div>
        <!--  
	    <div style="color:red;width:100%;height:327px;text-align:center;line-height:327px;font-size:18px;background:#000;border-radius:10px;">此视频不存在</div>
	   -->
	    <span onclick="hg_close_opration_info();" style="position:absolute;z-index:30;" title="关闭/ALT+Q"></span>
	  	
	  {/if}
  </div>
</div>
<div class="info clear cz"  >
	<ul id="video_opration" class="clear" style="border:0;">
		<li>
			<a class="button_4"   href="./run.php?mid={$_INPUT['mid']}&a=form&id={$formdata['data']['id']}&infrm=1">编辑</a>
		</li>
		<li>
			<a class="button_4" onclick="return hg_ajax_post(this, '删除', 1);"  title=""  href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$formdata['data']['id']}">删除</a>
		</li>
		<li>
			<a class="button_4" href="javascript:void(0);" onclick="hg_stateAudit({$formdata['data']['id']},{$formdata['data']['audit']},this)"  id ="stateAudit_{$formdata['data']['id']}">{if $formdata['data']['audit']==1}审核{elseif $formdata['data']['audit']==2}打回{elseif $formdata['data']['audit']==3}审核{/if}</a>
			
		</li>
		<li><a class="button_6" href="./run.php?mid={$_INPUT['mid']}&a=recommend&id={$formdata['data']['id']}" onclick="return hg_ajax_post(this, '推荐', 0);">发布至网站</a></li>
		{if $formdata['suobei']['is_open'] && $formdata['data']['video_url']}
		<li>
			<a class="button_6" href="javascript:void(0);" onclick="forwardSuobei({$formdata['data']['id']})"  id ="forward_{$formdata['data']['id']}">{if $formdata['data']['suobei']}重新{$formdata['suobei']['display_name']}{else}{$formdata['suobei']['display_name']}{/if}</a>		
		</li>
		{/if}
	</ul>
	<ul>
		<li  id="pic_show">
			{if $formdata['data']}
			{foreach $formdata['data']['pic'] as $key=>$val}
			{code}
				$big = $val['host'].$val['dir'].$val['file_path'].$val['file_name'];
				$small = $val['host'].$val['dir'].'50x50/'.$val['file_path'].$val['file_name'];
			{/code}
			<a href="{$big}">
				<img alt="" src="{$small}" />
			</a>
			{/foreach}
			{/if}
		</li>
	</ul>
	
</div>




<div class="info clear vo">
	<h4 onclick="hg_slide_up(this,'con_textinfo')"><span title="展开\收缩"></span>内容属性</h4>
	<ul id="con_textinfo" class="clear">	    
		<li class="h"><span>标题：{$formdata['data']['title']}</span></li>
		<li class="h"><span>时间：{$formdata['data']['create_time']}</span></li>
		<li class="h"><span>分类：{$formdata['data']['name']}</span></li>
		<li class="h"><span>审核状态：{$formdata['data']['zt']}</span></li>
		<li class="h"><span>审核意见：{$formdata['data']['opinion']}</span></li>
	</ul>
</div>
<div class="info clear vo">
	<h4 onclick="hg_slide_up(this,'con_userinfo')"><span title="展开\收缩"></span>报料人信息</h4>
	<ul id="con_userinfo" class="clear">	    
		<li class="h"><span>报料人：{$formdata['data']['user_name']}</span></li>
		<li class="h"><span>电话：{$formdata['data']['tel']}</span></li>
		<li class="h"><span>邮件：{$formdata['data']['email']}</span></li>
		<li class="h"><span>住址：{$formdata['data']['addr']}</span></li>
		
		{if $formdata['bounty']}
			{if $formdata['data']['is_bounty']}
			<li class="h"><span>是否付费：<font color="green">已付费</font></span></li>
			<li class="h"><span>赏金：<font color="green">{$formdata['data']['money']}</font></span></li>
			{else}
			<li class="h"><span>是否付费：<font color="red">未付费</font></span></li>
			<li class="h"><span>赏金：<font color="green"></font></span></li>
			{/if}
			
		{/if}
		
	</ul>
</div>
{if intval($formdata['data']['latitude']) || intval($formdata['data']['longitude'])}
<div class="info clear vo"  style="height: 350px">
{code}
	$hg_map = array(
		'height'=>350,
		'width'=>380,
		'longitude'=>$formdata['data']['longitude'],          //经度
		'latitude'=>$formdata['data']['latitude'], 			  //纬度
		'zoomsize'=>13,          							  //缩放级别，1－21的整数
		'areaname'=>$position,         						  //显示地区名称，纬度,经度与地区名称二选1
		'is_drag'=>0,            							  //是否可拖动 1－是，0－否
	);
{/code}
{template:form/google_map,longitude,latitude,$hg_map}
</div>
{/if}
{else}
此投稿不存在,请刷新页面更新
{/if}



<script type="text/javascript">
	$(function() {
    $('#pic_show a').lightBox({width:500});
	});
	
</script>

