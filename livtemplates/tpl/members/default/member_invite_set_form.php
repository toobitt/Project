<?php 
/* $Id: stream_form.php 2361 2011-10-28 09:56:50Z ayou $ */
?>
{template:head}
{css:ad_style}
{css:bigcolorpicker}
{css:member_form}
{css:member_configuration}
{js:jqueryfn/jquery.tmpl.min}
{js:bigcolorpicker}
{js:area}

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
{code}//print_r($credits);{/code}
<script>
$.globaldefault = {code} echo json_encode($formdata['field_default']);{/code}
</script>
<div class="wrap clear">
<div class="ad_middle" style="width:850px">
{if $formdata}
<form name="editform" action="" method="post" enctype='multipart/form-data' class="ad_form h_l">
<h2>邀请配置</h2>
<ul class="form_ul">
<li class="i">
	<span class="basic-configuration">邀请人奖励:</span>
	<div class="continuous-sign">
							<div class="item">
			<span class="timeopen">是否开启邀请人奖励:</span>
			<div class="configuration mar20">
				<div class="type">
					<input type="radio" name="is_invitedaddcredit" class="on" value="1" id="yes" {if $invitedaddcredit['is_addcredit']}checked{/if}/>
					<label for="yes">是</label>
				</div>
				<div class="type">
					<input type="radio" name="is_invitedaddcredit" class="no" value="0" id="no" {if !$invitedaddcredit['is_addcredit']}checked{/if}/>
					<label for="no">否</label>
				</div>
			</div>
		</div>
		<div id="is_addcredit_d">
		<div class="item">
			<div class="configuration">
				{if is_array($get_credit_type)}
				{foreach $get_credit_type as $key => $value}
				<div class="type">
					<span class="title configuration-title">{$value['title']}: </span>
					<input type="text" name="invitedaddcredit_base[{$value['db_field']}]"  value="{$invitedaddcredit['base'][$value['db_field']]}" style="width:50px;" />
				</div>
					{/foreach}
				{/if}
			</div>
		</div>
		</div>
	</div>
</li>
<li class="i">
	<span class="basic-configuration">被邀请人奖励:</span>
	<div class="continuous-sign">
							<div class="item">
			<span class="timeopen">是否开启被邀请人奖励:</span>
			<div class="configuration mar20">
				<div class="type">
					<input type="radio" name="is_inviteaddcredit" class="on" value="1" id="yes" {if $inviteaddcredit['is_addcredit']}checked{/if}/>
					<label for="yes">是</label>
				</div>
				<div class="type">
					<input type="radio" name="is_inviteaddcredit" class="no" value="0" id="no" {if !$inviteaddcredit['is_addcredit']}checked{/if}/>
					<label for="no">否</label>
				</div>
			</div>
		</div>
		<div id="is_addcredit">
		<div class="item">
			<div class="configuration">
				{if is_array($get_credit_type)}
				{foreach $get_credit_type as $key => $value}
				<div class="type">
					<span class="title configuration-title">{$value['title']}: </span>
					<input type="text" name="inviteaddcredit_base[{$value['db_field']}]"  value="{$inviteaddcredit['base'][$value['db_field']]}" style="width:50px;" />
				</div>
					{/foreach}
				{/if}
			</div>
		</div>
		</div>
	</div>
</li>
<li class="i">
	<span class="basic-configuration">功能配置:</span>
	<div class="continuous-sign">
						<div class="item">
			<span class="timeopen">是否开启邀请时间限制:</span>
			<div class="configuration mar20">
				<div class="type">
					<input type="radio" name="is_invite_endtime" class="on" value="1" id="yes" {if $invite_endtime}checked{/if}/>
					<label for="yes">是</label>
				</div>
				<div class="type">
					<input type="radio" name="is_invite_endtime" class="no" value="0" id="no" {if !$invite_endtime}checked{/if}/>
					<label for="no">否</label>
				</div>
			</div>
		</div>
		<div id="limit_time">
			<div class="item">
			<span class="limit_time">有效时间(单位:小时):</span>
			<div class="configuration mar20">
	<input type="text" name="invite_endtime"  value="{$invite_endtime}" style="width:80px;"/>
			</div>
			</div>
		</div>
		
	</div>
</li>
</ul>
<input type="hidden" name="a" value="{$action}" />
<input type="hidden" name="is_del" id="is_del" value="0" />
<input type="hidden" name="{$primary_key}" value="{$formdata['id']}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<div class="temp-edit-buttons">
<input type="submit" name="sub" value="{$optext}" class="edit-button submit"/>
<input type="button" value="取消" class="edit-button cancel" onclick="javascript:history.go(-1);"/>
</div>
</form>
{else}
<div style="font-size:20px;color:red;padding: 30px;"> 没有数据 </div>
{/if}

</div>
<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}
<script type="text/x-jquery-tmpl" id="option-tpl">
<div class="item">
	<span class="lastedop">第<a class="index">{{= index}}</a>天:</span>
	<div class="configuration mar20">
	{if is_array($get_credit_type)}
	{foreach $get_credit_type as $key => $value}
	<div class="type">
		<span class="title configuration-title">{$value['title']}: </span>
		<input type="text" name="credits_lastedop[{$value['db_field']}][]"  value="" style="width:50px;" />
	</div>
	{/foreach}
	{/if}
	</div>
	<p class="btn add">+</p>
	<p class="btn delete">x</p>
</div>
</script>
<script type="text/javascript">
$(function(){
	var is_addcredit = Number($('input[name="is_inviteaddcredit"]:checked').val());
	var is_addcredit_d = Number($('input[name="is_invitedaddcredit"]:checked').val());
	var is_invite_endtime = Number($('input[name="is_invite_endtime"]:checked').val());
	if(is_addcredit==0||is_addcredit_d==0)
	{
		$('#is_addcredit').toggle();
	}
	if(is_invite_endtime==0)
	{
		$('#limit_time').toggle();
	}
		$('input[name="is_invite_endtime"]').on('click' , function(){
			var checked = $('input[name="is_invite_endtime"]:checked').val(),
				obj = $('#limit_time');
			checked==0 ? obj.hide() :  obj.show();
		});
		$('input[name="is_inviteaddcredit"]').on('click' , function(){
			var checked = $('input[name="is_inviteaddcredit"]:checked').val(),
				obj = $('#is_addcredit');
			checked==0 ? obj.hide() :  obj.show();
		});
	$('input[name="is_invitedaddcredit"]').on('click' , function(){
		var checked = $('input[name="is_invitedaddcredit"]:checked').val(),
			obj = $('#is_addcredit_d');
		checked==0 ? obj.hide() :  obj.show();
	});
	
});
</script>

