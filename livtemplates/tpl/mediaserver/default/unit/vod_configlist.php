<li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['config_order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');"  onclick="hg_row_interactive(this, 'click', 'cur');" >
	<span class="left">
		<a class="lb"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}"  /></a>
	</span>
	<span class="right"  style="width:860px">
		<a class="fb" href="javascript:void(0);"  onclick="hg_showAddConfig(true,{$v['id']});"><em class="b2" ></em></a>
		<a class="fb" href="javascript:void(0);"  onclick="return hg_delete_config({$v['id']})"><em class="b3" ></em></a>
		<a class="fl" ><em  id="output_format_{$v['id']}">{$v['output_format']}</em></a>
		<a class="fl" ><em  id="codec_format_{$v['id']}">{$v['codec_format']}</em></a>
		<a class="fl" style="width:60px;"><em  id="codec_profile_{$v['id']}">{$v['codec_profile']}</em></a>
		<a class="fl" ><em  id="width_{$v['id']}">{$v['width']}px</em></a>
		<a class="fl" ><em  id="height_{$v['id']}">{$v['height']}px</em></a>
		<a class="fl" ><em  id="video_bitrate_{$v['id']}">{$v['video_bitrate']}kbps</em></a>
		<a class="fl" ><em  id="audio_bitrate_{$v['id']}">{$v['audio_bitrate']}kbps</em></a>
		<a class="fl"><em   id="frame_rate_{$v['id']}">{$v['frame_rate']}帧/秒</em></a>
		<a class="fl"><em   id="gop_{$v['id']}">{$v['gop']}帧</em></a>
		<a class="fl"><em   id="vpre_{$v['id']}">{$v['vpre']}</em></a>
		<a class="fl"><em><img  id="water_mark_{$v['id']}" src="{$v['water_mark']}" width="40" height="30" /></em></a>
		<a class="fl"><em   id="is_use_{$v['id']}">{$v['is_use']}</em></a>
		<a class="fl"><em   id="is_open_water_{$v['id']}">{$v['is_open_water']}</em></a>
	</span>
	<span class="title overflow"  style="cursor:pointer;" >
		<a><span id="name_{$v['id']}" class="m2o-common-title ">{$v['name']}{if $v['is_default']}*{/if}</span><strong></strong></a>
	</span>
</li>   