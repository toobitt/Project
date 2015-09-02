{code}
/*hg_pre($formdata);*/
{/code}
<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div">
			<span class="title title_num" name="title_num">问题：</span>
			<span style="width:30px;height:30px;border:1px solid #DADADA;float:left;margin:-5px 10px -5px 0px;">{if $pictures_info}<img width=30 height=30 src="{$question_img}" />{else}<img width=30 height=30 src="{$RESOURCE_URL}vote_default_b.png" />{/if}</span>
			<input type="text" name="title" value="{$title}" style="width:400px" />
			<span {if $pictures_info} class="question_files" {else} class="question_files_b"{/if}></span>
			<span name="questionFileStyle[]" {if $pictures_info} class="questionFileStyle_c" {else} class="questionFileStyle_b" {/if}><input type="file" name="question_files_0" class="question_style" onchange="hg_questionFileStyle(this);" hidefocus></span>
		</div>
		<div class="form_ul_div">	
			<span class="title">描述：</span>
			{template:form/textarea,describes,$describes}
		</div>
		<div class="form_ul_div clear">
			<span class="title">分类：</span>
			{code}
				$hg_attr['node_en'] = 'vote_node';
			{/code}
			{template:unit/class,node_id,$formdata['node_id'], $node_data}
		</div>
        <div class="form_ul_div clear" style="margin-left: 33px;">
            <a class="common-publish-button overflow" href="javascript:;" _default="发布至：无" _prev="发布至："></a>
        </div>
		<div class="form_ul_div">	
			<span class="title">选项：</span>
			<div name="option_box[]" id="option_box_1" class="option-list">
			{if $option_title}
				{foreach $option_title AS $k => $v}
					{if !$v['is_other']}
				<div class="option_title">
					<span style="width:30px;height:30px;border:1px solid #DADADA;float:left;margin:-3px 10px 0px 0px;">{if $v['pictures_info']}<img width=30 height=30 src="{$v['option_img']}" />{else}<img width=30 height=30 src="{$RESOURCE_URL}vote_default_b.png" />{/if}</span>
					<span class="num_a">{code} echo $k+1; {/code}.</span><input onblur="hg_optionChecked(this);" type="text" name="option_title_0[]" value="{$v['title']}" style="width:290px;"/>
					<input type="hidden" name="option_id[]" value="{$v['id']}" />
					<span {if $v['pictures_info']} class="vote_question_files" {else} class="vote_question_files_b" {/if}></span>
					<span class="option_del_box">
						<span name="option_del[]" class="option_del" title="删除" onclick="hg_optionTitleDel(this,{$v['id']},1);"></span>
					</span>
					<span class="add-data-btn">选取内容</span>
					<input type="hidden" class="hidden-id" name="publishcontent_id[]" />
					<span name="optionFileStyle[]" {if $v['pictures_info']} class="optionFileStyle" {else}  class="optionFileStyle_c" {/if}><input type="file" name="option_files_0_{$k}" class="option_style" onchange="hg_optionFileStyle(this);" hidefocus></span>
					<span onclick="hg_optionDescribe(this);" class="{if $v['describes']}option_describe_b{else}option_describe{/if}" title="选项描述" style=""></span>
					<span style="position: relative;left: 140px;display: inline-block;margin-right:2px;">初始投票数<input name="ini_num[]" value="{$v['ini_num']}" style="width: 30px;margin-left: 5px;" /></span>
				<!--	<span class="single_total_a">{$v['single_total']}&nbsp;票</span> -->
					<span name="option_describe_box[]">
						<textarea name="option_describes[{$k}]" onblur="hg_optionDescribeHide(this);" style="display:none;margin-top:10px;">{$v['describes']}</textarea>
					</span>
				<!-- 	
					<span class="describe_overflow">{code} echo substr($v['describes'],0,9); {/code}{if $v['describes']}...{/if}</span>
				
						<span class="describe_overflow_box"></span><span class="single_total_style_b">
					{code}
						$width = intval(($vv['single_total']/$v['question_total'])*100);
					{/code}
						<span style="{if $width <1}width:1px;{else}width:{$width}px;{/if}height:2px;display:inline-block;background: #609CD2;"></span>
					</span> -->
				</div>
					{/if}
				{/foreach}
			{else}
				{code}
					for ($i=0; $i<4; $i++)
					{
				{/code}
					<div class="option_title">
						<span class="upload_style"></span>
						<span class="num_a">{code} echo $i+1; {/code}.</span><input onblur="hg_optionChecked(this);" type="text" name="option_title_0[]" value="" style="width:290px"/>
						<span class="vote_question_files_b"></span>
						<span class="option_del_box">
							<span name="option_del[]" class="option_del" title="删除" onclick="hg_optionTitleDel(this,'',1);"></span>
						</span>
						<span class="add-data-btn">添加内容</span>
						<input type="hidden" class="hidden-id"  name="publishcontent_id[]" />
						<span name="optionFileStyle[]" class="optionFileStyle_c"><input type="file" name="option_files_0_{code} echo $i; {/code}" class="option_style"  onchange="hg_optionFileStyle(this);" hidefocus></span>
						<span onclick="hg_optionDescribe(this);" class="option_describe" title="选项描述"></span>
						<span style="position: relative;left: 140px;display: inline-block;margin-right:2px;">初始投票数<input name="ini_num[]" value="" style="width: 30px;margin-left: 5px;" /></span>
						<span name="option_describe_box[]"></span>
					<!--	<span class="describe_overflow" style="left:72px;"></span>-->
					</div>
				{code}
					}
				{/code}
			{/if}
			</div>
			<div class="option_title">
				<div id="getOtherOptionBox_{$id}" style="width:526px;border:1px solid #449FFC;padding-bottom:10px;display:none;margin-bottom:10px;"></div>
				<a id="add_button_1" href="javascript:void(0);" onclick="hg_optionTitleAdd(this,'question');">再加一项</a>
				<font style="margin-left:55px;" class="colGray_b">至少设置两项</font>
				{if $is_other}<span style="margin-left:55px;cursor:pointer;" class="colGray_a" onclick="hg_getOtherOption({$id});">查看更多</span>{/if}
			</div>
			<div class="form_ul_div">
				<span class="title"></span>
				<input type="checkbox" id="option_radio" onclick="hg_option_select(this);" value=1 {if $option_type == 1} checked {/if} {if $action == 'create'} checked="checked" {/if} name="option_type" class="n-h-s" /><span class="s-s">单选</span>
				<input type="checkbox" id="option_checkbox" onclick="hg_option_select(this);" value=2 {if $option_type == 2} checked {/if} name="option_type" class="n-h-s" /><span class="s-s">多选</span>
				<span class="s-s ml_30">最少选</span><input type="text" name="min_option" value="{$min_option}" class="n-h-s" style="margin-left:10px;width:30px;position:relative;top:-4px;" /><span class="s-s">条</span>
				<span class="s-s ml_30">最多选</span><input type="text" name="max_option" value="{$max_option}" class="n-h-s" style="margin-left:10px;width:30px;position:relative;top:-4px;" onmouseout="hg_maxOptionShow(this);" onmouseover="hg_maxOptionShow(this,1);" /><span class="s-s">条</span>
				<input type="checkbox" id="is_other" value=1 {if $is_other == 1} checked {/if} name="is_other" class="n-h-s ml_30" /><span class="s-s">允许有其他选项</span>
				<span class="maxOptionAlert s-s ml_30" style="color:red;"></span>
			</div>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span class="title">有效期：</span>
			<input  id="start_time" type="text" value="{if $start_time}{$start_time}{/if}" autocomplete="off" size="20" onfocus="if(1){WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm:ss'});}" name="start_time">
			<span>-</span>
			<input  id="end_time" type="text" value="{if $end_time}{$end_time}{/if}" autocomplete="off" size="20" onfocus="if(1){WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm:ss'});}" name="end_time">
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span class="title">设置：</span>
			<input type="checkbox" value=1 {if $is_ip} checked {/if} name="is_ip" class="n-h-s" /><span class="s-s">同一IP地址限制</span>
			<input id="ip_limit_time" name="ip_limit_time" type="text" value="{if $ip_limit_time}{$ip_limit_time}{/if}" style="margin-left:10px;width:50px;position:relative;top:-4px;" />
			<font class="hours">小时</font>
			<input type="checkbox" style="margin-left:16px;" onclick="hg_is_user_login(this);" value=1  {if $is_user_login} checked {/if} name="is_user_login" class="n-h-s ml_30" /><span class="s-s">开启用户登陆</span>
			<span  {if $is_user_login} style="display:inline-block;" {else} style="display:none;" {/if} id="is_uesr_id_box">
				<input type="checkbox" value=1  {if $is_userid} checked {/if} name="is_userid" class="n-h-s ml_30" /><span class="s-s">同一用户限制</span>
				<input id="userid_limit_time" name="userid_limit_time" type="text" value="{$userid_limit_time}" style="margin-left:10px;width:50px;position:relative;top:-4px;" />
				<font class="hours">小时</font>
			</span>
			<input type="checkbox" value=1 {if $is_verify_code} checked {/if} name="is_verify_code" class="n-h-s ml_30" /><span class="s-s">开启验证码</span>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span class="title" style="margin-left: -5px;width:75px;">自定义信息：</span>
		</div>
		<div id="moreBox" style="margin-left: 75px;margin-top: 10px;margin-bottom: 10px;">
		{if $more_info}
			{foreach $more_info AS $kk => $vv}
			<div style="margin:5px;"><span onclick="hg_addMoreInfo(this);" style="cursor:pointer;margin-right:5px;margin-top:10px;display: inline-block;">++</span><textarea name="more_info[]" style="min-width: 400px;min-height: 20px;height: 32px;">{$vv}</textarea><span onclick="hg_delMoreInfo(this);" style="cursor:pointer;margin-left:10px;margin-top:10px;display: inline-block;" name="delMore[]">--</span></div>
			{/foreach}
		{else}
			<div style="margin:5px;"><span onclick="hg_addMoreInfo(this);" style="cursor:pointer;margin-right:5px;margin-top: 10px;display: inline-block;">++</span><textarea name="more_info[]" style="min-width: 400px;min-height: 20px;height: 32px;"></textarea><span onclick="hg_delMoreInfo(this);" style="cursor:pointer;margin-left:10px;display:none;margin-top:10px;" name="delMore[]">--</span></div>
		{/if}
		</div>
	</li>
