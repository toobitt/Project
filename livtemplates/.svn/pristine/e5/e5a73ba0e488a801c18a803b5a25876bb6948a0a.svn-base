{template:head}
{code}

//$re = $formdata['sort_id']?$formdata['sort_name']:'请选择分类';

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

<style type="text/css">
.colorpicker-wrap{display:inline-block; }
</style>

<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear" style="padding-bottom: 30px;">
		<div class="ad_middle"  style="width:900px">
			<form name="editform" action="run.php?mid={$_INPUT['mid']}" method="post" class="ad_form h_l">
			<h2>{$optext}栏目</h2>
				<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">栏目名称:</span>
								<input type="text" value="{$formdata['title']}" name='title' style="width:200px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">标识:</span>
								<input type="text" value="{$formdata['sign']}" name='sign' style="width:200px;">
							</div>
						</li>
						<!-- 
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">显示类型:</span>
								{code}
									$status_css = array(
										'class' => 'transcoding down_list',
										'show' => 'sort_audit',
										'width' => 124,
										'state' => 0,
									);
									$type_default = $formdata['type'] ? $formdata['type'] : -1;
									$_configs['type'][-1] = '所有分类';
								{/code}
								{template:form/search_source,type,$type_default,$_configs['type'],$status_css}
							</div>
						</li>
						 -->
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">描述备注:</span>
								<textarea rows="3" cols="80" name='brief'>{$formdata['brief']}</textarea>
							</div>
						</li>
					</ul>
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="site_id" value="{$_INPUT['site_id']}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br/>
					<div class="temp-edit-buttons">
					   <input type="submit" name="sub" value="{$optext}栏目" class="edit-button submit"/>
					   <input type="button" value="取消" class="edit-button cancel" onclick="javascript:history.go(-1);"/>
				    </div>
			</form>
		</div>
	</div>
{template:foot}