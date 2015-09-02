{template:head}
{css:ad_style}
{css:vote_style}
{js:mms_default}
{js:input_file}
{js:vote}
{css:column_node}
{js:column_node}

{code}
$group_id = $formdata['magazine_id']?$formdata['magazine_id']:$formdata['maga_id'];
{/code}

<script type="text/javascript">
//获取杂志当前期数
function get_cur_nper(id){
	var url = './run.php?mid=' + gMid + '&a=get_cur_nper&maga_id=' + id;
	hg_ajax_post(url);
};
function get_back(json)
{
	//alert(json);
	var obj = eval("("+json+")");
	var nper = parseInt(obj.current_nper)+1;
	$('#required_2').val(nper);
	var total = parseInt(obj.volume)+1;
	$('#total_issue').val(total);
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
			<h2>{$optext}期刊</h2>
			<ul class="form_ul">
				<li class="i">
					<div class="form_ul_div clear">
					<span class="title">选择杂志：</span>
						{code}
							$item_source = array(
								'class' => 'down_list i',
								'show' => 'item_shows_',
								'width' => 100,/*列表宽度*/		
								'state' => 0, /*0--正常数据选择列表，1--日期选择*/
								'is_sub'=>1,
								'onclick'=>"get_cur_nper(this.getAttribute('attrid'))",
							);
							$default = $group_id ? $group_id : -1;
							$gname[$default] = '选择杂志';
							foreach($appendMagazine AS $k =>$v)
							{
								$gname[$v['id']] = $v['name'];
							}
							if($a == 'update')
							{
								$arr[$group_id] = $gname[$group_id];
								$gname = $arr;
							}
						{/code}
						{template:form/search_source,magazine_id,$default,$gname,$item_source}
						</div>
						<div class="form_ul_div clear">
							<span class="title">当前期号：</span>
							<input type="text" id="required_2" name="issue" value="{$formdata['issue']}" size="15"/><font class="important">默认自动生成,年份以出版日期为算</font>
						</div>
				</li>
				<li class="i">
					<div class="form_ul_div clear">
							<span class="title">总第：</span>
							<input type="text" id="total_issue" name="total_issue" value="{$formdata['total_issue']}" size="15"/>期<font class="important">默认自动生成</font>
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div clear">
							<span class="title">文章总数：</span>
							<input type="text" name="total_article" value="{$formdata['total_article']}" size="15"/><font class="important">此期杂志文章总数</font>
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div clear">
						<span class="title">封面：</span>
						<span class="file_input s" id="file_input" style="float:left;">选择文件</span>
						<span id="file_text" class="overflow file-text s">{$logo}</span>
						<span id="logo_img" style="float:right;border:1px solid #DADADA;">{if $formdata['url']}<img width=70 height=90 src="{$formdata['url']}" />{/if}</span>
						<input onclick="hg_logo_value();" name="files" type="file"  value="" class="vote_file" id="f_file"  hidefocus>
					</div>
				</li>

				<li class="i">
					<div class="form_ul_div">
						<span class="title">出版日期：</span>
						<input  id="pub_date" type="text" value="{$pub_date}" autocomplete="off" size="20" onfocus="if(1){WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd'});}" name="pub_date">
					</div>
				</li>
				{code}
					$audit_css = array(
						'class' =>'transcoding down_list',
						'show' => 'audit_item',
						'width' => 100,
						'state' => 0,
						'is_sub' => 1
					);
					$formdata['state'] = $formdata['state']?$formdata['state']:0;
				{/code}
				<!-- <li class="i">
					<div class="form_ul_div clear">
						<span class="title">审核状态：</span>									
						{template:form/search_source,state,$formdata['state'],$_configs['issue_audit'],$audit_css}						
					</div>
				</li>
				
				<li class="i">
					<div class="form_ul_div clear">
					<a class="common-publish-button overflow title" href="javascript:;" _default="发布至" _prev="发布至：">发布至</a>
					{template:unit/publish, 1, $formdata['column_id']}
					</div>
				</li>
				 -->
			</ul>
		
		<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14"/>
		<input type="hidden" name="a" value="{$action}" id="action" />
		<input type="hidden" name="id" value="{$formdata['id']}" />
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
{if !$id}
<script type="text/javascript">

$(function () {
	var id = $('#magazine_id').val();
	var func = function () {
		if (window.hg_ajax_post) {
			get_cur_nper(id);
		} else {
			setTimeout(function () {
				func();
			}, 100);
		}
	};
	func();
});

</script>
{/if}
{template:foot}
