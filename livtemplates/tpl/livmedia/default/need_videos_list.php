{if $formdata['total_num']}
<div class="clear ul  {if $formdata['switch_mode']}text{else}img{/if}" id="video_content_ul" >
{foreach $formdata['video_info'] as $k => $v}
  <span id="v_{$v['id']}" onclick="hg_deleteSelf($(this));" class="li" >
     <div class="item_img"><img src="{$v['img']}" onmousemove="hg_show_bigitem({$v['id']});" onmouseout="hg_hide_bigitem({$v['id']});" onmousedown="hg_hide_bigitem({$v['id']});"  /></div>
	 <div id="s_{$v['id']}" class="show_item">
		<span class="overflow"  id="s_title_{$v['id']}">名称：{$v['title']}</span>
		<span id="s_duration_{$v['id']}">时长：{$v['duration']}</span>
		<span id="s_totalsize_{$v['id']}">大小：{$v['totalsize']}</span>
	 </div>
  </span>
{/foreach}
</div>

<div class="page">
    {if $formdata['total_num'] > $formdata['page_num']}
	    {if $formdata['current_page'] != 1}
		<a href="javascript:void(0);" onclick="hg_getManyVideos({$formdata['first_page']});" class="p">|<</a>
		<a href="javascript:void(0);" onclick="hg_getManyVideos({$formdata['prev_page']});" class="p"><</a>
		{/if}
		{if $formdata['total_page'] != $formdata['current_page']}
		<a href="javascript:void(0);" onclick="hg_getManyVideos({$formdata['next_page']});" class="p" >></a>
		<a href="javascript:void(0);" onclick="hg_getManyVideos({$formdata['last_page']});" class="p">>|</a>
		{/if}
	{/if}
	
	<span class="button_4" style="float:right;margin-right:9px;" id="add_all_videos" onclick="hg_selectAllVideos();">全部添加</span>
	<input type="button"  style="float:right;cursor:pointer;margin:0 5px;"  class="button_4"   value="{if $formdata['switch_mode']}小{else}大{/if}图模式"   id="switch_button"     onclick="hg_switchThecollect();"  />
	<span style="float:right;margin-right:10px;">{$formdata['current_page']}/{$formdata['total_page']}页 {$formdata['total_num']}条</span>
</div>
{else}
<p style="color:#da2d2d;text-align:center;font-size:14px;line-height:30px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
{/if}