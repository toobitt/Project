
{template:head}
{css:ad_style}
{css:vote_style}
{js:mms_default}
{js:input_file}
{js:message}
{js:vote}
{css:column_node}
{js:column_node}

{if $a == 'create'}
<script type="text/javascript">
/*获取问卷默认设置*/
$(function(){
	var admin_id = gAdmin.admin_id;
	var url = './run.php?mid=' + gMid + '&a=getDefaultSettings&admin_id=' + admin_id;
	hg_ajax_post(url,'','','getDefaultSettings_back');
});
function getDefaultSettings_back(obj)
{
	var obj = obj[0];
	if (obj.is_ip == 1)
	{
		$('input[name="is_ip"]').attr('checked','checked');
	}
	if (obj.is_userid == 1)
	{
		$('input[name="is_userid"]').attr('checked','checked');
	}
	if (obj.is_verify_code == 1)
	{
		$('input[name="is_verify_code"]').attr('checked','checked');
	}
	if (obj.state == 1)
	{
		$('input[name="state"]').attr('checked','checked');
	}
	if (obj.is_logo == 1)
	{
		$('input[name="is_logo"]').attr('checked','checked');
	}
}
</script>
{else}
<script type="text/javascript">
$(function(){
	var num = $('#question_user').find(':checkbox[name^="is_"]:checked').length;
	if (num == 5)
	{
		$('#all_select').attr('checked','checked');
	}
});
</script>
{/if}

{if $a}
	{code}
		$action = $a;
	{/code}
{/if}

