{css:ad_style}
{css:vote_style}

<div class="vote_title" id="vote_title_hide">
	<span class="colGray_a ml_20">问卷：</span>
	<span id="vote_title">{$vote_title}</span>
</div>
<div>
	<form  class="ad_form h_l" name="voteQuestion" id="voteQuestion" method="post" enctype='multipart/form-data' {if $id} action="./run.php?mid={$_INPUT['mid']}" {else} onsubmit="return hg_voteQuestionCreate();"  action="./run.php?mid={code} echo $relate_module_id ? $relate_module_id : $_INPUT['mid'];{/code}" {/if}>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div">
					<span class="title">投票标题：</span>
					<input type="text" name="title" value="{$title}" style="width:440px"/>
					<font class="important" id="important_2">必填</font>
				</div>
				<div class="form_ul_div">	
					<span class="title">描述：</span>
					{template:form/textarea,describes,$describes}
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">	
					<span class="title">投票选项：</span>
					<div id="option_box">
						{if $id}
						{foreach $option_title AS $k => $v}
							<div class="option_title">
								<span class="num_a">{code} echo $k+1;{/code}.</span><input type="text" name="option_title[]" value="{$v['title']}" style="width:150px"/><input type="hidden" name="option_id[]" value="{$v['id']}" /><a href="javascript:void(0);" style="margin-left:20px;" title="删除" onclick="hg_optionTitleDel(this,{$v['id']})">X</a>
								<input type="file" name="files_{$k}" style="float:right;margin-right:190px;width:90px;{if !$is_logo} display:none; {/if}"/>
							</div>
						{/foreach}
						{else}
							<div class="option_title">
								<span class="num_a">1.</span><input type="text" name="option_title[]" value="{$option_title}" style="width:150px"/><a href="javascript:void(0);" style="margin-left:20px;display:none;" title="删除" onclick="hg_optionTitleDel(this)">X</a>
								<input type="file" name="files_0" style="float:right;margin-right:190px;width:90px;"/>
							</div>
							<div class="option_title">
								<span class="num_a">2.</span><input type="text" name="option_title[]" value="{$option_title}" style="width:150px"/><a href="javascript:void(0);" style="margin-left:20px;display:none;" title="删除" onclick="hg_optionTitleDel(this)">X</a>
								<input type="file" name="files_1" style="float:right;margin-right:190px;width:90px;"/>
							</div>	
						{/if}
					</div>
					<div class="option_title">
						<a id="add_button" href="javascript:void(0);" onclick="hg_optionTitleAdd();">再加一项</a>
						<font style="margin-left:55px;" class="colGray_b">至少设置两项</font>
					</div>

				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">	
					<span class="title">选项设置：</span>
					<input type="checkbox" id="option_radio" onclick="hg_option_select('radio');" value=1 {if $option_type == 1} checked {/if} name="option_type" class="n-h-s" /><span class="s-s">单选</span>
					<input type="checkbox" id="option_checkbox" onclick="hg_option_select('checkbox');" value=2 {if $option_type == 2} checked {/if} name="option_type" class="n-h-s" /><span class="s-s">多选</span>
				</div>
				<div class="form_ul_div">	
					<span class="title">选项数目：</span>
					<input type="text" name="min_option" value="{$min_option}" style="width:50px"/>
					<font style="float:right;" class="colGray_b">(最小选项数目)</font>
				</div>
				<div class="form_ul_div">	
					<span class="title"></span>
					<input type="text" name="max_option" value="{$max_option}" style="width:50px"/>
					<font style="float:right;" class="colGray_b">(最大选项数目 0-无限制)</font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">	
					<span class="title">其他选项：</span>
					<input type="checkbox" value=1 {if $is_other} checked {/if} name="is_other" class="n-h" /><span class="s">其他投票选项</span>
				</div>
			</li>

		</ul>

		<input type="submit" name="sub" value="确定" id="sub" class="button_6_14"/>
		<input type="hidden" name="vote_id" value="{$vote_id}" id="vote_id" />
		{if $id}
			<input type="hidden" name="a" value="update" id="action" />
			<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
			<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
			<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
		{else}
			<input type="hidden" name="a" value="create" id="action" />
		{/if}
		<input type="hidden" id="is_logo" value="{$is_logo}" />
	</form>
</div>
<script type="text/javascript" language="javascript">
/*选项为2个时 删除按钮隐藏*/
$(function(){
	if ($('#option_box .option_title').length == 2)
	{
		$('#option_box a').hide();
	}
});
</script>
