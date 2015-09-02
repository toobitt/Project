{template:head}
{code}
$list = $formdata['cell'];
$cell_mode_param = $formdata['cell_mode_param'];
$data_source_param = $formdata['data_source_param'];
$block = $formdata['block'];
$cell_mode = $formdata['cell_mode'];
$data_source = $formdata['data_source'];
{/code}
{css:ad_style}
{css:column_node}
<script>
	var gPageid = {$list['page_id']};
</script>
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
				<h2>编辑单元信息</h2>
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
								'onclick' => 'hg_cell_settings();'
							);								
							$data_source_attr = array(
								'class' => 'transcoding down_list',
								'show' => 'data_source_show',
								'width' => 300,	
								'state' => 0,
								'onclick' => 'hg_cell_settings();',
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
								'onclick' => 'hg_cell_settings(true);',
							);
							//区块
							$blocklist[0] = '选择区块';
							foreach($block as $k => $v)
							{
								$blocklist[$v['id']] = $v['name'];
							}																					
							//样式
							$cellmode[0] = '选择样式';
							foreach($cell_mode as $k => $v)
							{
								$cellmode[$v['id']] = $v['title'];
							}
							//数据源
							$datasource[0] = '选择数据源';
							foreach($data_source as $k => $v)
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
						</li>
						<li class="i" id="using_block" {if !$list['using_block']}style="display:none;"{/if}>
							<div class="form_ul_div clear">
								<span class="title">选择区块:</span>
								{template:form/search_source,block_id,$list['block_id'],$blocklist,$block_attr}
								<a style="margin-left:10px;line-height:22px;" href="./run.php?mid={$_INPUT['mid']}&a=create_block_form&site_id={$_INPUT['site_id']}&page_id={$_INPUT['page_id']}" onclick="return hg_ajax_post(this,'新建区块',0)">新增区块</a>
							</div>
						</li>
						<li class="i">
								<div class="form_ul_div clear">
										<span class="title">样式：</span>
										{template:form/search_source,cell_mode,$list['cell_mode'],$cellmode,$cell_mode_attr}
								</div>
						</li>						
						<div id="not_using_block" {if $list['using_block']}style="display:none;"{/if}>	
							<li class="i">
								<div class="form_ul_div clear">
										<span class="title">数据源：</span>
										{template:form/search_source,data_source,$list['data_source'],$datasource,$data_source_attr}
								</div>
							</li>
						</div>
						<li class="i">
							<div class="form_ul_div clear">
									<span class="title">单元类型：</span>
									{template:form/search_source,cell_type,$list['cell_type'],$_configs['cell_type'],$cell_type_attr}
							</div>
						</li>	
						<li class="i">
							<div class="form_ul_div clear">
									<span class="title">单元代码：</span>
									<textarea name="cell_code">{$list['cell_code']}</textarea>
							</div>
						</li>	
						<div id="param_settings" {if $list['using_block']}style="display:none;"{/if}>
							{if $list['cell_mode'] && $list['data_source'] && $list['param_asso']}
								<fieldset>
								    <legend>数据源输入参数</legend>
								    {if is_array($data_source_param['input_param']) && count($data_source_param['input_param'])>0}
								    	{foreach $data_source_param['input_param'] as $k => $v}
								    		{$v['name']}:
								    		{if $v['type'] == 'text'}
								    			<input type="text" name="input_param[{$v['sign']}]" value="{$v['default_value']}" /><br/>
								    		{else}
								    			<option value="">
												请选择
												</option>								    		
												<select name="input_param[{$v['sign']}]">
							    				{foreach $v['other_value'] as $kk => $vv}	
							    					<option value="{$kk}" {if $kk == $v['default_value']}selected="selected"{/if}>{$vv}</option><br/>
							    				{/foreach}
							    				</select><br/>			    		
								    		{/if}
								    	{/foreach}
								    {else}
								    	暂无参数
								    {/if}
								</fieldset> 
								<fieldset>
							    <legend>样式参数</legend>
							    {if is_array($cell_mode_param['mode_param']) && count($cell_mode_param['mode_param'])>0}
							    	{foreach $cell_mode_param['mode_param'] as $k => $v}
							    		{$v['name']}:
							    		{if $v['type'] == 'text'}
							    			<input type="text" name="mode_param[{$v['id']}]" value="{$v['default_value']}" /><br/>
							    		{else}
							    			<select name="mode_param[{$v['id']}]">
								    			<option value="">
												请选择
												</option>
							    				{foreach $v['other_value'] as $kk => $vv}	
							    					<option value="{$kk}" {if $kk == $v['default_value']}selected="selected"{/if}>{$vv}</option><br/>
							    				{/foreach}
							    			</select><br/>	
							    		{/if}	
							    	{/foreach}
							    {else}
							    	暂无参数
							    {/if}
								</fieldset>	
								<fieldset>
							    <legend>参数关联</legend>
							    {if is_array($cell_mode_param['data_param']) && count($cell_mode_param['data_param'])>0}
							    	{foreach $cell_mode_param['data_param'] as $k => $v}
							    		{$v['name']}:
										<select name="assoc_param[{$v['id']}]">
										<option value="">
										请选择
										</option>
										{foreach $data_source_param['out_param'] as $kk=>$vv}
											<option value="{$vv['parents']}" {if $v['assoc_data_variable'] == $vv['parents']}selected=selected{/if}>
												--{$vv['name']}--
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
				<input type="hidden" name="a" id= "aid" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<input type="hidden" name="flag" value="flag" />
				<input type="hidden" name="title" value="{$list['cell_name']}" />
				<input type="hidden" name="sort_id" value="{$list['sort_id']}" />
				<input type="hidden" name="template_style" value="{$list['template_style']}" /> 
				<input type="hidden" name="template_id"  value="{$list['template_id']}" />
				<input type="hidden" name="template_sign" value="{$list['template_sign']}" />
				<br />
				<input type="submit" name="sub" value="{$optext}" onclick="isvalidatefile('file_data');" class="button_6_14"/>
				<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
	<div id="add_block" style="box-shadow:0 0 3px #555;padding:0 12px 12px 12px;background:#f0f0f0;display:none;position:fixed;top:50px;left:50px;z-index:100000;border:1px solid #f5f5f5;border-radius:5px;width:500px;max-height:500px;overflow:auto;"></div>
