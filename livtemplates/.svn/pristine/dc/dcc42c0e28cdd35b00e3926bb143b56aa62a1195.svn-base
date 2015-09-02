{template:head}
{css:hg_sort_box}
{js:common/auto_textarea}
{js:hg_sort_box}
{js:common/common_form}
{css:common/common_form}
{css:ad_style}
{if is_array($formdata) && $a == 'update'}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
<style type="text/css">
.appinfo{width:500px;height:40px;margin-left:85px;}
.appinfo span{width:150px;height:30px;display:block;line-height:30px;font-size:12px;float:left;}
.appkey{width:500px;height:250px;margin-left:85px;}
.appkey div{width:100%;height:40px;}
.appkey div span{width:100px;height:25px;display:block;float:left;}
.appkey div input{width:300px;float:left;}
</style>
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" class="ad_form h_l">
					<h2>{$optext}账号</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title" style="font-size:14px;font-weight:bold;color:green;">用户信息</span>
								<div class="appinfo"></div>
							</div>
						
							<div class="form_ul_div">
								<span  class="title">用户名</span>
								<input type="text" value="{$user_info['user_name']}" style="width:400px;" readonly />
							</div>
							
							<div class="form_ul_div">
								<span  class="title">头像</span>
								{code}
									$img_url = '';
									if($user_info['avatar'] && is_array($user_info['avatar']))
									{
										$img_url = $user_info['avatar']['host'] . $user_info['avatar']['dir'] . $user_info['avatar']['filepath'] . $user_info['avatar']['filename'];
									}
								{/code}
								<img src="{if $img_url}{$img_url}{else}{$RESOURCE_URL}avatar.jpg{/if}"  width="160" height="120" />
							</div>
							
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title" style="font-size:14px;font-weight:bold;color:green;">应用详情</span>
								<div class="appinfo"></div>
							</div>
							
							<div class="form_ul_div">
								<span  class="title">应用名称</span>
								<input type="text" value="{$app_info['app']['name']}" style="width:400px;" readonly />
							</div>
							
							<div class="form_ul_div">
								<span  class="title">应用图标</span>
								{code}
									$img_url = '';
									if($app_info['app']['icon'] && is_array($app_info['app']['icon']))
									{
										$icon_url = $app_info['app']['icon']['host'] . $app_info['app']['icon']['dir'] . $app_info['app']['icon']['filepath'] . $app_info['app']['icon']['filename'];
									}
								{/code}
								<img src="{$icon_url}"  width="160" height="120" />
							</div>
							
							<div class="form_ul_div">
								<span  class="title">版本信息</span>
								<div class="appinfo">
									<span>Android：{$app_info['version']['android']['status_text']}</span>
									<span>版本：{$app_info['version']['android']['version_name']}</span>
									<span><a href="{$app_info['version']['android']['download_url']}" style="color:green;">下载apk</a></span>
								</div>
								<div class="appinfo">
									<span>ios：{$app_info['version']['ios']['status_text']}</span>
									<span>版本：{$app_info['version']['ios']['version_name']}</span>
									<span><a href="{$app_info['version']['ios']['download_url']}" style="color:green;">下载ipa</a></span>
								</div>
							</div>
							<div class="form_ul_div">
								<span  class="title">Bundle ID</span>
								<input type="text" value="{$app_info['version']['ios']['package_name']}" style="width:400px;" readonly />
							</div>
						
							<div class="form_ul_div">
								<span  class="title">安卓包名</span>
								<input type="text" value="{$app_info['version']['android']['package_name']}" style="width:400px;" readonly />
							</div>
						
							<div class="form_ul_div">
								<span  class="title">安卓签名</span>
								<input type="text" value="" style="width:400px;" readonly />
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title" style="font-size:14px;font-weight:bold;color:green;">推送接口</span>
								<div class="appinfo"></div>
							</div>
							
							<div class="form_ul_div">
								<span  class="title">推送平台</span>
								<input type="text" value="AVOS Cloud" style="width:300px;" readonly />
								{code}
									$item_source = array(
										'class' 	=> 'down_list',
										'show' 		=> 'push_accounts_item_show',
										'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
										'is_sub'	=>	1,
									);
									
									if($user_info['push_accounts_id'])
									{
										$default = $user_info['push_accounts_id'];
									}
									else
									{
										$default = 0;
									}
									$sort[0] = '全部账号';
									if($push_accounts)
									{
										foreach($push_accounts AS $k => $v)
										{
											if(intval($v['plant_type']) == 1)
											{
												$sort[$v['id']] = $v['name'];
											}
										}
									}
								{/code}
								<div style="float:right;margin-right:218px;">
									{template:form/search_source,push_accounts_id,$default,$sort,$item_source}
								</div>
							</div>

							<div class="form_ul_div" style="clear:both;margin-top:16px;">
								<span  class="title">应用KEY</span>
								<div class="appkey">
									<div>
										<span>对应的应用名称</span>
										<input type="text" name="app_name" value="{$user_info['app_name']}" />
									</div>
									<div>
										<span>App ID</span>
										<input type="text" name="app_id" value="{$user_info['app_id']}" />
									</div>
									<div>
										<span>App Key</span>
										<input type="text" name="app_key"  value="{$user_info['app_key']}" />
									</div>
									<div>
										<span>Master Key</span>
										<input type="text" name="master_key" value="{$user_info['master_key']}" />
									</div>
									<div>
										<span>PROV ID</span>
										<input type="text" name="prov_id" value="{$user_info['prov_id']}" />
									</div>
										<input type="hidden" name="uid" value="{$user_info['uid']}" />
										<input type="hidden" name="access_token" value="{$user_info['access_token']}" />
									<div>
										<a href="https://cn.avoscloud.com" class="button_6" target="_blank">去AVOS</a>
									</div>
								</div>
							</div>
						</li>
						
						
						{if $user_info['push_status'] == 3 || $user_info['push_status'] == 5}
						<li class="i" style="height:200px;">
							<div class="form_ul_div">
								<span  class="title" style="font-size:14px;font-weight:bold;color:green;">推送状态</span>
								<div class="appinfo"></div>
							</div>
							<div class="form_ul_div">
								<span  class="title">推送状态</span>
								<div>
									{code}
									/*状态控件*/
									$status_source = array(
										'class' => 'transcoding down_list',
										'show' => 'push_status_show',
										'width' => 104,/*列表宽度*/
										'state' => 0,/*0--正常数据选择列表，1--日期选择*/
									);
									$_push_status = array(
										3 => '待通过',
										5 => '已通过'
									)
									{/code}
									{template:form/search_source,push_status,$user_info['push_status'],$_push_status,$status_source}
								</div>
							</div>
						</li>
						{/if}
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="{$optext}推送接口" class="button_6_14"/>
				<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}