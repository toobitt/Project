<?php 
/* $Id: setting_list.php 9478 2012-05-29 06:56:17Z lijiaying $ */
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
			<div>
			<form name="editform" id="editform" class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" method="post" enctype='multipart/form-data' onsubmit="return hg_ajax_submit('editform','','','');">
				<h2>基本设置</h2>
				<ul class="form_ul">
					<li class="i">
						<div class="formUlLiDiv">
							<span class="title">日期格式：</span>
							<input type="text" name="dateformat" value="{$dateformat}" />
							<font class="font2">使用 yyyy(yy) 表示年，mm 表示月，dd 表示天。如 yyyy-mm-dd 表示 2000-1-1</font>
						</div>
						<div class="formUlLiDiv">
							<span class="title">时间格式：</span>
							<input class="n-h-s" id="hr24" type="radio" value=1 name="timeformat" {if $timeformat}checked='checked'{/if} />
							<label class="s-s" for="hr24">24 小时制</label>
							<input class="n-h-s ml_50" id="hr12" type="radio" value=0 name="timeformat" {if !$timeformat}checked='checked'{/if} />
							<label class="s-s" for="hr12">12 小时制</label>
						</div>
						<div class="formUlLiDiv">
							<span class="title">时区：</span>
							<div style="display:inline-block;">
							{code}
								$timeoffset_type = array(
									'class' => 'transcoding down_list',
									'show' => 'state_show',
									'width' => 410,/*列表宽度*/
									'state' => 0,/*0--正常数据选择列表，1--日期选择*/
									'is_sub'=> 0,
								);
								$timeoffset = $timeoffset/3600;

								$attr_type = $_configs['timeoffset_type'];

								$default = $timeoffset ? $timeoffset : '8';

							{/code}
							{template:form/search_source,timeoffset,$default,$attr_type,$timeoffset_type}
							</div>
							<font class="font2" style="margin-left: 103px;position: relative;top: -8px;left: -10px;">默认为: GMT +08:00</font>
						</div>
					</li>
					<li class="i">
						<div class="formUlLiDiv">
							<span class="title">发短消息最少注册天数：</span>
							<input type="text" name="pmsendregdays" value="{$pmsendregdays}" />
							<font class="font2">注册天数少于此设置的，不允许发送短消息，0为不限制，此举为了限制机器人发广告</font>
						</div>
						<div class="formUlLiDiv">
							<span class="title">同一用户在 24 小时内允许发起两人会话的最大数:</span>
							<input type="text" name="privatepmthreadlimit" value="{$privatepmthreadlimit}" />
							<font class="font2">
								同一用户在 24 小时内可以发起的两人会话数的最大值，建议在 30 - 100 范围内取值，0 为不限制，此举为了限制通过机器批量发广告
							</font>
						</div>
						<div class="formUlLiDiv">
							<span class="title">同一用户在 24 小时内允许发起群聊会话的最大数:</span>
							<input type="text" name="chatpmthreadlimit" value="{$chatpmthreadlimit}" />
							<font class="font2">
								同一用户在 24 小时内可以发起的群聊会话的最大值，建议在 30 - 100 范围内取值，0 为不限制，此举为了限制通过机器批量发广告
							</font>
						</div>
						<div class="formUlLiDiv">
							<span class="title">参与同一群聊会话的最大用户数:</span>
							<input type="text" name="chatpmmemberlimit" value="{$chatpmmemberlimit}" />
							<font class="font2">同一会话最多能有多少用户参与设置，建议在 30 - 100 范围内取值，0为不限制</font>
						</div>
						<div class="formUlLiDiv">
							<span class="title">发短消息灌水预防:</span>
							<input type="text" name="pmfloodctrl" value="{$pmfloodctrl}" />
							<font class="font2">两次发短消息间隔小于此时间，单位秒，0 为不限制，此举为了限制通过机器批量发广告</font>
						</div>
						
					</li>
					<li class="i">
						<div class="formUlLiDiv">
							<span class="title">启用短消息中心：</span>
							<input class="n-h-s" id="pmcenter_1" type="radio" value=1 name="pmcenter" {if $pmcenter}checked='checked'{/if} />
							<label class="s-s" for="pmcenter_1">是</label>
							<input class="n-h-s ml_50" id="pmcenter_2" type="radio" value=0 name="pmcenter" {if !$pmcenter}checked='checked'{/if} />
							<label class="s-s" for="pmcenter_2">否</label>
							<font class="font1">是否启用短消息中心功能，不影响使用短消息接口应用程序的使用</font>
						</div>
						<div class="formUlLiDiv">
							<span class="title">开启发送短消息验证码：</span>
							<input class="n-h-s" id="sendpmseccode_1" type="radio" value=1 name="sendpmseccode" {if $sendpmseccode}checked='checked'{/if} />
							<label class="s-s" for="sendpmseccode_1">是</label>
							<input class="n-h-s ml_50" id="sendpmseccode_2" type="radio" value=0 name="sendpmseccode" {if !$sendpmseccode}checked='checked'{/if} />
							<label class="s-s" for="sendpmseccode_2">否</label>
							<font class="font1">是否开启短消息中心发送短消息验证码，可以防止使用机器狂发短消息</font>
						</div>
					</li>
					
				</ul>
				</br>
				<input type="submit" name="sub" value="提交" id="sub" class="button_6_14"/>
				<input type="hidden" name="a" value="settingBasic" id="action" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
			</form>
			</div>
		</div>
	</div>
</div>
</body>
{template:foot}