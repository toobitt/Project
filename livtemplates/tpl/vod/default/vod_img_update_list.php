
<dt>选择本标注的视频示意图</dt>	
 {foreach $formdata['new_img'] as $k => $v}			
<dd><a ><img src="{$v}" id="list_pic_{$k}"  width="117" height="88"  onclick="hg_show_pic(this);"/></a><span class="info-img-selected"></span></dd>            
{/foreach}
      
