<script type="text/javascript">
	$(function(){
		Liv_resize("#tuji_pics_show");
	});
</script>
{code}
$image_resource = RESOURCE_URL;
{/code}
{if $formdata['id']}
<div class="info clear vider_s"  id="vodplayer_{$formdata['id']}">
  <div id="tuji_pics_show" class="tuji_pics_show">
  	  <img src="{if $formdata['cover_url']}{$formdata['cover_url']}{else}{$image_resource}black.jpg{/if}" id="tuji_content_img" style="position:absolute;left:0px;top:0px;" />
  	  <div id="over_tip" style="width:200px;height:100px;position:absolute;left:25%;top:30%;background:none repeat scroll 0 0 #000000;opacity:0.7;display:none;"></div>
  	  <div style="width:45px;height:20px;position:absolute;left:10px;top:280px;background:black;text-align:center;">
  	     	<div style="color:white;line-height:20px;">封面</div>
  	  </div>
  	  <div id="picinfo" class="pic_info info"  style="background:#E0E0E0;position:absolute;left:0px;top:301px;" onclick="hg_edit_comment(this,1);">
        <div style="background:#E0E0E0;width:100%;height:98%;" class="overflow" id="picinfo_comment">{$formdata['comment']}</div>
  	  </div>
  	  <input type="hidden" name="isover" id="isover" value="0" />
  	  <textarea class="pic_info" style="position:absolute;left:0px;top:301px;width:99%;height:18%;display:none;" id="pic_text" onblur="hg_edit_comment(this,0,{$formdata['id']},1);">{$formdata['comment']}</textarea>
	  <div class="arrL" title="点击浏览上一张图片 "  onmouseover="hg_onPicMouseOver(this,1);" onmouseout="hg_onPicMouseOver(this,0);" onclick="hg_showOtherPic({$formdata['id']},0);"></div>
	  <div class="arrR" title="点击浏览下一张图片 "  onmouseover="hg_onPicMouseOver(this,1);" onmouseout="hg_onPicMouseOver(this,0);" onclick="hg_showOtherPic({$formdata['id']},0);"></div>
	  <div class="btnPrev" style="display:none;" id="left_btn"  onmouseover="hg_show_btn(this);" onclick="hg_showOtherPic({$formdata['id']},0);"><a href="#"></a></div>
	  <div class="btnNext" style="display:none;" id="right_btn" onmouseover="hg_show_btn(this);" onclick="hg_showOtherPic({$formdata['id']},0);"><a href="#"></a></div>
	  <span onclick="hg_close_opration_info();" style="position:absolute;z-index:30;" title="关闭/ALT+Q"></span>
  </div>
</div>

<div class="info clear cz"  style="margin-top:70px;">
	<div id="video_opration" class="clear" style="border:0;">
			<div class="common-opration-list">
			    <a class="button_4"  href="./run.php?mid={$_INPUT['mid']}&a=tuji_form&id={$formdata['id']}&infrm=1" target="formwin">编辑</a>
			    <a class="button_4" onclick="return hg_ajax_post(this, '删除', 1);"  title=""  href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$formdata['id']}">删除</a>
			</div>
			<div class="common-opration-list">
			    <a class="button_4" href="javascript:vod(0);"  onclick="hg_showMoveTuJi({$formdata['id']});">移动</a>
			    {if $formdata['status'] != 1}
			    <a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=audit&id={$formdata['id']}&audit=1" onclick="return hg_ajax_post(this, '审核', 0,'hg_change_status');">审核</a>
		       {else}
			   <a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=audit&id={$formdata['id']}&audit=0" onclick="return hg_ajax_post(this, '打回', 0,'hg_change_status');">打回</a>
		       {/if}
			</div>
			<div class="common-opration-list">
			    <a class="button_6" href="./run.php?mid={$_INPUT['mid']}&a=recommend&id={$formdata['id']}" onclick="return hg_ajax_post(this, '推荐', 0);">发布至网站</a>
			</div>
			<!-- <a class="button_4" href="download.php?a=video&amp;api={$__api}&amp;f=vod_down.php&amp;id={$formdata['id']}&amp;title={$formdata['title']}">下载</a>-->
		    <!-- <a class="button_6" href="javascript:void(0);"  onclick="hg_showAddTuJi({$formdata['id']},1);">列出所有</a>-->
	</div>
</div>
<div class="info clear vo">
	<h4 onclick="hg_slide_up(this,'tuji_subinfo')"><span title="展开\收缩"></span>内容属性</h4>
	<ul id="tuji_subinfo" class="clear">
		<li class="h"><span>分类：{$formdata['sort_name']}</span></li>
		{if $default}
			<li class="w"><span>发布至：</span>
				{if $default[$formdata['id']][1]}
				<div class="earth"></div>
					 {foreach $default[$formdata['id']][1] as $v}
					 <span>{$v}</span>
					 {/foreach}
				{/if}
				{if $default[$formdata['id']][2]}
				<div class="iphone"></div>
					 {foreach $default[$formdata['id']][2] as $v}
					 <span>{$v}</span>
					 {/foreach}
				{/if}
			</li>
		{/if}
		{if $formdata['keywords']}<li class="w"><span>关键字：</span>{$formdata['keywords']}</li>{/if}
		{if $formdata['comment']}<li class="w"><span>描述：</span>{$formdata['comment']}</li>{/if}
	</ul>
</div>
{else}
此图集已经不存在,请刷新页面更新
{/if}