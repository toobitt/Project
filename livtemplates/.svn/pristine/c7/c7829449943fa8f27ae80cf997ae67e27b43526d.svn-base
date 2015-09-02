{template:head}
{css:ad_style}
{css:column_node}
{code}
	//hg_pre($formdata);
{/code}
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
				<h2>{$optext}帐户信息</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">用户名称：</span>
								<input type="text" value="{$formdata['data']['nickname']}" name="nickname" id="nickname">
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
							$form['sort'][0]='请选择分类';
							$formdata['data']['con_sort'] = $formdata['data']['con_sort'] ? $formdata['data']['con_sort'] : 0;
						{/code}
						
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">报料分类：</span>
								{template:form/search_source,con_sort,$formdata['data']['con_sort'],$formdata['sort'],$sort_css}			
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span><font color='red'>*</font>为必填选项</span>
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
