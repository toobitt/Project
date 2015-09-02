<div id="page_{$_INPUT['tuji_id']}" class="page_zoom">
    <ul class="thumb" id="thumb_zoom_{$_INPUT['tuji_id']}" style="margin-top:30px;margin-bottom:30px;">
    	  {foreach $formdata AS $k => $v}
	      <li><a href="#"><img src="{$v['img_src']}" alt="{if $v['description']}{$v['description']}{else if $v['is_namecomment']}{$v['old_name']}{else}{$v['default_comment']}{/if}" /></a></li>
	      {/foreach}
    </ul>
</div>
<script type="text/javascript">
$("ul[id^='thumb_zoom_'] li").Zoomer({speedView:200,speedRemove:400,altAnim:true,speedTitle:400,debug:false});
</script>