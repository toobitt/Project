{template:head}
{css:ad_style}
{css:column_node}
{js:share}
{code}
	$block_form[0] = $formdata;
{/code}
<script type="text/javascript">

	function datascource_change()
	{
		if($('#datasource_id').val()=='app')
		{
			block_data_form.datasource_id.options[0].selected = "true";
		}
		else
		{
			hg_get_browse($('#datasource_id').val());
		}
	}
	
	function hg_put_settings(html)
	{
		$('#datasource_arg').html(html);
	}
	
	function hg_get_browse(id)
	{
		var url = "run.php?mid="+gMid+"&a=get_datasource_info&id="+id;
		hg_ajax_post(url);
	}
	
</script>

<div id="channel_form" style="margin-left:60%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form name="block_data_form" action="" method="post"  class="ad_form h_l">
				<h2>{if $block_form[0]['id']}更新区块{else}新增区块{/if}</h2>
				<div id="basic_info" >
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">区块名称：</span>
								<input type="text" value="{$block_form[0]['name']}" name='name' style="width:300px;">
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">栏目：</span>
								<select name='column_id'>
								{foreach $block_form[0]['all_column'] as $k=>$v}
								<option value="{$v['id']}" {if $v['id']==$block_form[0]['column_id']}selected{/if}>{$v['name']}</option>
								{/foreach}
								</select>
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">更新频率：</span>
								<input type="text" value="{$block_form[0]['update_time']}" name='update_time' style="width:60px;">
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">更新方式：</span>
								{$_configs['update_type'][1]}：<input type=radio name="update_type" value=1 {if $block_form[0]['update_type']==1}checked{/if}>
								{$_configs['update_type'][2]}：<input type=radio name="update_type" value=2 {if $block_form[0]['update_type']==2}checked{/if}>
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">数据源：</span>
								<select id="datasource_id" name="datasource_id" onchange="datascource_change()">
								<option value="0" >
								请选择
								</option>
								
								{foreach $block_form[0]['app_data'] as $k=>$v}
								<option value="app">
									--{$v}--
									{foreach $block_form[0]['datasource_data'][$k] as $kk=>$vv}
										<option value="{$kk}" {if $block_form[0]['datasource_id']==$kk}selected{/if}>
										{$vv['name']}
										</option>
									{/foreach}
								</option>
								{/foreach}
								</select>
								<br>
								{code}
								{/code}
								<div id="datasource_arg" >
									{if $block_form[0]['datasource_info_data']}
										{foreach $block_form[0]['datasource_info_data']['argument']['ident'] as $k=>$v}
											{$block_form[0]['datasource_info_data']['argument']['argument_name'][$k]}<input type=text name="argument_{$v}" value="{$block_form[0]['datasource_argument'][$v]}" style="width:50px;height:15px;">
										{/foreach}
									{/if}
								</div>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">宽：</span>
								<input type="text" value="{$block_form[0]['width']}" name='width' style="width:60px;">
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">高：</span>
								<input type="text" value="{$block_form[0]['height']}" name='height' style="width:60px;">
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">行数：</span>
								<input type="text" value="{$block_form[0]['line_num']}" name='line_num' style="width:60px;">
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">父标签：</span>
								<input type="text" value="{$block_form[0]['father_tag']}" name='father_tag' style="width:100px;">
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">默认循环体：</span>
								<input type="text" value="{$block_form[0]['loop_body']}" name='loop_body' style="width:300px;">
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						
					</ul>
					</div>
				<input type="hidden" name="a" value="{if $block_form[0]['id']}update{else}create{/if}" />
				<input type="hidden" name="id" value="{$block_form[0]['id']}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="{if $block_form[0]['id']}更新{else}添加{/if}" class="button_6_14"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}