
<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div">
			<span class="title title_num" name="title_num[]">问题&nbsp;{code} echo $k+1;{/code}：</span>
			<span style="width:30px;height:30px;border:1px solid #DADADA;float:left;margin:-5px 10px -5px 0px;">{if $v['pictures_info']}<img width=30 height=30 src="{$v['question_img']}" />{else}<img width=30 height=30 src="{$RESOURCE_URL}vote_default_b.png" />{/if}</span>
			<input type="text" name="q_title[]" value="{$v['title']}" style="width:400px" />
			<input type="hidden" name="q_id[]" value="{$v['id']}" />
			<span  {if $v['pictures_info']} class="question_files" {else} class="question_files_b" {/if}></span>
 			<span onclick="hg_delQuestionDom(this,{$v['id']});" class="vote_del"></span>
			<span onclick="hg_question_contract(this);" class="question_contract_a"></span>
			<span name="questionFileStyle[]" {if $v['pictures_info']} class="questionFileStyle" {else} class="questionFileStyle_a" {/if}><input type="file" name="question_files_{$k}" class="question_style" onchange="hg_questionFileStyle(this,1);" hidefocus></span>
		</div>
		<div class="form_ul_div">	
			<span class="title">选项：</span>
			<div name="option_box[]" id="option_box_{code} echo $k+1;{/code}">
			{if $questions}
				{foreach $v['options'] AS $kk => $vv}
					<div class="option_title">
						<span style="display:inline-block;width:30px;height:30px;border:1px solid #DADADA;float:left;margin:-3px 10px 0px 0px;">{if $vv['pictures_info']}<img width=30 height=30 src="{$vv['option_img']}" />{else}<img width=30 height=30 src="{$RESOURCE_URL}vote_default_b.png" />{/if}</span>
						<span class="num_a" style="display:inline-block;">{code} echo $kk+1;{/code}.</span><input onblur="hg_optionChecked(this);" type="text" name="option_title_{$k}[]" value="{$vv['title']}" style="width:290px;"/>
						<input type="hidden" name="option_id_{$k}[]" value="{$vv['id']}" />
						<span {if $vv['pictures_info']} class="vote_question_files" {else} class="vote_question_files_b" {/if}></span>
						<span class="option_del_box">
							<span  name="option_del[]" class="option_del" title="删除" onclick="hg_optionTitleDel(this,{$vv['id']})"></span>
						</span>
						<span name="optionFileStyle[]" {if $vv['pictures_info']} class="optionFileStyle" {else}  class="optionFileStyle_c"  {/if}><input type="file" name="option_files_{$k}_{$kk}" class="option_style" onchange="hg_optionFileStyle(this);" hidefocus></span>
						<span class="single_total_style">
						{code}
							$width = intval(($vv['single_total']/$v['question_total'])*100);
						{/code}
							<span style="{if $width <1}width:1px;{else}width:{$width}px;{/if}height:2px;display:inline-block;background: #609CD2;"></span>
						</span>
						<span {if $vv['pictures_info']} class="single_total f_r" {else} class="single_total_b f_r" {/if}>{$vv['single_total']}&nbsp;票 </span>
					</div>
				{/foreach}
			{/if}
			</div>
			<div class="option_title">
				<div id="getOtherOptionBox_{$v['id']}" style="width:680px;border:1px solid #449FFC;padding-bottom:10px;display:none;margin-bottom:10px;margin-left:-50px;"></div>
				<a id="add_button_{code} echo $k+1;{/code}" href="javascript:void(0);" onclick="hg_optionTitleAdd(this);">再加一项</a>
				<font style="margin-left:55px;" class="colGray_b">至少设置两项</font>
				{if $v['is_other']}<span style="margin-left:55px;cursor:pointer;" class="colGray_a" onclick="hg_getOtherOption({$v['id']});">查看更多</span><span id="getOtherOption_img_{$v['id']}" style="display:none;position: relative;top: 4px;left: 10px;"><img width=16 height=16 src="{$RESOURCE_URL}loading6.gif" /></span>{/if}
			</div>
			
			<div class="form_ul_div">
				<span class="title"></span>
				<input type="checkbox" onclick="hg_option_select(this);" value=1 {if $v['option_type'] == 1} checked {/if} name="option_type[]" class="n-h-s" /><span class="s-s">单选</span>
				<input type="checkbox" onclick="hg_option_select(this);" value=2 {if $v['option_type'] == 2} checked {/if} name="option_type[]" class="n-h-s" /><span class="s-s">多选</span>
				<span class="s-s ml_30">最少选</span><input type="text" name="min_option[]" value="{$v['min_option']}" class="n-h-s" style="margin-left:10px;width:30px;position:relative;top:-4px;" /><span class="s-s">条</span>
				<span class="s-s ml_30">最多选</span><input type="text" name="max_option[]" value="{$v['max_option']}" class="n-h-s" style="margin-left:10px;width:30px;position:relative;top:-4px;" onmouseout="hg_maxOptionShow(this);" onmouseover="hg_maxOptionShow(this,1);" /><span class="s-s">条</span>
				<input type="checkbox" value=1 {if $v['is_other']	 == 1} checked {/if} name="is_other[{$k}]" class="n-h-s ml_30" /><span class="s-s">允许有其他选项</span>
				<span class="maxOptionAlert s-s ml_30" style="color:red;"></span>
			</div>
		</div>
	</li>
</ul>
<script type="text/javascript">
$(function(){
	$('#questionBox div[name^="option_box"]').each(function(){
		if ($(this).children().length > 2)
		{
			$(this).find('input[name^="option_files_"]').each(function(){
				$(this).parent().prev().find('span[name^="option_del"]').show();
			});
		}
	});
});
</script>
