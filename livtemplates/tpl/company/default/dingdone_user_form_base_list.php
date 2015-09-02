{template:head}
{css:hg_sort_box}
{js:common/auto_textarea}
{js:hg_sort_box}
{js:common/common_form}
{css:common/common_form}
{css:ad_style}
{if is_array($formdata)}
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
					<h2>{$optext}基本账号</h2>
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
							
							<div class="form_ul_div">
								<span  class="title">邮箱</span>
								<input type="text" value="{$user_info['email']}" style="width:400px;" readonly />
							</div>
							
							<div class="form_ul_div">
								<span  class="title">手机</span>
								<input type="text" value="{$user_info['telephone']}" style="width:400px;" readonly />
							</div>
							
							
							<div class="form_ul_div">
								<span  class="title">描述备注</span>
								<textarea style="width:400px;height:120px;">{$user_info['brief']}</textarea>
							</div>
							<div id="is_developer" class="form_ul_div clear">
								<span class="title">用户角色: </span>
								<ul class="type-choose clear">
									<li><input type="radio" name="is_developer" class="is_developer" {if $user_info['dingdone_role_id'] == 1 } checked="checked"{/if} value="1" /><span>普通用户</span></li>
									<li><input type="radio" name="is_developer" class="is_developer" {if $user_info['dingdone_role_id'] == 2 } checked="checked"{/if} value="2" /><span>开发者</span></li>
								</ul>
								<span class="error" id="title_tips" style="display:none;"></span>
							</div>
							<div id="is_business" class="form_ul_div clear">
								<span class="title">是否是商业用户: </span>
								<ul class="type-choose clear">
									<li><input type="radio" name="is_business" class="is_business" {if $user_info['is_business'] } checked="checked"{/if} value="1" /><span>是</span></li>
									<li><input type="radio" name="is_business" class="is_business" {if $user_info['is_business'] == 0 } checked="checked"{/if} value="0" /><span>否</span></li>
								</ul>
								<span class="error" id="title_tips" style="display:none;"></span>
							</div>	
							<div id="permission" class="form_ul_div clear">
								<span class="title">用户权限: </span>
								<ul class="type-choose clear" style="padding-left:85px;">
 									{code}
										foreach($permissionInfo as $ko=>$vo)
										{
									{/code}		
											<li>
												<input type="checkbox" name="permission[]" value="{$vo['id']}" {if $vo['have']	}checked="checked"{/if}/>
												<span>{$vo['name']}</span>
											</li>
									{code}
										}	
									{/code}
									
								</ul>
								<span class="error" id="title_tips" style="display:none;"></span>
							</div>
							<div id="module_num" class="form_ul_div clear">
								<span class="title">最多模块数: </span>
								<ul class="type-choose clear" style="padding-left:85px;">
										<input type="radio" name="module_num" value="6" {if $app_info['app']['max_module_num']=='6'}checked="checked"{/if}/><span>6个模块</span>
										<input type="radio" name="module_num" value="8" {if $app_info['app']['max_module_num']=='8'}checked="checked"{/if}/><span>8个模块</span>
										<input type="radio" name="module_num" value="10" {if $app_info['app']['max_module_num']=='10'}checked="checked"{/if}/><span>10个模块</span>
										<input type="radio" name="module_num" value="12" {if $app_info['app']['max_module_num']=='12'}checked="checked"{/if}/><span>12个模块</span>
										<input type="radio" name="module_num" value="14" {if $app_info['app']['max_module_num']=='14'}checked="checked"{/if}/><span>14个模块</span>
								</ul>
								<span class="error" id="title_tips" style="display:none;"></span>
							</div>	
							<div id="is_intest" class="form_ul_div clear">
								<span class="title">是否是测试用户: </span>
								<ul class="type-choose clear">
									<li><input type="radio" name="is_intest" class="is_intest" {if $user_info['is_intest'] } checked="checked"{/if} value="1" /><span>是</span></li>
									<li><input type="radio" name="is_intest" class="is_intest" {if $user_info['is_intest'] == 0 } checked="checked"{/if} value="0" /><span>否</span></li>
								</ul>
								<span class="error" id="title_tips" style="display:none;"></span>
							</div>
                            <div id="rename_android_package" class="form_ul_div clear">
                                <span class="title">自定义安卓包名: </span>
                                <ul class="type-choose clear">
                                    <li><input type="radio" name="rename_android_package" class="rename_android_package" {if $user_info['rename_android_package'] } checked="checked"{/if} value="1" /><span>是</span></li>
                                    <li><input type="radio" name="rename_android_package" class="rename_android_package" {if $user_info['rename_android_package'] == 0 } checked="checked"{/if} value="0" /><span>否</span>
                                        &nbsp;&nbsp;&nbsp;<span style="color: red;">(注：开通#自定义安卓包名#权限是一次性的)</span></li>
                                </ul>
                                <span class="error" id="title_tips" style="display:none;"></span>
                            </div>
                            
                            <div id="list_ui_num" class="form_ul_div clear">
								<span class="title">list_ui扩展字段数: </span>
								<ul class="type-choose clear" style="padding-left:85px;">
										<input type="radio" name="list_ui_num" value="5" {if $app_info['catalog_num']['max_list_ui_num']=='5'}checked="checked"{/if}/><span>5个扩展字段</span>
										<input type="radio" name="list_ui_num" value="7" {if $app_info['catalog_num']['max_list_ui_num']=='7'}checked="checked"{/if}/><span>7个扩展字段</span>
										<input type="radio" name="list_ui_num" value="9" {if $app_info['catalog_num']['max_list_ui_num']=='9'}checked="checked"{/if}/><span>9个扩展字段</span>
										<input type="radio" name="list_ui_num" value="11" {if $app_info['catalog_num']['max_list_ui_num']=='11'}checked="checked"{/if}/><span>11个扩展字段</span>
										<input type="radio" name="list_ui_num" value="13" {if $app_info['catalog_num']['max_list_ui_num']=='13'}checked="checked"{/if}/><span>13个扩展字段</span>
								</ul>
								<span class="error" id="title_tips" style="display:none;"></span>
							</div>	
                            
                            <div id="radio_num" class="form_ul_div clear">
								<span class="title">最多单选个数: </span>
								<ul class="type-choose clear" style="padding-left:85px;">
										<input type="radio" name="radio_num" value="1" {if $app_info['catalog_num']['max_radio_num']=='1'}checked="checked"{/if}/><span>1</span>
										<input type="radio" name="radio_num" value="2" {if $app_info['catalog_num']['max_radio_num']=='2'}checked="checked"{/if}/><span>2</span>
										<input type="radio" name="radio_num" value="3" {if $app_info['catalog_num']['max_radio_num']=='3'}checked="checked"{/if}/><span>3</span>
										<input type="radio" name="radio_num" value="4" {if $app_info['catalog_num']['max_radio_num']=='4'}checked="checked"{/if}/><span>4</span>
										<input type="radio" name="radio_num" value="5" {if $app_info['catalog_num']['max_radio_num']=='5'}checked="checked"{/if}/><span>5</span>
								</ul>
								<span class="error" id="title_tips" style="display:none;"></span>
							</div>
							
							<div id="price_num" class="form_ul_div clear">
								<span class="title">最多价格个数: </span>
								<ul class="type-choose clear" style="padding-left:85px;">
										<input type="radio" name="price_num" value="1" {if $app_info['catalog_num']['max_price_num']=='1'}checked="checked"{/if}/><span>1</span>
										<input type="radio" name="price_num" value="2" {if $app_info['catalog_num']['max_price_num']=='2'}checked="checked"{/if}/><span>2</span>
										<input type="radio" name="price_num" value="3" {if $app_info['catalog_num']['max_price_num']=='3'}checked="checked"{/if}/><span>3</span>
										<input type="radio" name="price_num" value="4" {if $app_info['catalog_num']['max_price_num']=='4'}checked="checked"{/if}/><span>4</span>
										<input type="radio" name="price_num" value="5" {if $app_info['catalog_num']['max_price_num']=='5'}checked="checked"{/if}/><span>5</span>
								</ul>
								<span class="error" id="title_tips" style="display:none;"></span>
							</div>
							
							<div id="time_num" class="form_ul_div clear">
								<span class="title">最多时间个数: </span>
								<ul class="type-choose clear" style="padding-left:85px;">
										<input type="radio" name="time_num" value="2" {if $app_info['catalog_num']['max_time_num']=='2'}checked="checked"{/if}/><span>2</span>
										<input type="radio" name="time_num" value="3" {if $app_info['catalog_num']['max_time_num']=='3'}checked="checked"{/if}/><span>3</span>
										<input type="radio" name="time_num" value="4" {if $app_info['catalog_num']['max_time_num']=='4'}checked="checked"{/if}/><span>4</span>
										<input type="radio" name="time_num" value="5" {if $app_info['catalog_num']['max_time_num']=='5'}checked="checked"{/if}/><span>5</span>
								</ul>
								<span class="error" id="title_tips" style="display:none;"></span>
							</div>
                            
                            
                            <div id="content_ui_num" class="form_ul_div clear">
								<span class="title">content_ui扩展字段: </span>
								<ul class="type-choose clear" style="padding-left:85px;">
										<input type="radio" name="content_ui_num" value="6" {if $app_info['catalog_num']['max_content_ui_num']=='6'}checked="checked"{/if}/><span>6</span>
										<input type="radio" name="content_ui_num" value="8" {if $app_info['catalog_num']['max_content_ui_num']=='8'}checked="checked"{/if}/><span>8</span>
										<input type="radio" name="content_ui_num" value="10" {if $app_info['catalog_num']['max_content_ui_num']=='10'}checked="checked"{/if}/><span>10</span>
										<input type="radio" name="content_ui_num" value="12" {if $app_info['catalog_num']['max_content_ui_num']=='12'}checked="checked"{/if}/><span>12</span>
										<input type="radio" name="content_ui_num" value="14" {if $app_info['catalog_num']['max_content_ui_num']=='14'}checked="checked"{/if}/><span>14</span>
								</ul>
								<span class="error" id="title_tips" style="display:none;"></span>
							</div>
                            
                             <div id="main_num" class="form_ul_div clear">
								<span class="title">主按钮个数: </span>
								<ul class="type-choose clear" style="padding-left:85px;">
										<input type="radio" name="main_num" value="1" {if $app_info['catalog_num']['max_main_num']=='1'}checked="checked"{/if}/><span>1</span>
										<input type="radio" name="main_num" value="2" {if $app_info['catalog_num']['max_main_num']=='2'}checked="checked"{/if}/><span>2</span>
										<input type="radio" name="main_num" value="3" {if $app_info['catalog_num']['max_main_num']=='3'}checked="checked"{/if}/><span>3</span>
										<input type="radio" name="main_num" value="4" {if $app_info['catalog_num']['max_main_num']=='4'}checked="checked"{/if}/><span>4</span>
										<input type="radio" name="main_num" value="5" {if $app_info['catalog_num']['max_main_num']=='5'}checked="checked"{/if}/><span>5</span>
								</ul>
								<span class="error" id="title_tips" style="display:none;"></span>
							</div>
							
							 <div id="minor_num" class="form_ul_div clear">
								<span class="title">辅助按钮个数: </span>
								<ul class="type-choose clear" style="padding-left:85px;">
										<input type="radio" name="minor_num" value="1" {if $app_info['catalog_num']['max_minor_num']=='1'}checked="checked"{/if}/><span>1</span>
										<input type="radio" name="minor_num" value="2" {if $app_info['catalog_num']['max_minor_num']=='2'}checked="checked"{/if}/><span>2</span>
										<input type="radio" name="minor_num" value="3" {if $app_info['catalog_num']['max_minor_num']=='3'}checked="checked"{/if}/><span>3</span>
										<input type="radio" name="minor_num" value="4" {if $app_info['catalog_num']['max_minor_num']=='4'}checked="checked"{/if}/><span>4</span>
										<input type="radio" name="minor_num" value="5" {if $app_info['catalog_num']['max_minor_num']=='5'}checked="checked"{/if}/><span>5</span>
								</ul>
								<span class="error" id="title_tips" style="display:none;"></span>
							</div>
						                          
                        </li>
						
						</ul>
						
				<input type="hidden" name="a" value="update_base_info" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="更新基本信息" class="button_6_14"/>
				<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}