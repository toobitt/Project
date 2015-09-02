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
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" class="ad_form h_l">
					<h2>申请详情</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">叮当账号</span>
								<input type="text" value="{$dingdone_name}" style="width:257px;" readonly />
							</div>
							
							<div class="form_ul_div">
								<span  class="title">应用名称</span>
								<input type="text" value="{$app_info['name']}" style="width:257px;" readonly />
							</div>
							
							<div class="form_ul_div">
								<span  class="title">二维码</span>
								<img src="{$app_info['qrcode_url']}" width='280px' height='280px' />
							</div>
							
							<div class="form_ul_div">
								<span  class="title">开发者类型</span>
								<input type="text" value="{$dev_type_text}" style="width:257px;" readonly />
							</div>
							
							<div class="form_ul_div">
								<span  class="title">姓名</span>
								<input type="text" value="{$name}" style="width:257px;" readonly />
							</div>
							
							<div class="form_ul_div">
								<span  class="title">证件号</span>
								<input type="text" value="{$identity_num}" style="width:257px;" readonly />
							</div>
							
							<div class="form_ul_div">
								<span  class="title">证件扫描</span>
								{code}
									$img_url = '';
									if($identity_photo && is_array($identity_photo))
									{
										$img_url = $identity_photo['host'] . $identity_photo['dir'] . $identity_photo['filepath'] . $identity_photo['filename'];
									}
								{/code}
								<img src="{$img_url}"  width="240" height="180" />
							</div>
							
							<div class="form_ul_div">
								<span  class="title">省份</span>
								<input type="text" value="{$province_name}" style="width:257px;" readonly />
							</div>
							
							<div class="form_ul_div">
								<span  class="title">市</span>
								<input type="text" value="{$city_name}" style="width:257px;" readonly />
							</div>
							
							<div class="form_ul_div">
								<span  class="title">区/县</span>
								<input type="text" value="{$district_name}" style="width:257px;" readonly />
							</div>
							
							<div class="form_ul_div">
								<span  class="title">联系地址</span>
								<input type="text" value="{$address}" style="width:257px;" readonly />
							</div>
							
							<div class="form_ul_div">
								<span  class="title">联系人</span>
								<input type="text" value="{$link_man}" style="width:257px;" readonly />
							</div>
							
							<div class="form_ul_div">
								<span  class="title">手机号</span>
								<input type="text" value="{$telephone}" style="width:257px;" readonly />
							</div>
							
							<div class="form_ul_div">
								<span  class="title">固定电话</span>
								<input type="text" value="{$fixed_line}" style="width:257px;" readonly />
							</div>
							
							<div class="form_ul_div">
								<span  class="title">电子邮箱</span>
								<input type="text" value="{$email}" style="width:257px;" readonly />
							</div>
							
							<div class="form_ul_div">
								<span  class="title">申请理由</span>
								<textarea style="width:400px;height:200px;" readonly>{$reason}</textarea>
							</div>

							<div class="form_ul_div">
								<span  class="title">公司传真</span>
								<input type="text" value="{$company_fax}" style="width:257px;" readonly />
							</div>
							
							
							<div class="form_ul_div">
								<span  class="title">公司网站</span>
								<input type="text" value="{$company_site}" style="width:257px;" readonly />
							</div>
							
							<div class="form_ul_div">
								<span  class="title">公司简介</span>
								<textarea style="width:400px;height:200px;" readonly>{$company_brief}</textarea>
							</div>
							
							<div class="form_ul_div">
								<span  class="title">技术要求</span>
								<input type="text" value="{$has_tech}" style="width:257px;" readonly />
							</div>
							<div class="form_ul_div">
								<span  class="title">营销要求</span>
								<input type="text" value="{$has_market}" style="width:257px;" readonly />
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div" style="height:30px;">
								<span  class="title">审核状态</span>
								{code}
									$item_source = array(
										'class' 	=> 'down_list',
										'show' 		=> 'identity_auth_status',
										'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
										'is_sub'	=>	1,
									);
									
									if($dev_status)
									{
										$default = $dev_status;
									}
									else
									{
										$default = 0;
									}
								{/code}
								<div>
									{template:form/search_source,dev_status,$default,$_configs['identity_auth_status'],$item_source}
								</div>
							</div>
							<div class="form_ul_div">
								<span  class="title">拒绝理由</span>
								<textarea name="refuse_develop_reason" style="width:400px;height:100px;">{$refuse_develop_reason}</textarea>
							</div>
						</li>
					</ul>
				<input type="hidden" name="a" value="audit" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" value="确认" class="button_6_14" style="margin-left:28px;"/>
				<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}