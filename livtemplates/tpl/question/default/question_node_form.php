
{template:head}
{css:ad_style}
{css:vote_style}
{js:mms_default}
{js:input_file}
{js:message}
{js:vote}

{css:column_node}
{js:column_node}
<script type="text/javascript">

</script>
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
		<div class="ad_middle">
		<form name="editform" action="./run.php?mid={$_INPUT['mid']}&a={$action}" method="post" enctype='multipart/form-data' class="ad_form h_l">
			<h2>{$optext}问卷分类</h2>
			<ul class="form_ul">
				<li class="i">
					<div class="form_ul_div">
						<span class="title">名称：</span>
						<input type="text" onblur="input_content_color(2);" onfocus="input_content_color(2);" id="required_2" name="name" value="{$name}" style="width:440px"/>
						<font class="important" id="important_2">必填</font>
					</div>
					<div class="form_ul_div">	
						<span class="title">描述：</span>
						{template:form/textarea,describes,$brief}
					</div>
					<div class="form_ul_div clear">
					<span class="title">父级分类：</span>
					{code}
						$hg_attr['node_en'] = 'question_node';
					{/code}
					{template:unit/class,father_node_id,$formdata['fid'], $node_data}
					</div>
				</li>
				</li>
			</ul>
		</br>
		<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14"/>
		<input type="hidden" name="a" value="{$action}" id="action" />
		<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
		</form>
		</div>
		<div class="right_version">
			<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
		</div>
{template:foot}