</ul>

<script type="text/javascript">
$(function(){
	if ($('#option_box_1 .option_title').length > 2)
	{
		$('#option_box_1 .option_title').each(function(){
			$(this).find('span[name^="option_del"]').show();
		});
	}
	if ($('#moreBox div').length == 1)
	{
		$('span[name^="delMore[]"]').hide();
	}
});
function hg_is_user_login(obj)
{
	if ($(obj).attr('checked') == 'checked')
	{
		$('#is_uesr_id_box').show();
		$('input[name="is_userid"]').removeAttr('disabled');
		$('input[name="userid_limit_time"]').removeAttr('disabled');
	}
	else
	{
		$('#is_uesr_id_box').hide();
		$('input[name="is_userid"]').attr('disabled', 'disabled');
		$('input[name="userid_limit_time"]').attr('disabled', 'disabled');
	}
}
function hg_addMoreInfo(obj)
{
	var html = '<div style="margin:5px;"><span onclick="hg_addMoreInfo(this);" style="cursor:pointer;margin-right:5px;margin-top: 10px;display: inline-block;">++</span><textarea name="more_info[]" style="min-width: 400px;min-height: 20px;height: 32px;"></textarea><span onclick="hg_delMoreInfo(this);" style="cursor:pointer;margin-left:10px;margin-top:10px;display: inline-block;" name="delMore[]">--</span></div>';
	$('#moreBox').append(html);
	if ($('#moreBox div').length > 1)
	{
		$('span[name^="delMore[]"]').show();
	}
	hg_resize_nodeFrame();
}
function hg_delMoreInfo(obj)
{
	$(obj).parent().remove();
	if ($('#moreBox div').length == 1)
	{
		$('span[name^="delMore[]"]').hide();
	}
	hg_resize_nodeFrame();
}
</script>
