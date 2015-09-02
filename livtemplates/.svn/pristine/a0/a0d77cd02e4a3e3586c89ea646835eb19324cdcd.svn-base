{code}
	$data_source = $formdata['data_source'];
	$column = $formdata['column'];
{/code}
<form action="run.php?mid={$_INPUT['mid']}&a=create_block" method="post" enctype="multipart/form-data" class="ad_form h_l" onsubmit="return hg_ajax_submit('block_form')" id="block_form" name="block_form">
<h2>新增区块</h2>
<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">区块名称：</span>
			<input type="text" value="" name='name'>
			<span class="site_fill_tip">
			</span>
		</div>
	</li>
	
	<li class="i">
							<div class="form_ul_div">
								<span  class="title">自动更新：</span>
								<input type=checkbox name="update_type" value="1" />
								<span class="site_fill_tip">
								</span>
							</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">更新频率：</span>
			<input type="text" value="" name='update_time' style="width:60px;">
			<span class="site_fill_tip">
			</span>
		</div>
	</li>
	<li class="i">
							<div class="form_ul_div">
								<span  class="title">支持推送：</span>
								<input type=checkbox name="is_support_push" value="1" />
								<span class="site_fill_tip">
								</span>
							</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">数据源：</span>
			<select id="datasource_id" name="datasource_id" onchange="datascource_change()">
			<option value="0" >
			请选择
			</option>
			{foreach $data_source as $kk=>$vv}
				<option value="{$vv['id']}">
				{$vv['name']}
				</option>
			{/foreach}
			</select>
			<div id="datasource_arg" >
			</div>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">宽：</span>
			<input type="text" value="" name='width' style="width:60px;">
			<span class="site_fill_tip">
			</span>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">高：</span>
			<input type="text" value="" name='height' style="width:60px;">
			<span class="site_fill_tip">
			</span>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">行数：</span>
			<input type="text" value="" name='line_num' style="width:60px;">
			<span class="site_fill_tip">
			</span>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">父标签：</span>
			<input type="text" value="" name='father_tag' style="width:100px;">
			<span class="site_fill_tip">
			</span>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title" style="width:80px;">默认循环体：</span>
			<input type="text" value="" name='loop_body' style="width:300px;">
			<span class="site_fill_tip">
			</span>
		</div>
	</li>
</ul>
<input type="hidden" name="infrm" value="1" />
<input type="hidden" name="site_id" value="{$_INPUT['site_id']}" />
<input type="hidden" name="column_id" value="{$_INPUT['page_id']}" />
<br />
<input style="margin-left:77px;" type="submit" name="sub" value="确定" class="button_2"/>
<input type="button" name="sub" onclick="$('#add_block').fadeOut()" value="返回" class="button_2"/>
</form>
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