<li class="clear"  id="r_{$formdata['id']}"    name="{$formdata['id']}"   orderid="{$formdata['config_order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');"  onclick="hg_row_interactive(this, 'click', 'cur');" >
	<span class="left">
		<a class="lb"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$formdata['id']}" title="{$formdata['id']}"  /></a>
	</span>
	<span class="right"  style="width:800px">
		<a class="fb" href="javascript:void(0);"  onclick="hg_showAddConfig(true,{$formdata['id']});"><em class="b2" ></em></a>
		<a class="fb" href="javascript:void(0);"  onclick="return hg_delete_config({$formdata['id']})"><em class="b3" ></em></a>
		<a class="fl" ><em  id="output_format_{$formdata['id']}">{$formdata['output_format']}</em></a>
		<a class="fl" ><em  id="codec_format_{$formdata['id']}">{$formdata['codec_format']}</em></a>
		<a class="fl" ><em  id="codec_profile_{$formdata['id']}">{$formdata['codec_profile']}</em></a>
		<a class="fl" ><em  id="width_{$formdata['id']}">{$formdata['width']}像素</em></a>
		<a class="fl" ><em  id="height_{$formdata['id']}">{$formdata['height']}像素</em></a>
		<a class="fl" ><em  id="video_bitrate_{$formdata['id']}">{$formdata['video_bitrate']}kbps</em></a>
		<a class="fl" ><em  id="audio_bitrate_{$formdata['id']}">{$formdata['audio_bitrate']}kbps</em></a>
		<a class="fl"><em   id="frame_rate_{$formdata['id']}">{$formdata['frame_rate']}帧/秒</em></a>
		<a class="fl"><em   id="gop_{$formdata['id']}">{$formdata['gop']}秒</em></a>
		<a class="fl"><em   id="vpre_{$formdata['id']}">{$formdata['vpre']}</em></a>
		<a class="fl"><em   id="water_mark_{$formdata['id']}">{$formdata['water_mark']}</em></a>
		<a class="fl"><em   id="is_use_{$formdata['id']}">{$formdata['is_use']}</em></a>
	</span>
	<span class="title overflow"  style="cursor:pointer;" >
		<a><span id="name_{$formdata['id']}" >{$formdata['name']}</span><strong></strong></a>
	</span>
</li>   