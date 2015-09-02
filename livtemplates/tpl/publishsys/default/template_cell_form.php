{template:head}
{code}
$list = $formdata['cell'];
$cell_mode_param = $formdata['cell_mode_param'];
$data_source_param = $formdata['data_source_param'];
$block = $formdata['block'];
{/code}
{css:ad_style}
{css:column_node}
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
				<h2>预设{$list['cell_name']}单元</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">单元名称：</span> 
								{$list['cell_name']}
							</div>
						</li>
						{code}
							$cell_mode_attr = array(
								'class' => 'transcoding down_list cell_page_type',
								'show' => 'cell_mode_show',
								'width' => 300,	
								'state' => 0,
								'onclick' => 'hg_cell_settings(this,cell_mode);'
							);								
							$data_source_attr = array(
								'class' => 'transcoding down_list',
								'show' => 'data_source_show',
								'width' => 300,	
								'state' => 0,
								'onclick' => 'hg_cell_settings(this,data_source);',
							);	
							$cell_type_attr = array(
								'class' => 'transcoding down_list',
								'show' => 'site_show',
								'width' => 300,	
								'state' => 0,
							);			
							$block_attr = array(
								'class' => 'transcoding down_list',
								'show' => 'block_show',
								'width' => 300,	
								'state' => 0,
							);
							//区块
							$blocklist[0] = '选择区块';
							foreach($block as $k => $v)
							{
								$blocklist[$v['id']] = $v['name'];
							}																						
							//样式
							$cellmode[0] = '选择样式';
							foreach($cell_mode[0] as $k => $v)
							{
								$cellmode[$v['id']] = $v['title'];
							}
							//数据源
							$datasource[0] = '选择数据源';
							foreach($data_source[0] as $k => $v)
							{
								$datasource[$v['id']] = $v['name'];
							}
							
						{/code}												
						<li class="i">
							<div class="form_ul_div clear">
									<span class="title">启用区块：</span>
									<label><input type="radio" name="using_block" value="1" {if $list['using_block']}checked="checked"{/if} onclick="hg_using_block(1)"/><span>是</span></label>
									<label><input type="radio" name="using_block" value="0" {if !$list['using_block']}checked="checked"{/if}onclick="hg_using_block(0)"/><span>否</span></label>
							</div>
							<input type="hidden" name="selected_cell_mode" value="" />
						</li>
						<li class="i" id="using_block" {if !$list['using_block']} style="display:none;"{/if}>
							<div class="form_ul_div clear">
								<span class="title">选择区块:</span>
								{template:form/search_source,block_id,$list['block_id'],$blocklist,$block_attr}
							</div>
						</li>
						<div id="not_using_block" {if $list['using_block']} style="display:none;"{/if}>
							<li class="i">
								<div class="form_ul_div clear">
										<span class="title">样式：</span>
										{template:form/search_source,cell_mode,$list['cell_mode'],$cellmode,$cell_mode_attr}
								</div>
								<input type="hidden" name="selected_cell_mode" value="" />
							</li>	
							<li class="i">
								<div class="form_ul_div clear">
										<span class="title">数据源：</span>
										{template:form/search_source,data_source,$list['data_source'],$datasource,$data_source_attr}
								</div>
								<input type="hidden" name="selected_data_source" value="" />
							</li>
						</div>			
						<!--<li class="i">
							<div class="form_ul_div clear">
									<span class="title">单元代码：</span>
									<textarea name="cell_code">{$list['cell_code']}</textarea>
							</div>
						</li>	-->
						<div id="param_settings" {if $list['using_block']} style="display:none;"{/if}>
							{if $list['cell_mode'] && $list['data_source'] && $list['param']}
								<fieldset>
								    <legend>数据源输入参数</legend>
								    {if is_array($list['param']['input_param']) && count($list['param']['input_param'])>0}
								    	{foreach $list['param']['input_param'] as $k => $v}
								    		{$data_source_param['input_param'][$k]['name']}:
								    		<input type="text" name="input_param[{$k}]" value="{$v}" /><br/>
								    	{/foreach}
								    {else}
								    	暂无参数
								    {/if}
								</fieldset> 
								<fieldset>
							    <legend>样式参数</legend>
							    {if is_array($list['param']['mode_param']) && count($list['param']['mode_param'])>0}
							    	{foreach $list['param']['mode_param'] as $k => $v}
							    		{$cell_mode_param['mode_param'][$k]['name']}:
							    		<input type="text" name="mode_param[{$k}]" value="{$v}" /><br/>
							    	{/foreach}
							    {else}
							    	暂无参数
							    {/if}
								</fieldset>	
								<fieldset>
							    <legend>参数关联</legend>
							    {if is_array($list['param']['assoc_param']) && count($list['param']['assoc_param'])>0}
							    	{foreach $list['param']['assoc_param'] as $k => $v}
							    		{$k}:
										<select name="assoc_param[{$k}]">
										<option value="">
										请选择
										</option>
										{foreach $data_source_param['out_param'] as $kk=>$vv}
										<option value="{$vv['sign']}" {if $vv['sign'] == $k || $vv['sign'] == $v}selected=selected{/if}>
											--{$vv['sign']}--
										</option>
										{/foreach}
										</select>    		
							    		<br/>
							    	{/foreach}
							    {else}
							    	暂无参数
							    {/if}
								</fieldset>								 							
							{/if}
						</div>												
					</ul>
				<input type="hidden" name="a" id= "aid" value="preset_update" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="template_id"  value="{$list['template_id']}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="确定" class="button_6_14"/>
				<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
<script>
function hg_cell_settings(obj,name)
{
	var cell_mode_id = $('#cell_mode').val();
	var data_source_id = $('#data_source').val();
	if(cell_mode_id !=0 && data_source_id != 0)
	{
		var url = './run.php?mid='+gMid+'&infrm=1&a=get_cell_data_param&cell_mode_id='+cell_mode_id +'&data_source_id='+data_source_id;
		hg_ajax_post(url);
	}
	else
	{
		hg_cell_settings_back('');
	}
}
function hg_cell_settings_back(html)
{
	$('#param_settings').html(html);
	hg_resize_nodeFrame();
}
function hg_using_block(flag)
{
	if(flag)
	{
		$('#using_block').show();
		$('#not_using_block').hide();
		$('#param_settings').hide();
		hg_resize_nodeFrame();
	}
	else
	{
		$('#using_block').hide();
		$('#not_using_block').show();
		$('#param_settings').show();
		hg_resize_nodeFrame();
	}
}
</script>
{template:foot}