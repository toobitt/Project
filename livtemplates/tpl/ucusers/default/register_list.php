<?php 
/* $Id: register_list.php 9478 2012-05-29 06:56:17Z lijiaying $ */
?>
{template:head}
{js:ucusers}
{css:vod_style}
{css:ad_style}
{css:ucusers_style}
{code}
/*hg_pre($list);*/

{/code}
{if is_array($list)}
	{foreach $list[0] as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div class="content clear">
	<div class="f">
		<div class="right v_list_show" style="float:none;">
			<h2 class="title_bg">
			{template:menu/btn_menu,'','','',$source}
			</h2>
		
			<div class="">
			<form name="editform" id="editform" class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" method="post" enctype='multipart/form-data' onsubmit="return hg_ajax_submit('editform','','','');">
				<h2>注册设置</h2>
				<ul class="form_ul">
					<li class="i">
						<div class="formUlLiDiv">
							<span class="title" style="width: 500px;margin: 20px 0px;">允许/禁止的 Email 地址只需填写 Email 的域名部分，每行一个域名，例如 @hotmail.com</span>
						</div>
					</li>
					<li class="i">
						<div class="formUlLiDiv">
							<span class="title">是否允许同一 Email 地址注册多个用户:</span>
							<input class="n-h-s" id="doublee_1" type="radio" value=1 name="doublee" {if $doublee}checked='checked'{/if} />
							<label class="s-s" for="doublee_1">是</label>
							<input class="n-h-s ml_50" id="doublee_2" type="radio" value=0 name="doublee" {if !$doublee}checked='checked'{/if} />
							<label class="s-s" for="doublee_2">否</label>
						</div>
					</li>
					<li class="i">
						<div class="formUlLiDiv">
							<span class="title">允许的 Email 地址:</span>
							<textarea name="accessemail" >{$accessemail}</textarea>
							<font class="font">只允许使用这些域名结尾的 Email 地址注册。</font>
						</div>
						<div class="formUlLiDiv">
							<span class="title">禁止的 Email 地址:</span>
							<textarea name="censoremail" >{$censoremail}</textarea>
							<font class="font">禁止使用这些域名结尾的 Email 地址注册。</font>
						</div>
						<div class="formUlLiDiv">
							<span class="title">禁止的用户名:</span>
							<textarea name="censorusername" >{$censorusername}</textarea>
							<font class="font">可以设置通配符，每个关键字一行，可使用通配符 "*" 如 "*版主*"(不含引号)。</font>
						</div>
					</li>
				</ul>
				</br>
				<input type="submit" name="sub" value="提交" id="sub" class="button_6_14"/>
				<input type="hidden" name="a" value="settingRegister" id="action" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
			</form>
			</div>
		</div>
	</div>
</div>
</body>
{template:foot}