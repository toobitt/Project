{code}
  $all_time =  intval($hg_value)/1000;
  $h_time   =  intval($all_time/3600);
  $m_time   =  intval(($all_time%3600)/60);
  $s_time   =  intval(($all_time%3600)%60);
  
  $hg_attr['width']      = $hg_attr['width'] ? $hg_attr['width'].'px':'252px';
  $hg_attr['background'] = $hg_attr['background'] ? $hg_attr['background']:'#ffffff';
  $hg_attr['color'] = $hg_attr['color'] ? $hg_attr['color']:'#000000';
{/code}
<div class="time_box"  style="width:{$hg_attr['width']};background:{$hg_attr['background']}" >
	<input type="text" class="time_text" style="color:{$hg_attr['color']};"  onblur="hg_kj_checktimeval(this,1,'{$hg_name}');" id="hour_{$hg_name}" value="{if $h_time}{$h_time}{else}0{/if}" />
	<div class="time_word">时</div>
	<input type="text" class="time_text" style="color:{$hg_attr['color']};"  onblur="hg_kj_checktimeval(this,2,'{$hg_name}');" id="minu_{$hg_name}" value="{if $m_time}{$m_time}{else}0{/if}" />
	<div class="time_word">分</div>
	<input type="text" class="time_text" style="color:{$hg_attr['color']};"  onblur="hg_kj_checktimeval(this,3,'{$hg_name}');" id="seco_{$hg_name}" value="{if $s_time}{$s_time}{else}0{/if}" />
	<div class="time_word">秒</div>
	<input type="hidden" name="{$hg_name}" id="time_{$hg_name}" value="{if $hg_value}{$hg_value}{else}0{/if}" />
</div>