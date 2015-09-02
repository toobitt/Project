{template:head}
{code}
    if($id)
    {
        $optext="更新";
        $ac="update";
    }
    else
    {
        $optext="新增";
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
{css:column_node}
{js:column_node}
<script type="text/javascript">
function trim(str)
{ 
	return str.replace(/(^\s*)|(\s*$)/g, ""); 
}

function hg_plan_del(id)
{
	if(confirm('确定删除该条记录？！'))
	{
		var url = './run.php?mid=' + gMid + '&a=delete&id=' + id + '&infrm=1&ajax=1';
		hg_request_to(url);
	}
}
function hg_call_plan_del(data)
{
	data = data.replace(/'/g, "");
	var ids = data.split(",");
	for(i=0;i<ids.length;i++)
	{
		$("#r_"+ids[i]).slideUp(1000).remove();
	}
	if($("#checkall").attr('checked'))
	{
		$("#checkall").removeAttr('checked');
	}
	hg_close_opration_info();
}

function hg_get_key(n)
{
	var Num=""; 
	for(var i=0; i<n; i++) 
	{ 
		Num +=Math.floor(Math.random()*10); 
	}
	return Num;
}


var chars = ['0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
function hg_get_key(n)
{
     var res = "";
     for(var i = 0; i < n ; i ++) 
     {
         var id = Math.ceil(Math.random()*35);
         res += chars[id];
     }
     return res;
}


jQuery(function($){
	if(!$("input[name='id']").val())
	{
		$("input[name='field_key[]']").val(hg_get_key(5));
	}
	$('.btn-add').click(function(){
		var parent_div = $(this).parent().clone();
		var parent_html = '<span class="title"></span>' + trim(parent_div.prop("outerHTML"));
		var self_html = $(this).prop("outerHTML");
		
		parent_html = parent_html.replace(self_html,'<span class="field-del">删除</span>');
		var tmp_val = $(parent_html).find("input[name='field_key[]']").val();
		parent_html = parent_html.replace(tmp_val,hg_get_key(5));
		tmp_val = $(parent_html).find("input[name='field_name[]']").val();
		parent_html = parent_html.replace(tmp_val,'');
		tmp_val = $(parent_html).find("input[name='field_length[]']").val();
		parent_html = parent_html.replace(tmp_val,'');
		tmp_val = $(parent_html).find("input[name='field_mark[]']").val();
		parent_html = parent_html.replace(tmp_val,'');
		parent_html = parent_html.replace('checked="checked"','');
		parent_html = parent_html.replace(/selected="selected"/g,'');
				
		$(this).parent().parent().append(parent_html);
		$('.field-del').click(function(){
			$(this).parent().prev('span.title').remove();
			$(this).parent().remove();
		});
	});
	$('.field-del').click(function(){
		$(this).parent().prev('span.title').remove();
		$(this).parent().remove();
	});
});
</script>

<style>
.btn-add{font-size: 30px; font-weight: bolder; cursor: pointer; float: right; min-height: 24px; line-height: 24px;}
.field{margin-top: 5px;}
.field_name{width: 70px;float: left;margin-right: 10px;}
.field_length{width: 70px; margin-right: 10px;}
.field_auto{height: 13px; margin-right: 10px;}
.down_type{margin-right: 10px;}
.field-del{cursor: pointer;}
</style>
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data" method="post"   id="content_form">
<h2>{$optext}信息</h2>
<ul class="form_ul">
<li class="i">
<div class="form_ul_div clear">
        <span class="title">所属分类：</span>
        {code}
			$server_source = array(
				'class' => 'down_list',
				'show' => 'server_show',
				'width' => 100,/*列表宽度*/		
				'state' => 0, /*0--正常数据选择列表，1--日期选择*/
				'is_sub'=>1,
			);
			$default = $sort_id ? $sort_id : -1;
			$server_item[$default] = '--选择--';
			foreach($sort_info as $k =>$v)
			{
				$server_item[$v['id']] = $v['name'];
			}
		{/code}
		{template:form/search_source,sort_id,$default,$server_item,$server_source}
    </div>
</li>
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">名称：</span><input type="text" value='{$name}' name='name' class="site_title">
    </div>
</li>
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">表名：</span><input type="text" value='{$table_name}' name='table_name' class="site_title">
    </div>
</li>
<li class="i">
    <div class="form_ul_div clear">
    	{if !$table_format}
        <span class="title">字段：</span>
        	<div class="field"><input type="text" value='{$field_name}' name='field_name[]' class="field_name">
        	{code}
   				$field_type = $_configs['field_type'];
				$server_item = array();
				$default = $field_type_id ? $field_type_id : 0;
			{/code}
			{if $field_type}
			<select class="down_type" name="field_type[]">
			{foreach $field_type as $k => $v}
				<option value="{$k}">{$v}</option>
			{/foreach}
			</select>
			{/if}			
			<input type="text" value='{$field_length}' placeholder='长度' name='field_length[]' class="field_length">
			{code}
				$field_index = $_configs['field_index'];
				$server_item = array();
				$default = $field_index_id ? $field_index_id : 0;
			{/code}
			{if $field_index}
			<select class="down_type" name="field_index[]">
			{foreach $field_index as $k => $v}
				<option value="{$k}">{$v}</option>
			{/foreach}
			</select>
			{/if}
			自增：<input type="checkbox" value='1' name='field_auto[]' class="field_auto">
			<input type="text" value='{$field_mark}' placeholder='备注' name='field_mark[]' class="field_mark">			
			<input type="hidden" value="" name="field_key[]" />
        	<span class="btn-add" onselectstart="return false">+</span>
        </div>
        {else}
        {code}
        $length = count($table_format);
        $i = 0;
        foreach($table_format as $key => $value)
        { 
        	if(in_array($value['field_name'],array('column_id','column_name')))
        	{
        		continue;
        	}
        {/code}
        	{if $i}
        		<span class="title"></span>
        	{else}
        		<span class="title">字段：</span>
        	{/if}
    	    <div class="field">
    		<input type="text" value="{$value['field_name']}" name="field_name[]" class="field_name">
    		{code}
				$field_type = $_configs['field_type'];
			{/code}
        	{if $field_type}
			<select class="down_type" name="field_type[]">
			{foreach $field_type as $k => $v}
				<option {if $k == $value['field_type']}selected="selected"{/if} value="{$k}">{$v}</option>
			{/foreach}
			</select>
			{/if}
			<input type="text" value='{$value['field_length']}' placeholder='长度' name='field_length[]' class="field_length">
			{code}
				$field_index = $_configs['field_index'];
			{/code}
			{if $field_index}
			<select class="down_type" name="field_index[]">
			{foreach $field_index as $k => $v}
				<option {if $k == $value['field_index']}selected="selected"{/if} value="{$k}">{$v}</option>
			{/foreach}
			</select>
			{/if}
			自增：<input type="checkbox" {if $value['field_auto']}checked="checked"{/if} value="1" name="field_auto[]" class="field_auto">
			<input type="text" value="{$value['field_mark']}" placeholder="备注" name="field_mark[]" class="field_mark">
			<input type="hidden" value="{$value['field_key']}" name="field_key[]" />
			{if !$i}			
        	<span class="btn-add" onselectstart="return false">+</span>
        	{else}
        	<span class="field-del">删除</span>
			{/if}</div>
        {code}
        $i ++;
        }
        {/code}
        {/if}
    </div>
</li>
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">栏目：</span><input type="checkbox" value='1' {if $is_column}checked="checked"{/if} name='is_column' class="site_title">
    </div>
</li>
</ul>
<input type="hidden" name="a" value="{$ac}" />
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="html" value="1" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<script>
jQuery(function($){});
	</script>
<div class="right_version">
    <h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>

{template:foot}