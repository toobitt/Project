{template:head}
{css:ad_style}
<script type="text/javascript">
	
</script>
{code}
if(!empty($formdata))
{
	$formdata = $formdata;
}
$site_id = intval($formdata['site_id']);
$page_id = intval($formdata['page_id']);
$page_data_id = intval($formdata['page_data_id']);
//	print_r($formdata['tem_data']);
//print_r($formdata);
{/code}

<div id="channel_form" style="margin-left:60%;position:relative;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<div id="blower_box" style="background:#ffffff;width:335px;height:300px;border:1px solid #A8A8A8;position:absolute;top:-312px;left:520px;">
			</div>
			{if $formdata['site_id']}
			<form action="" method="post"  class="ad_form h_l" name="deploy_form" id="deploy_form">
				<h2>生成发布:{$formdata['deploy_name']}</h2>
				
				<div id="basic_info" name="basic_info" >
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">需要生成的内容类型：</span>
								{foreach $formdata['set_type'] as $k=>$v}
								<input type="checkbox"  name="content_type[]" value={$k}>{$v} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								{/foreach}
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
					
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">包含子级：</span>
								<input type="checkbox"  name="is_contain_child" value='1'>
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">生成时间：</span>
								<input type="text" name="publish_time" id="publish_time" size="22" onFocus="return showCalendar('publish_time', 'y-mm-dd');" autocomplete="off" />
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">正文生成条数：</span>
								<input type="text" name="content_mk_num" id="content_mk_num" size="10"  />
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						{if $formdata['argument']['argument_name']}
						{foreach $formdata['argument']['argument_name'] as $k=>$v}
						{code}
						if(!$formdata['argument']['add_status'][$k])
						{
							continue;
						}
						if($formdata['argument']['type'][$k] == 'select')
						{
						$formdata['argument']['other_value'][$k] = explode(' ',$formdata['argument']['other_value'][$k]);
						if(is_array($formdata['argument']['other_value'][$k]))
						{
							$other_value = array();
							foreach($formdata['argument']['other_value'][$k] as $kk => $vv)
							{
								$tmp = explode('=>',$vv);
								$other_value[$tmp[0]] = $tmp[1];
							}
							$formdata['argument']['other_value'][$k] = $other_value;
						}
						}
						{/code}
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">{$v}：</span>
								{if $formdata['argument']['type'][$k] == 'text'}
								<input type="text" name="mkcontent_{$formdata['argument']['ident'][$k]}" id="mkcontent_{$formdata['argument']['ident'][$k]}" size="10" value="" />
								{else}
								<select name="mkcontent_{$formdata['argument']['ident'][$k]}">
								{foreach $formdata['argument']['other_value'][$k] as $kkk=>$vvv}
								<option value="{$kkk}" {if $formdata['argument']['value'][$k]===$vvv}selected{/if}>{$vvv}</option>
								{/foreach}
								</select>
								{/if}
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						{/foreach}
						{/if}
					</ul>
					</div>
				<input type="hidden" name="a" value="create" />
				<input type="hidden" name="site_id" value="{$formdata['site_id']}" />
				<input type="hidden" name="page_id" value="{$formdata['page_id']}" />
				<input type="hidden" name="page_data_id" value="{$formdata['page_data_id']}" />
				<input type="hidden" name="title" value="{$formdata['deploy_name']}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
				<br />
				<input type="button" onclick="hg_ajax_submit('deploy_form')" name="sub" value="添加" class="button_6_14"/>
			</form>
			{else}
			<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">nothing！</p>
						<script>hg_error_html(columnlist,1);</script>
			{/if}
		</div>
	</div>