{if is_array($formdata)}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
		<div class="ad_middle">
		<form name="editform" action="./run.php?mid={$_INPUT['mid']}&a={$action}" method="post" enctype='multipart/form-data' class="ad_form h_l">
			<h2>{$optext}问卷</h2>
			<ul class="form_ul">
				<li class="i">
					<div class="form_ul_div">
						<span class="title">问卷标题：</span>
						<input type="text" onblur="input_content_color(2);" onfocus="input_content_color(2);" id="required_2" name="title" value="{$title}" style="width:520px;"/>
				<!--		<div style="float:right;">
						{code}
							$item_source = array(
								'class' => 'down_list i',
								'show' => 'item_shows_',
								'width' => 100,/*列表宽度*/		
								'state' => 0, /*0--正常数据选择列表，1--日期选择*/
								'is_sub'=>1,
								'onclick'=>'',
							);
							$default = $group_id ? $group_id : -1;
							$gname[$default] = '选择分类';
							foreach($groupName AS $k =>$v)
							{
								$gname[$v['id']] = $v['name'];
							}
						{/code}
						{template:form/search_source,group_id,$default,$gname,$item_source}
						</div>
					</div>-->
					<div class="form_ul_div">	
						<span class="title">描述：</span>
						{template:form/textarea,describes,$describes}
					</div>
					
					<div class="form_ul_div clear">
						<span class="title">分类：</span>
						{code}
							$hg_attr['node_en'] = 'question_node';
						{/code}
						{template:unit/class,node_id,$node_id,$node_data}
					</div>
					
					<div class="form_ul_div clear">
						<span class="title">图片：</span>
						<span class="file_input s" id="file_input" style="float:left;">选择文件</span>
						<span id="file_text" class="overflow file-text s">{$logo}</span>
						<span id="logo_img" style="float:right;border:1px solid #DADADA;">{if $vote_img}<img width=30 height=30 src="{$vote_img}" />{/if}</span>
						<input onclick="hg_logo_value();" name="files" type="file"  value="" class="vote_file" id="f_file"  hidefocus>
					</div>
				</li>
				<div id="questionBox">
				{if $questions}
					{foreach $questions AS $k => $v}
					{template:unit/question_form}
					{/foreach}
				{else}
					{template:unit/question_create_form}
				{/if}
				</div>
				<li class="i">
					<div class="form_ul_div">
						<span class="vote_question_add_buttom" onclick="hg_addQuestionDom();">添加问题</span>
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div">
						<span class="title">有效期：</span>
						<input  id="start_time" type="text" value="{$start_time}" autocomplete="off" size="20" onfocus="if(1){WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm:ss'});}" name="start_time">
						<span>-</span>
						<input  id="end_time" type="text" value="{$end_time}" autocomplete="off" size="20" onfocus="if(1){WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm:ss'});}" name="end_time">
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div">
						<span class="title">设置：</span>
						<input type="checkbox" value=1 {if $is_ip} checked {/if} name="is_ip" class="n-h-s" /><span class="s-s">同一IP地址限制</span>
						<input id="ip_limit_time" name="ip_limit_time" type="text" value="{$ip_limit_time}" style="margin-left:10px;width:50px;position:relative;top:-4px;" />
						<font class="hours">小时</font>
						<input type="checkbox" value=1  {if $is_userid} checked {/if} name="is_userid" class="n-h-s ml_30" /><span class="s-s">同一用户限制</span>
						<input id="userid_limit_time" name="userid_limit_time" type="text" value="{$userid_limit_time}" style="margin-left:10px;width:50px;position:relative;top:-4px;" />
						<font class="hours">小时</font>
						<input type="checkbox" value=1 {if $is_verify_code} checked {/if} name="is_verify_code" class="n-h-s ml_30" /><span class="s-s">开启验证码</span>
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div">
						<span class="title">其他设置：</span>
						<span id="question_user">
							<input class="n-h-s" onclick="hg_questionUser();" type="checkbox" value=1 name="is_uesrname" {if $is_uesrname} checked {/if} />
							<span class="s-s">用户名</span>
							<input class="n-h-s" onclick="hg_questionUser();" type="checkbox" value=1 name="is_sex" {if $is_sex} checked {/if} />
							<span class="s-s">性别</span>
							<input class="n-h-s" onclick="hg_questionUser();" type="checkbox" value=1 name="is_moblie" {if $is_moblie} checked {/if} />
							<span class="s-s">手机</span>
							<input class="n-h-s" onclick="hg_questionUser();" type="checkbox" value=1 name="is_id_card" {if $is_id_card} checked {/if} />
							<span class="s-s">身份证</span>
							<input class="n-h-s" onclick="hg_questionUser();" type="checkbox" value=1 name="is_other_info" {if $is_other_info} checked {/if} />
							<span class="s-s">其他</span>
						</span>
						<input type="checkbox" onclick="hg_questionUserAll(this);" id="all_select" class="n-h-s" style="margin-left:50px;" /><span class="s-s">全选</span>
						<font class="important" id="important_2">(投票者的相关信息)</font>
					</div>
				</li>
			</ul>
		</br>
		<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14"/>
		<input type="hidden" name="a" value="{$action}" id="action" />
		<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
		<input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
		</form>
		<div id="question_html" style="display:none;">
		{template:unit/question_create_form}
		</div>
<!-- other_box -->
		<div id="livwindialog" class="lightbox" style="width: 780px; z-index: 1000; position: absolute; visibility: visible; margin-left: -300px; top: 0px; left: 50%; display: none; ">
			<div class="lightbox_top">
				<span class="lightbox_top_left"></span>
				<span class="lightbox_top_right"></span>
				<span class="lightbox_top_middle"></span>
			</div>
			<div class="lightbox_middle">
				<span style="position:absolute;right:25px;top:25px;z-index:1000;" onclick="hg_otherClose();">
					<img width="14" height="14" id="livwindialogClose" src="{$RESOURCE_URL}close.gif" style="cursor: pointer; " />
				</span>
				<div id="livwindialogbody" class="text" style="max-height:500px;"></div>
			</div>
			<div class="lightbox_bottom">
				<span class="lightbox_bottom_left"></span>
				<span class="lightbox_bottom_right"></span>
				<span class="lightbox_bottom_middle"></span>
			</div>
		</div>
<!-- other_box -->
		</div>
		<div class="right_version">
			<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
		</div>
{template:foot}