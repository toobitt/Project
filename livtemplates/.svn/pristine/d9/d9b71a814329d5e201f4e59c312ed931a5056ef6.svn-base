{template:head}
{if is_array($formdata)}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;	
		{/code}
	{/foreach}
{/if}
{code}
	if($id)
	{
		$optext="更新";
		$a="update";
	}
	else
	{
		$optext="添加";
		$a="create";
	}
{/code}
{css:ad_style}
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
	<form class="ad_form h_l" action="" method="post" enctype="multipart/form-data"  id="content_form">
		<h2>{$optext}评论</h2>
		<ul class="form_ul">
			
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">用户ID：</span><input type="text" value='{$member_id}' name='member_id' size="20">
				</div>
			</li>
			
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">用户名：</span><input type="text" value='{$username}' name='username' size="20">
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">标题：</span><input type="text" value='{$title}' name='title' class="title">
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">内容：</span><textarea name="content">{$content}</textarea>
					<font class="important" style='color:red'>*</font>
				</div>
			</li>
			 {if !$id}
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">对象类型：</span>
					{code}
						$item_source = array(
							'class' => 'down_list i',
							'show' => 'item_shows_',
							'width' => 104,/*列表宽度*/		
							'state' => 0, /*0--正常数据选择列表，1--日期选择*/
							'is_sub'=>1,
							'onclick'=>'',
						);
						$default = $groupid ? $groupid : 0;
						$gname = $_configs['comment_type'];
					{/code}
					{template:form/search_source,com_type,$default,$gname,$item_source}
					<span class="title">对象id：</span><input type="text" name='cmid' size="20">
					<font class="important" style='color:red'>*</font>
				</div>
				
			</li>
			
			{else}
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">内容标题：</span><input type="text" value='{$content_title}' readonly="readonly" class="title">
					<font class="important">被评论内容标题</font>
				</div>
			</li>
			{/if}
		</ul>
		<input type="hidden" name="a" value="{$a}" />
		<input type="hidden" name="tablename" value="{$tablename}" />
		<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
		<br />
		<input type="submit" id="submit_ok" name="sub" value="{$optext}评论" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
	</form>
</div>
<div class="right_version">
	<h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>
{template:foot}