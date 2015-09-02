{css:ad_style}
{css:vote_style}
<script type="text/javascript">
	function hg_ini2single()
	{
		if ($('#single_total').css('display') == 'none')
		{
			$('#single_total').show();
			$('#single_total_ini').hide();
			$('#other_vote_total').show();
			$('#other_vote_total_ini').hide();
			$('#typeShow').html('(不含初始数据)');
		}
		else
		{
			$('#single_total').hide();
			$('#single_total_ini').show();
			$('#other_vote_total').hide();
			$('#other_vote_total_ini').show();
			$('#typeShow').html('(包含初始数据)');
		}
	}
</script>
<form name="editform" action="./run.php?mid={$_INPUT['mid']}&a={$action}" method="post" enctype='multipart/form-data' class="ad_form h_l">
	<ul class="form_ul">
		<li class="i">
			<div class="form_ul_div" style="margin: 10px 0 20px 0px;">
				<span class="title">投票标题：</span>
				<span style="position: relative;top: 5px;">{$formdata['title']}</span>
			</div>
			<div >
				<span class="title" style="margin-left: 74px;">选项</span>
				<span class="title" style="margin-left: 155px;">总数</span>
				<span class="title" onclick="hg_ini2single();" style="cursor:pointer;margin-left: 43px;text-decoration: underline;">切换</span>
				<span id="typeShow">(包含初始数据)</span>
				<span class="title" style="margin-left: 40px;">初始数据</span>
				<span class="title" style="margin-left: 12px;">真实数据</span>
			</div>
			<div class="form_ul_div" style="margin: 10px 0 20px 0px;">	
				<span class="title"></span>
				<ul id="single_total" style="display:none;">
				{foreach $formdata['option_title'] AS $k => $v}
					<li style="position: relative;top: 5px;margin-top:5px;{if $k>1}margin-left:75px;{/if}">
						<span>{code} echo $k+1; {/code}.</span>
						<span style="margin-left:5px;display: inline-block;width:160px;overflow:hidden;white-space:nowrap"  title="{$v['title']}">{$v['title']}</span>
						<span style="display: inline-block;width:68px;" title="投票总数">{$v['ini_single']}</span>
						{code}
							$width = intval(($v['single_total']/$formdata['question_total'])*100);
						{/code}
						<span style="position: absolute;{if $k>1}left:255px;{else}left:330px;{/if}margin-right:10px;float:right;background-color:#498adb;{if $width <1} width:1px; {else} width:{$width}px;';{/if}">
							<font style="margin-left:100px;">{code} echo (round($v['single_total']/$formdata['question_total'],4))*100;{/code}%</font>
						</span>
						<span style="position: relative;left:152px;" title="初始化数据">{$v['ini_num']}</span>
						<span style="float:right;margin-right:10px;" title="真实数据">{$v['single_total']}</span>
					</li>
					
				{/foreach}
				</ul>
				<ul id="single_total_ini">
				{foreach $formdata['option_title'] AS $k => $v}
					<li style="position: relative;top: 5px;margin-top:5px;{if $k>1}margin-left:75px;{/if}">
						<span>{code} echo $k+1; {/code}.</span>
						<span style="margin-left:5px;display: inline-block;width:160px;overflow:hidden;white-space:nowrap"  title="{$v['title']}">{$v['title']}</span>
						<span style="display: inline-block;width:68px;" title="投票总数">{$v['ini_single']}</span>
						{code}
							$width_ini = intval(($v['ini_single']/$formdata['question_total_ini'])*100);
						{/code}
						<span style="position: absolute;{if $k>1}left:255px;{else}left:330px;{/if}margin-right:10px;float:right;background-color:#498adb;{if $width_ini <1} width:1px; {else} width:{$width_ini}px;';{/if}">
							<font style="margin-left:100px;">{code} echo (round($v['ini_single']/$formdata['question_total_ini'],4))*100;{/code}%</font>
						</span>
						<span style="position: relative;left:152px;" title="初始化数据">{$v['ini_num']}</span>
						<span style="float:right;margin-right:10px;" title="真实数据">{$v['single_total']}</span>
					</li>
				{/foreach}
				</ul>
			</div>
		</li>
		<li class="i">
			<div class="form_ul_div" style="margin: 10px 0 20px 0px;">	
				<span class="title">其他选项：</span>
				<ul>
					<li id="other_vote_total" style="display:none;position: relative;top: 5px;margin-top:5px;margin-left:75px;">
						<span style="margin-left:20px;"></span>
						{code}
							$width = intval(($formdata['other_vote_total']/$formdata['question_total'])*100);
						{/code}
						<span style="position: absolute;left:255px;margin-right:10px;float:right;background-color:#498adb;{if $width <1} width:1px; {else} width:{$width}px;';{/if}">
							<font style="margin-left:100px;">{code} echo (round($formdata['other_vote_total']/$formdata['question_total'],4))*100;{/code}%</font>
						</span>
						</span>
						<span style="float:right;margin-right:20px;">{$formdata['other_vote_total']}</span>
					</li>
					
					<li id="other_vote_total_ini" style="position: relative;top: 5px;margin-top:5px;margin-left:75px;">
						<span style="margin-left:20px;"></span>
						{code}
							$width_ini = intval(($formdata['other_vote_total']/$formdata['question_total_ini'])*100);
						{/code}
						<span style="position: absolute;left:255px;margin-right:10px;float:right;background-color:#498adb;{if $width_ini <1} width:1px; {else} width:{$width_ini}px;';{/if}">
							<font style="margin-left:100px;">{code} echo (round($formdata['other_vote_total']/$formdata['question_total_ini'],4))*100;{/code}%</font>
						</span>
						<span style="float:right;margin-right:20px;">{$formdata['other_vote_total']}</span>
					</li>
				</ul>
			</div>
		</li>
		<li class="i">
			<div class="form_ul_div" style="margin: 10px 0 20px 0px;">
				<span class="title">参与人数：</span>
				{if $formdata['app_id']}
					{code}
						$i=0;
					{/code}
					{foreach $formdata['app_id'] AS $k=>$v}
					<div style="margin-bottom:5px;">
						<span style="position: relative;top: 5px;{if $i> 1}margin-left: 74px;{/if}">{$v['app_name']} : </span>
						<span style="position: relative;top: 5px;">{$v['counts']} 人</span>
					</div>
					{code}
						$i ++;
					{/code}
					{/foreach}
				{/if}
				<div style="margin-bottom:5px;">
					<span style="position: relative;top: 5px;{if $formdata['app_id'] && count($formdata['app_id']) > 1}margin-left: 74px;{/if}">总人数 : </span>
					<span style="position: relative;top: 5px;">{if $formdata['preson_count']}{$formdata['preson_count']}{else}0{/if} 人</span>
				</div>
			</div>
		</li>
<!--
		<li class="i">
			<div class="form_ul_div" style="margin: 10px 0 20px 0px;">
				<span class="title">投票数：</span>
				<div style="margin-bottom:5px;">
					<span style="position: relative;top: 5px;">初始化数据 : </span>
					<span style="position: relative;top: 5px;">{$formdata['ini_num']}</span>
				</div>
				<div style="margin-bottom:5px;">
					<span style="position: relative;top: 5px;">真实数据 : </span>
					<span style="position: relative;top: 5px;">{$formdata['question_total']}</span>
				</div>
				<div style="margin-bottom:5px;">
					<span style="position: relative;top: 5px;margin-left: 74px;">总数据 : </span>
					<span style="position: relative;top: 5px;">{$formdata['question_total_ini']}</span>
				</div>
			</div>
		</li>
-->
	</ul>
</form>