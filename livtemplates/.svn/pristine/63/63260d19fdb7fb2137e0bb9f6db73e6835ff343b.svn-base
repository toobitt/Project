{template:head}
{css:common/common_category}
{js:hg_sort_box}
{css:column_node}
{js:column_node}
{css:ad_style}
{js:2013/ajaxload_new}
{js:page/page}
{js:jquerywidget/get_recipient}
{code}

	/*所有选择控件基础样式*/
	$all_select_style = array(
		'class' 	=> 'down_list',
		'state' 	=> 	0,
		'is_sub'	=>	1,
	);
{/code}
{if is_array($formdata) && $a == 'update'}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
<style>
.ad_form .form_ul .form_ul_div span.title{width:80px!important;}
</style>
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
					<h2>{$optext}通知</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">通知名称：</span>
								<input type="text" value="{$title}" name='title' required="true" style="width:257px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">通知正文：</span>
								<textarea name='content' required="true" style="width:257px;">{$content}</textarea>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">类型：</span>								
								<select name="type" class="utype">
								{foreach $_configs['type'] as $key=>$val}
								<option value="{$key}" {if($key==$type)}selected{/if}>{$val}</option>
								{/foreach}
								</select>
							</div>
						</li> 
						<li class="i owntypes">
						</li>
						<!-- 
						<li class="i owntype tp1">
							<div class="form_ul_div">
							    <span class="title" style="width:75px;">接收人名称：</span>
							    <input type="text" name="owner_uname[]" value="{if $type==1}{$owner_uname}{/if}"/>
							    </div>
							<div class="form_ul_div">
							    <span class="title" style="width:75px;">接收人类型：</span>
							    <input type="radio" name="owner_utype" {if ($owner_utype !=2)} checked  {/if} value="1">会员
							    <input type="radio" name="owner_utype" {if ($owner_utype ==2)} checked {/if} value="2">管理员
							</div>
						</li> 
						<li class="i owntype tp2">
							<div class="form_ul_div">
							    <span class="title" style="width:75px;">接收组名称：</span>
							    {code}$groups = $groups[0];{/code}
							    {foreach $groups as $key=>$v}
							    <input type="checkbox" name="owner_uname[]" {if ($owner_uname ==$v['name'])} checked {/if} _owner_uid="{$v['id']}" value="{$v['name']}">{$v['name']}
							    {/foreach}
							</div>
						</li>
						<li class="i owntype tp3">
							<div class="form_ul_div">
							    <span class="title" style="width:75px;">m2o角色名称：</span>
							    {code}$roles = $roles[0];{/code}
							    {foreach $roles as $key=>$v}
							    <input type="checkbox" name="owner_uname[]" {if ($owner_uname ==$v['name'])} checked {/if} _owner_uid="{$v['id']}" value="{$v['name']}">{$v['name']}
							    {/foreach}
							</div>
						</li>
						<li class="i owntype tp4">
							<div class="form_ul_div">
							    <span class="title" style="width:75px;">接收组名称：</span>
							    {code}$orgs = $orgs[0];{/code}
							    {foreach $orgs as $key=>$v}
							    <input type="checkbox" name="owner_uname[]" {if ($owner_uname ==$v['name'])} checked {/if} _owner_uid="{$v['id']}" value="{$v['name']}">{$v['name']}
							    {/foreach}
							</div>
						</li>
						
						 <li class="i">
						  <div class="form_ul_div single_uname">
						    <span class="title" style="width:75px;">接收人名称：</span>
							<div class="">
					            <input type="text" name="owner_uname[]" id="recipient" value="{if $type==1}{$owner_uname}{/if}" data-member="8396,8395"/>
				            </div>           
							</div>
						</li> -->
						<li class="i">
							<div class="form_ul_div">
								<span class="title">发送时间：</span>
								<input type="text" class="date-picker" _time="true" value="{if $send_time}{$send_time}{/if}" name='send_time' style="width:257px;">
							    <span class="color">(不设置默认当前时间)</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">开始时间：</span>
								<input type="text" class="date-picker" _time="true" value="{if $from_time}{$from_time}{/if}" name='from_time' style="width:257px;">
							    <span class="color">(不设置默认当前时间)</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">结束时间：</span>
								<input type="text" class="date-picker" _time="true" value="{if $to_time}{$to_time}{/if}" name='to_time' style="width:257px;">
							    <span class="color">(不设置默认永久有效)</span>
							</div>
						</li>
					</ul>
				<input type="hidden" name="send_uid" value="{$send_uid}" />
				<input type="hidden" name="send_uname" value="{$send_uname}" />
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
				<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
	<style>.owntype{display:none;}
	.owner_uname {display:inline-block;}
	.owner_uname p {width:18px;height:18px;background:#357ed3;border-radius:50%;color:#eee;text-align:center;line-height:18px;margin:4px 2px 0px 2px;font-size:14px;cursor:pointer;display:inline-block;}</style>	
	<script type="text/x-jquery-tmpl" id="tp1">
			<div class="form_ul_div single_uname">
				<span class="title" style="width:75px;">接收人名称：</span>
			    <input type="text" name="owner_uname" id="recipient" readonly="readonly" style="width:256px;" value="{if $type==1}{$owner_uname}{/if}" data-name="{$owner_uname}" data-id="{$owner_uid}" data-type="{if $owner_utype}{$owner_utype}{else}1{/if}"/>
			</div>           
			<!--<div class="form_ul_div">
				<span class="title" style="width:75px;">接收人类型：</span>
				<input type="radio" name="owner_utype" checked value="1">会员
				<input type="radio" name="owner_utype" {if ($owner_utype ==2 && $type==1)} checked {/if} value="2">管理员
		    </div>-->
	</script>
	<script type="text/x-jquery-tmpl" id="tp2">
		 <div class="form_ul_div">
			<span class="title" style="width:75px;">接收组名称：</span>
			{foreach $groups as $key=>$v}
			<input type="checkbox" name="owner_uname[]" {if ($owner_uname ==$v['name'])} checked {/if} _owner_uid="{$v['id']}" value="{$v['name']}@{$v['id']}">{$v['name']}
			{/foreach}
		</div>
	</script>
	<script type="text/x-jquery-tmpl" id="tp3">
		<div class="form_ul_div">
			<span class="title" style="width:75px;">m2o角色名称：</span>
			{foreach $roles as $key=>$v}
			<input type="checkbox" name="owner_uname[]" {if ($owner_uname ==$v['name'])} checked {/if} _owner_uid="{$v['id']}" value="{$v['name']}@{$v['id']}">{$v['name']}
			{/foreach}
		</div>
	</script>
	<script type="text/x-jquery-tmpl" id="tp4">
		<div class="form_ul_div">
			<span class="title" style="width:75px;">接收组名称：</span>
			{foreach $orgs as $key=>$v}
			<input type="checkbox" name="owner_uname[]" {if ($owner_uname ==$v['name'])} checked {/if} _owner_uid="{$v['id']}" value="{$v['name']}@{$v['id']}">{$v['name']}
			{/foreach}
		</div>
	</script>
	<script type="text/x-jquery-tmpl" id="tp5">
		<div class="form_ul_div">
			<span class="title" style="width:75px;">接收人名称：</span>
			<div>所有人</div>
		</div>
	</script>
	<script>
	$(function(){
		var utype = $('.utype').val();
		var i = parseInt(utype);
		$(".owntypes").html($("#tp"+i).html())
		$('.utype').change(function(){
			var utype = $('.utype').val();
			var i = parseInt(utype);
			$(".owntypes").html($("#tp"+i).html());
			if( i == 1){
				initMember( 'recipient' , 'run.php?a=getMemberInfo&method=show' , 'run.php?a=getAuthInfo&method=show' );					/*再次实例化 get_recipient组件*/
			}
			
		});
		if( utype == 1){
			initMember( 'recipient' , 'run.php?a=getMemberInfo&method=show' , 'run.php?a=getAuthInfo&method=show' );
		}
		function initMember( target , url , mUrl ){
			$('body').get_recipient({
				target : target,
				needType : true,   /*是否需要会员与管理员切换*/
				muti : true,	   /*是否允许多选*/
				infoUrl : url,
				manageUrl : mUrl,
				title : '接收人名称',
				callback : function( event , info ){
					$('#recipient').val( info.names );
				}
			});
		}
	})
	
	</script>
	<script>
	//var i = 1; 
	//$(".add").live('click',function(){ 
		//if(i < 100)
		//{
		//	$(this).parent().parent().append('<div class="owner_uname"><input type="text" name="owner_uname[]" value=""/><input type="hidden" name="owner_uid[]" value=""/><p class="add">+</p> <p class="del">-</p></div>');
		//	i++;
		//}
		//else{alert("最多加100个！");} 
	//})
	//$('.del').live('click',function(){ 
		//if(i < 2){ alert("至少保留一个！");}
		//else{
		//	$(this).parent().remove();
		//	i--;}
		//});
	</script>
{template:foot}