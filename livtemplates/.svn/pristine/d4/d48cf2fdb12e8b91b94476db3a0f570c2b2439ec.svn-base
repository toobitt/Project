{template:head}
{css:ad_style}
{css:vote_style}
{js:mms_default}
{js:input_file}
{js:message}
{js:vote}
{css:column_node}
{js:column_node}
{js:common/common_form}

{code}
$group_id = $formdata['sort_id'];
{/code}
<style>
.option_del_box{display:inline-block;width:16px;height:16px;cursor:pointer;float:right;position: relative;right: 270px;top: 4px;}
</style>
<script type="text/javascript">
	function hg_addContactDom()
	{
		var div = "<div class='form_ul_div clear'><span class='title'>联系方式: </span><input type='text' name='contract_name[]' style='width:90px;' class='title'>&nbsp;&nbsp;&nbsp;<input type='text' name='contract_value[]' size='40'/>&nbsp;&nbsp;<span class='option_del_box'><span name='option_del[]' class='option_del' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span></div>";
		$('#extend').append(div);
		hg_resize_nodeFrame();
	}
	function hg_optionTitleDel(obj)
	{
		if ( $(obj).data("save") ) 
		{
			if(confirm('确定删除该联系方式吗？'))
			{
				$(obj).closest(".form_ul_div").remove();
			}
		}
		else
		{
			$(obj).closest(".form_ul_div").remove();
		}
		hg_resize_nodeFrame();
	}
</script>

{if $a}
	{code}
		$css_attr['style'] = 'style="width:80px"';
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
			<h2>{$optext}杂志</h2>
			<ul class="form_ul">
				<li class="i">
					<div class="form_ul_div">
						<span class="title">杂志名称：</span>
						<input type="text" onblur="input_content_color(2);" onfocus="input_content_color(2);" id="required_2" name="title" value="{$formdata['name']}" size="50"/>
						  
						<div style="float:right;margin-right:70px;">
						{code}
							$item_source = array(
								'class' => 'down_list i',
								'show' => 'item_shows_',
								'width' => 100,/*列表宽度*/		
								'state' => 0, /*0--正常数据选择列表，1--日期选择*/
								'is_sub'=>1,
								'onclick'=>'',
							);
							$default = $group_id ? $group_id : -1;
							$gname = $appendSort[0];
							$gname[-1] = '选择分类';
							
						{/code}
						{template:form/search_source,group_id,$default,$gname,$item_source}
						</div>
					
					</div>
			
					<div class="form_ul_div">	
						<span class="title">描述：</span>
						{template:form/textarea,brief,$formdata['brief']}
					</div>
					<div class="form_ul_div clear">
						<span class="title">杂志周期: </span>{template:form/select,release_cycle,$formdata['release_cycle'],$_configs['release_cycle'], $css_attr}<font class="important"></font>
					</div>
					
					<div class="form_ul_div clear">
						<span  class="title">总期数: </span><input type="text" name="volume" size="50" value="{$formdata['volume']}" /><font class="important">累计期数，不填默认为0</font>
					</div>
					<div class="form_ul_div clear">
						<span  class="title">当前期数: </span><input type="text" name="current_nper" size="50" value="{$formdata['current_nper']}" /><font class="important">出版杂志的当前期数,不填默认为0</font>
					</div>
					
				</li>
				<!-- 
				<li class="i">
					<div class="form_ul_div clear">
					<a class="common-publish-button overflow title" href="javascript:;" _default="发布至" _prev="发布至：">发布至</a>
					{template:unit/publish, 1, $formdata['column_id']}
					</div>
				</li>
				 -->
				<li class="i">
	
					{if($formdata['contract'])}
					{foreach $formdata['contract']['contract_name'] as $k=>$v}
					<div class='form_ul_div clear'><span class='title'>联系方式: </span><input type='text' name='contract_name[]' value='{$v}' style='width:90px;' class='title'>&nbsp;&nbsp;&nbsp;<input type='text' name='contract_value[]' value='{$formdata["contract"]["contract_value"][$k]}' size='40'/>&nbsp;&nbsp;
					<span class='option_del_box'><span name='option_del[]' class='option_del' title='删除' data-save="1" onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span></div>
					{/foreach}
					{/if}
					<div id="extend"></div>
					<div class="form_ul_div clear">
						<span type="text" style="cursor:pointer;padding: 5px 20px;margin-left: 75px;background-color: #5B5B5B;color: white;border-radius: 2px;" onclick="hg_addContactDom();">添加联系方式</span>
					</div>
					
				</li>
				<li class="i">
					<div class="form_ul_div clear">
						<span  class="title">主办单位: </span><input type="text" name="sponsor" size="50" value="{$formdata['sponsor']}" />
					</div>
					<div class="form_ul_div clear">
						<span  class="title">责任编辑: </span><input type="text" name="editor" size="50" value="{$formdata['editor']}" />
					</div>
					<div class="form_ul_div clear">
						<span  class="title">国内统一刊号: </span><input type="text" name="cssn" size="50" value="{$formdata['cssn']}" />
					</div>
					<div class="form_ul_div clear">
						<span  class="title">国际标准刊号: </span><input type="text" name="issn" size="50" value="{$formdata['issn']}" />
					</div>
					<div class="form_ul_div clear">
						<span  class="title">语言: </span><input type="text" name="language" size="50" value="{$formdata['language']}" />
					</div>
					<div class="form_ul_div clear">
						<span  class="title">页数: </span><input type="text" name="page_num" size="50" value="{$formdata['page_num']}" />
					</div>
					<div class="form_ul_div clear">
						<span  class="title">定价RMB: </span><input type="text" name="price" size="50" value="{$formdata['price']}" />
					</div>
				</li>
				
			</ul>
		
		<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14"/>
		<input type="hidden" name="a" value="{$action}" id="action" />
		<input type="hidden" name="{$primary_key}" value="{$formdata['id']}" />
		<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
		</form>
		<div id="question_html" style="display:none;">
		{template:unit/question_create_form}
		</div>

		</div>
		<div class="right_version">
			<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
		</div>
{template:foot}