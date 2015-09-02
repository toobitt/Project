{code}
  $image_resource = RESOURCE_URL;
{/code}

{if !$formdata['new_img']['errorno']}
 {foreach $formdata['new_img'] as $k => $v}
 {if $k <= 8}
 <div class="img_box"><img src="{$v}" class="every_pic" width="76px" height="57px" onclick="hg_select_pic(this,{$formdata['id']});"  /></div>
 {/if}
 {/foreach}

<div id="img_loaded_{$formdata['id']}"><!-- 标记是否加载 --></div>

{else}
未找到该视频信息
{/if}
















