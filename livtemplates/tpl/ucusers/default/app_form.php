<?php 
/* $Id: app_form.php 9495 2012-05-30 05:14:09Z lijiaying $ */
?>
{template:head}
{js:ucusers}
{css:vod_style}
{css:ad_style}
{css:ucusers_style}
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
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div class="content clear">
	<div class="f">
		<div class="right v_list_show" style="float:none;">
			<h2 style="float: right;margin-right: 100px;"><a href="{$_INPUT['referto']}">返回前一页</a></h2>
		
			<div class="">
			<form name="editform" id="editform" class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" method="post" enctype='multipart/form-data' onsubmit="return hg_ajax_submit('editform','','','');">
				<h2>{$optext}应用</h2>
				<ul class="form_ul">
					<li class="i">
					{if $a == 'update'}
						<div class="formUlLiDiv">
							<span class="title" style="width: 500px;margin: 20px 0px;">ID: {$appid}</span>
						</div>
					{else}
						<div class="formUlLiDiv">
							<span class="title" style="width: 500px;margin: 20px 0px;">选择安装类型：</span>
							<input onclick="hg_installtype(0);" class="n-h-s" id="installtype_1" checked="checked" type="radio" name="installtype" />
							<label onclick="hg_installtype(0);" class="s-s" for="installtype_1">自定义安装</label>
							<input onclick="hg_installtype(1);" class="n-h-s ml_50" id="installtype_2" type="radio" name="installtype" />
							<label onclick="hg_installtype(1);" class="s-s" for="installtype_2">URL 安装 (推荐)</label>
						</div>
					{/if}
					</li>
					<li class="i" id="box_1">
						<div class="formUlLiDiv">
							<span class="title">应用类型:</span>
							<div style="display:inline-block;">
							{code}
								$app_type = array(
									'class' => 'transcoding down_list',
									'show' => 'state_show',
									'width' => 120,/*列表宽度*/
									'state' => 0,/*0--正常数据选择列表，1--日期选择*/
									'is_sub'=> 0,
								);
								
								$attr_type = $_configs['app_type'];
								$default = $type ? $type : -1;
							{/code}
							{template:form/search_source,type,$default,$attr_type,$app_type}
							</div>
						</div>
					</li>
					<li class="i" id="box_2">
						<div class="formUlLiDiv">
							<span class="title">应用名称:</span>
							<input type="text" name="name" value="{$name}" />
							<font class="font6">限 20 字节。</font>
						</div>
						<div class="formUlLiDiv">
							<span class="title">应用的主 URL:</span>
							<input type="text" name="url" value="{$url}" />
							<font class="font6">该应用与 UCenter 通信的接口 URL，结尾请不要加“/” ，应用的通知只发送给主 URL</font>
						</div>
						<div class="formUlLiDiv">
							<span class="title">应用的其他 URL:</span>
							<textarea name="extraurl" >{$extraurl}</textarea>
							<font class="font">该应用可以访问的其他 URL，结尾请不要加“/” ，每行一个，只有在同步登录是请求该 URL</font>
						</div>
						<div class="formUlLiDiv">
							<span class="title">应用 IP:</span>
							<input type="text" name="ip" value="{$ip}" />
							<font class="font6">正常情况下留空即可。如果由于域名解析问题导致 UCenter 与该应用通信失败，请尝试设置为该应用所在服务器的 IP 地址。</font>
						</div>
						<div class="formUlLiDiv">
							<span class="title">通信密钥:</span>
							<input type="text" name="authkey" value="{$authkey}" />
							<font class="font6">只允许使用英文字母及数字，限 64 字节。应用端的通信密钥必须与此设置保持一致，否则该应用将无法与 UCenter 正常通信。</font>
						</div>
						<div class="formUlLiDiv">
							<span class="title">应用的物理路径:</span>
							<input type="text" name="apppath" value="{$apppath}" />
							<font class="font6">默认请留空，如果填写的为相对路径（相对于UC），程序会自动转换为绝对路径，如 ../</font>
						</div>
						<div class="formUlLiDiv">
							<span class="title">查看个人资料页面地址:</span>
							<input type="text" name="viewprourl" value="{$viewprourl}" />
							<font class="font6">URL中域名后面的部分，如：/space.php?uid=%s 这里的 %s 代表uid</font>
						</div>
						<div class="formUlLiDiv">
							<span class="title">应用接口文件名称:</span>
							<input type="text" name="apifilename" {if $action == 'update'}value="{$apifilename}"{else}value="uc.php"{/if} />
							<font class="font6">应用接口文件名称，不含路径，默认为uc.php</font>
						</div>
						<div class="formUlLiDiv">
							<span class="title">标签单条显示模板:</span>
							<textarea name="tagtemplates" >{$template}</textarea>
							<font class="font">当前应用的标签数据显示在其它应用时的单条数据模板。</font>
						</div>
						<div class="formUlLiDiv">
							<span class="title">标签模板标记说明:</span>
							<textarea name="tagfields" >{$fields}</textarea>
							<font class="font">一行一个标记说明条目，用逗号分割标记和说明文字。如：subject,主题标题 url,主题地址</font>
						</div>
					</li>
					<li class="i" id="box_3">
						<div class="formUlLiDiv">
							<span class="title">是否开启同步登录:</span>
							<input class="n-h-s" id="synlogin_1" type="radio" value=1 name="synlogin" {if $synlogin}checked='checked'{/if} />
							<label class="s-s" for="synlogin_1">是</label>
							<input class="n-h-s ml_50" id="synlogin_2" type="radio" value=0 name="synlogin" {if !$synlogin}checked='checked'{/if} />
							<label class="s-s" for="synlogin_2">否</label>
							<font class="font6" style="margin-left: 526px;">开启同步登录后，当用户在登录其他应用时，同时也会登录该应用。</font>
						</div>
						<div class="formUlLiDiv">
							<span class="title">是否接受通知:</span>
							<input class="n-h-s" id="recvnote_1" type="radio" value=1 name="recvnote" {if $recvnote}checked='checked'{/if} />
							<label class="s-s" for="recvnote_1">是</label>
							<input class="n-h-s ml_50" id="recvnote_2" type="radio" value=0 name="recvnote" {if !$recvnote}checked='checked'{/if} />
							<label class="s-s" for="recvnote_2">否</label>
						</div>
					</li>
				</ul>
				</br>
				<input type="submit" name="sub" value="提交" id="sub" class="button_6_14"/>
				<input type="hidden" name="a" value="{$action}" id="action" />
				<input type="hidden" name="id" value="{$appid}" id="appid" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
			</form>
			</div>
			<div id="box_5" style="display:none;">
				<form id="appform" target="_blank" class="ad_form h_l" action="" method="post" enctype='multipart/form-data' onsubmit="document.appform.action=document.appform.appurl.value;" name="appform">
					<ul class="form_ul">
						<li class="i">
							<div class="formUlLiDiv">
								<span class="title" style="width: 500px;margin: 20px 0px;">应用程序安装地址:</span>
								<input type="text" name="appurl" style="width:400px;" value="http://domainname/install/index.php" />
								<font class="font6"></font>
							</div>
						</li>
					</ul>
					<br />
					<input type="submit" name="sub2" value="安装" id="sub2" class="button_6_14"/>
				</form>
			</div>
			<div id="conf_box" {if $action == 'create'}style="display:none;"{/if}>
				<ul class="form_ul">
					<li class="i">
						<div class="formUlLiDiv">
							<span class="title">应用的 UCenter 配置信息:</span>
							<textarea id="getconfig">define('UC_CONNECT', '{$conf['UC_CONNECT']}');
							define('UC_DBHOST', '{$conf['UC_DBHOST']}');
							define('UC_DBUSER', '{$conf['UC_DBUSER']}');
							define('UC_DBPW', '{$conf['UC_DBPW']}');
							define('UC_DBNAME', '{$conf['UC_DBNAME']}');
							define('UC_DBCHARSET', '{$conf['UC_DBCHARSET']}');
							define('UC_DBTABLEPRE', '`{$conf['UC_DBNAME']}`.{$conf['UC_DBTABLEPRE']}');
							define('UC_DBCONNECT', {$conf['UC_DBCONNECT']});
							define('UC_KEY', '{$conf['UC_KEY']}');
							define('UC_API', '{$conf['UC_API']}');
							define('UC_CHARSET', '{$conf['UC_CHARSET']}');
							define('UC_IP', '{$conf['UC_IP']}');
							define('UC_APPID', '{$conf['UC_APPID']}');
							define('UC_PPP', {$conf['UC_PPP']});
							</textarea>
							<font class="font">当应用的 UCenter 配置信息丢失时可复制左侧的代码到应用的配置文件中</font>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
</body>
{template:foot}