{code}
$image_resource = RESOURCE_URL;
{/code}
 <img src="{if $formdata['pic_url']}{$formdata['pic_url']}{else}{$image_resource}black.jpg{/if}" style="position:absolute;left:0px;top:0px;"  id="tuji_content_img" />
  <div id="over_tip" class="tip_box">
  	<a href="javascript:void(0)" class="close_tip" onclick="close_tip_box();"></a>
  	<div style="color:white;margin-left:20%;margin-top:20%;">您已经浏览完所有图片</div>
  </div>
  <div style="width:45px;height:20px;position:absolute;left:10px;top:280px;background:black;text-align:center;">
  	   {if $formdata['nochild']}
  	   <div style="color:white;line-height:20px;">没有图片</div>
  	   <script type="text/javascript">
  	 		display_tip_box();
  	   </script>
  	   {else}
  	   <div style="color:white;line-height:20px;">{$formdata['current_page']}/{$formdata['total_num']}</div>
  	   {/if}
  </div>
  <div id="picinfo" class="pic_info info"  style="background:#E0E0E0;position:absolute;left:0px;top:301px;" onclick="hg_edit_comment(this,1);">
      <div style="background:#E0E0E0;width:100%;height:98%;" class="overflow" id="picinfo_comment">{if $v['description']}{$v['description']}{else if $v['is_namecomment']}{$v['old_name']}{else}{$v['default_comment']}{/if}</div>
  </div>
  <input type="hidden" name="isover" id="isover" value="{$formdata['over']}" />
  <textarea class="pic_info" style="position:absolute;left:0px;top:301px;width:99%;height:18%;display:none;" id="pic_text" onblur="hg_edit_comment(this,0,{$formdata['id']});">{$formdata['description']}</textarea>
  <div class="arrL" title="点击浏览上一张图片 "  onmouseover="hg_onPicMouseOver(this,1);" onmouseout="hg_onPicMouseOver(this,0);"  onclick="hg_showOtherPic({$formdata['tuji_id']},{$formdata['prev_page']},0);"></div>
  <div class="arrR" title="点击浏览下一张图片 "  onmouseover="hg_onPicMouseOver(this,1);" onmouseout="hg_onPicMouseOver(this,0);"  onclick="hg_showOtherPic({$formdata['tuji_id']},{$formdata['next_page']},1);"></div>
  <div class="btnPrev" style="display:none;" id="left_btn"  onmouseover="hg_show_btn(this);" onclick="hg_showOtherPic({$formdata['tuji_id']},{$formdata['prev_page']},0);"><a href="#"></a></div>
  <div class="btnNext" style="display:none;" id="right_btn" onmouseover="hg_show_btn(this);" onclick="hg_showOtherPic({$formdata['tuji_id']},{$formdata['next_page']},1);"><a href="#"></a></div>
  <span onclick="hg_close_opration_info();" style="position:absolute;z-index:30;" title="关闭/ALT+Q"></span>