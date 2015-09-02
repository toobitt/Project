{template:head}
{code}
	if($id)
	{
		$optext="更新";
		$ac="update";
	}
	else
	{
		$optext="添加";
		$ac="create";
	}
{/code}
{if is_array($formdata)}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;	
		{/code}
	{/foreach}
{/if}
{css:ad_style}
{js:ad}
{css:column_node}
{js:column_node}
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data" method="post"   id="content_form">
<h2>{$optext}</h2>
<ul class="form_ul">
	{code}
		$item_source = array(
			'class' => 'down_list',
			'show' => 'item_show',
			'width' => 140,/*列表宽度*/		
			'state' => 0, /*0--正常数据选择列表，1--日期选择*/
			'is_sub'=>1,
		);
		$data_node = $data_node[0];
		$default = $data_id ? $data_id : -1;
		$node_data[-1] = '选择数据源';
		foreach($data_node as $k =>$v)
		{
			$node_data[$v['id']] = $v['name'];
		}
	{/code}
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">数据源：</span>
			{template:form/search_source,data_id,$default,$node_data,$item_source}
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">标题：</span><input type="text" name="title" value="{$title}"  style="width:260px;"/>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">目录：</span><input type="text" name="filepath" value="{$filepath}"  style="width:260px;"/>
		</div>
	</li>	
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">文件名：</span><input type="text" name="filename" value="{$filename}"  style="width:260px;"/>
		</div>
	</li>	
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">头部代码：</span><textarea name="headcode">{$headcode}</textarea>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">循环代码：</span><textarea name="mediacode">{$mediacode}</textarea>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">尾部代码：</span><textarea name="tailcode">{$tailcode}</textarea>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">输出类型：</span>
			{code}
				$item_source = array(
					'class' => 'down_list',
					'show' => 'type_show',
					'width' => 100,/*列表宽度*/		
					'state' => 0, /*0--正常数据选择列表，1--日期选择*/
					'is_sub'=>1,
				);
				$default = $type ? $type : 1; 
			{/code}			
			{template:form/search_source,type,$default,$_configs['dynpro_type'],$item_source}
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">JS回调函数：</span><input type="text" name="callbackfunc" value="{$callbackfunc}"  style="width:260px;"/>
		</div>
	</li>		
</ul>
<input type="hidden" name="a" value="{$ac}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
{code}
	$siteid = $_INPUT['site_id'] ? $_INPUT['site_id'] : $site_id;
{/code}
<input type="hidden" name="site_id" value="{$siteid}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
	<h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>

{template:foot}