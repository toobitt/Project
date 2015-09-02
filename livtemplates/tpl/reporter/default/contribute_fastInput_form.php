{template:head}
{css:ad_style}
<style type="text/css">
.source_item {cursor:pointer; border:1px solid #CCC; display:inline-block; padding:3px 5px; margin:5px;}
</style>
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
				<h2>{$optext}快捷输入</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">内容：</span>
								<input type="text" value="{$formdata['content']}" name='content' style="width:440px;">
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						{code}
							$sort_css = array(
								'class' => 'transcoding down_list',
								'show' => 'object_type_show',
								'width' => 120,	
								'state' => 0,
							);
							$sort[0][0] = '请选择分类';
							$formdata['sort_id'] = $formdata['sort_id'] ? $formdata['sort_id'] : 0;
						{/code}
						<li class="i">
							<div class="form_ul_div clear" >
								<span class="title">分类：</span>
								{template:form/search_source,sort_item,$formdata['sort_id'],$sort[0],$sort_css}	
								<font class="important" style="color:red">*</font>		
							</div>
							
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title"><font color='red'>*</font>为必填选项</span>
							</div>
						</li>
					</ul>
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}