<script>
function hg_select_page(obj)
{
	if(!obj)
	{
		if(gPageid != 0)
		{
			var url = './run.php?mid='+gMid+'&a=get_page_data_form&page_id='+gPageid;
			hg_request_to(url);
		}
	}
	else
	{
		var page_id = $(obj).attr('attrid');
		if(page_id==0)
		{
			hg_select_page_back();
			return;
		}
		var fid = $(obj).attr('fid');
		var url = './run.php?mid='+gMid+'&a=get_page_data_form&page_id='+page_id;
		if(fid)
		{
			url += '&fid='+fid;
		}
		hg_request_to(url);		
	}
}
function hg_select_page_back(obj)
{
	if(!obj)
	{
		$('#page_data_li').hide();
		$('#page_data_content').html();
	}
	else
	{
		obj = $.parseJSON(obj);
		var page_info = obj.page_info;
		var page_data = obj.page_data;
		var html = '<ul style="float:left;">';
		$.each(page_data,function(i,n){
			if(n[page_info.last_field] == 1)
			{
				html += '<li><input type="radio" name="page_data_id" value="'+n[page_info.field]+'"/>'+ n[page_info.name_field]+'</li>';
			}
			else
			{
				html += '<li><input type="radio" name="page_data_id" value="'+n[page_info.field]+'"/>' +n[page_info.name_field] +'<span style="pointer:cursor;" attrid="'+page_info.id+'" fid="'+n[page_info.field]+'" onclick="hg_select_page(this);"> > </span></li>';
			}
		});
		html += '</ul>';
		$('#page_data_li').show();
		$('#page_data_content').html(html);
		hg_resize_nodeFrame();		
	}
}
$(function ($) {
	hg_select_page();
});
function hg_cell_settings(flag)
{
	if(flag)
	{
		var block_id = $('#block_id').val();
		var url = './run.php?mid='+gMid+'&infrm=1&a=get_block_info&block_id='+block_id;
		$.get(url,function(data){
			var obj = $.parseJSON(data);
			obj = obj[0];
			$('#data_source').val(obj.datasource_id);
		});
	}
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
	$('#param_settings').show();
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
		$('#not_using_block').show('fast');
		$('#param_settings').show('fast');
		hg_resize_nodeFrame();
	}
}
function hg_block_form(html)
{
	$('#add_block').html(html);
	$('#add_block').fadeIn('fast');
	hg_resize_nodeFrame();
}
function hg_add_block_back(data)
{
	var array = $.parseJSON(data);	
	if(array.id)
	{
		$('#add_block').fadeOut();
	}
	var html = '<li style="cursor:pointer;"><a href="###" onclick="if(hg_select_value(this,0,\'block_show\',\'block_id\',1)){};" attrid="'+array.id+'" class="overflow">'+array.name+'</a></li>';
	$('#block_show').prepend(html);
	$('#block_show').children('li:first').children('a').click();
}
</script>


<!-- {css:column_node/column_node} -->
<!-- {js:jqueryfn/jquery.tmpl.min} -->
<!-- {js:publishsys/Tree1} -->
<!-- <div class="publish-box" id="publish-box-{$hg_name}"> -->
<!-- 	<div class="publish-result {if count($hg_print_selected) == 0}empty{/if}" > -->
<!-- 		<p class="publish-result-title">参数关联：</p> -->
<!-- 		<ul> -->
			
<!-- 		</ul> -->
<!-- 		<div class="publish-result-empty">显示参数关联情况</div> -->
<!-- 	</div> -->
	
<!-- 	<div class="publish-site"> -->
<!-- 		<div class="publish-site-current" _siteid="23"> -->
			
<!-- 		</div> -->
<!-- 		<span class="publish-site-qiehuan">切换</span> -->
<!-- 		<ul> -->
			 
<!-- 		</ul> -->
<!-- 	</div> -->
	 
<!-- 	<div class="publish-list"> -->
<!-- 		<div class="publish-inner-list"> -->
			
<!-- 		</div> -->
<!-- 	</div> -->
	
<!-- 	<input type="hidden" class="publish-hidden" name="column_id" value="" /> -->
<!-- 	<input type="hidden" class="publish-name-hidden" name="column_name" value="" /> -->
<!-- </div> -->
 <script>
$(function() {
	$('.publish-box').hg_publish({
		radioModel: true
	});
});
</script>
{template:foot}