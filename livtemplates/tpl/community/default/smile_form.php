
<div class="biaoqing-box" id="biaoqing-box">
  <div class="biaoqing-title">常用表情</div>
  <div class="biaoqing-con">
  	{foreach $smiles as $key => $value}
	  	{code}
	  		$fid = $key+1;
	  		$styles = $key ? "display:none;" : "display:block;";
	  		$j = 0;
	  	{/code}
  	 <div id="face_show_{$fid}" style="{$styles}">
	  <table>
	  {foreach $value as $k => $v}
	  {code}
		  $j ++;
	  	$tr_class = ($j == 5) ? "tr_last" : "";
	  {/code}
		  <tr class="{$tr_class}">
		  {foreach $v as $ks => $vs}
		  {code}
		 	$face_img = "";
		  	if($vs['host'])
		  	{
		  		$face_img = $vs['host'] . $vs['dir'] . $vs['filepath'] . $vs['filename'];
		  	}
		  	$td_class = ($ks == 9) ? "td_last" : "";		  	
		  {/code}
		  	<td class="{$td_class} biaoqing-img"><img src="{$face_img}" alt="{$vs['mark']}"/></td>
		  {/foreach}</tr>
	{/foreach}
	  </table>
	 </div>
	 {/foreach}
  </div>
  <div class="biaoqing-control">
  	<a href="javascript:void(0);" class="biaoqing-off" onclick="face_show_change(this,2,1);"></a>
  	<a href="javascript:void(0);" class="biaoqing-on" onclick="face_show_change(this,1,2);"></a>
  </div>
</div>
