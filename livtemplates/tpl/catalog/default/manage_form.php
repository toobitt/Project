{template:head}
{css:ad_style}
{css:catalog_form}
{js:jqueryfn/jquery.tmpl.min}
{js:area}
{js:catalog/manage_form}
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
{code}//hg_pre($get_sort);{/code}
<script>
jQuery(function($){
	//new PCAS("province", "city", "area");
})
</script>
<div class="wrap clear">
<div class="ad_middle" style="width:850px">
<form name="editform" action="" method="post" class="ad_form h_l">
<h2>{$optext}编目信息</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">编目名称: </span>
		<input type="text" name="zh_name"  value="{$zh_name}" />
	</div>
	<div class="form_ul_div clear">
		<span class="title">编目标识: </span>
		<input type="text" name="catalog_field"  {if $field} readonly="readonly" {/if} value="{$catalog_field}" />
	</div>
	<div class="form_ul_div clear">
		<span class="title">描述备注: </span><textarea name="remark" id="remark"  cols="45" rows="4" />{$remark}</textarea>
	</div>
</li>

<li class="i">
	<div class="form_ul_div clear sort">
	<span class="title">编目分类: </span>
	 <select name="catalog_sort_id">
		{foreach $catalogsort as $k=>$v}
	      {code}
	       $sortname = $v['catalog_sort_name'];
	       $catalogsort_id = $v['catalog_sort_id'];
	      {/code}
         <option {if $catalogsort_id == $catalog_sort_id} selected="selected"{/if}  value ="{$catalogsort_id}">{$sortname}</option>
	     {/foreach}
	   </select>
	</div>
</li>
<!-- here -->	
<li class="i option-type">
	<div class="form_ul_div clear pre-option">
		<span class="title">编目样式: </span>
		<ul class="type-choose clear">
			{foreach $styles as $k=>$v}
		         {code}
		         	$style = $v['zh_name'];
		            $form_style_id = $v['id'];
		            $form_type = $v['type'];
		         {/code}
		         <li ><input type="radio" class="data-type" name="form_style" {if ($form_style_id==$form_style)} checked="checked"{/if}  value ="{$form_style_id}" _val="{$style}" _type="{$form_type}"><span>{$style}</span></li>
		    {/foreach}
		</ul>
	</div>
	<div class="form_ul_div clear my-tpl-wrap">
		
	</div>
	<div id="option_value">
		<div id="default-option"  class="form_ul_div clear default-option" >
			<span class="title">预选项: </span>
			<div class="option-contain">
				{if $formdata['catalog_default']}
				{foreach $formdata['catalog_default'] as $k=>$v}
				<input name="catalog_default[]"  type= "text" placeholder="请输入预设选项" value="{$v}">
				{/foreach}
				{else}
				<input name="catalog_default[]"  type= "text" placeholder="请输入预设选项" />
				{/if}
			</div>
			<p class="add-option">+</p>
		</div>
		<div id="default-selected" class="form_ul_div clear default-value">
			<span class="title">默认值: </span>
			<input type="text" name="selected"  value="{$selected}" placeholder="编目样式如果为单选、多选、下拉，则默认值必须是预选值的一项"/>
		</div>
		<div id="type-custom" class="form_ul_div type-custom">
			<div class="clear">
				<span class="title">数据类型: </span>
				<input type="radio" name="datatype"  value="0" {if $datatype !=1}checked="checked"{/if}/>文本数据
				<input type="radio" name="datatype"  value="1" {if $datatype ==1}checked="checked"{/if}/>数值型数据
			</div>
			<div class="clear">
				<span class="title">单位：</span>
				<input type="text" name="unit"  value="{$unit}" />
			</div>
		</div>
		<div id="type-date" class="form_ul_div type-date">
			<div class="clear">
				<span class="title">时间显示: </span>
				<input type="checkbox" name="status" value="{$status}" {if $status==1}checked="checked"{/if}/>开启
			</div>
		</div>
		<div id="type-price" class="form_ul_div type-price">
			<div class="clear">
				<span class="title">折扣计算: </span>
				<input type="checkbox" name="status" value="{$status}" {if $status==1}checked="checked"{/if}/>开启
			</div>
		</div>
	</div>
	<div id="is_batch" class="form_ul_div clear default-batch">
		<span class="title">是否批量: </span>
		<ul class="type-choose clear">
			<li><input type="radio" name="batch" class="batch" {if $batch } checked="checked"{/if} value="1" /><span>是</span></li>
			<li><input type="radio" name="batch" class="batch" {if $batch == 0 } checked="checked"{/if} value="0" /><span>否</span></li>
		</ul>
		<span class="error" id="title_tips" style="display:none;"></span>
	</div>	
</li>
<!-- /here -->	
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">适用应用: </span>
		<ul class="type-choose clear">
			{foreach $catalog_apps as  $value}
		         {code}
		             $app_bundle = $value['bundle'];
			         $app_name = $value['name'];
			         $apps = $app_bundle . '@' . $app_name;
			        $flag = 0;
			        if(is_array($app_uniqueid))
			        {
			            if (in_array($apps,$app_uniqueid)) $flag=1;
			        }
			        else 
			        {
			            if($apps == $app_uniqueid) $flag=1;
			        }
		         {/code}
		         <li><input type="checkbox" {if $flag} checked="checked"{/if} value="{$apps}" size="50" name="app_uniqueid[]"/><span>{code}echo $app_name;{/code}</span></li>
	        {/foreach}	
	        <span class="error" id="title_tips" style="display:block;">{if $app_uniqueid}*取消适用应用将会导致历史数据丢失{/if}</span>	
		</ul>
	</div>
</li>

<li id="is_bak" class="i default-reduce">
	<div class="form_ul_div clear ">
		<span class="title">是否冗余: </span>
		<ul class="type-choose clear">
			<li><input type="radio" name="bak" class="bak" {if $bak}checked="checked"{/if} value="1" /><span>是</span></li>
			<li><input type="radio" name="bak" class="bak" {if $bak == 0 }checked="checked"{/if} value="0" /><span>否</span></li>
		</ul>
		<span class="error" id="title_tips" style="display:none;"></span>
	</div>
</li>	

<li class="i default-fill">
	<div class="form_ul_div clear">
		<span class="title">是否必填: </span>
		<ul class="type-choose clear">
			<li><input type="radio" name="required" class="required" {if $required } checked="checked"{/if} value="1" /><span>是</span></li>
			<li><input type="radio" name="required" class="required" {if $required == 0 } checked="checked"{/if} value="0" /><span>否</span></li>
		</ul>
		<span class="error" id="title_tips" style="display:none;"></span>
	</div>
</li>
</ul>

<input type="hidden" name="a" value="{$action}" />
<input type="hidden" name="is_del" id="is_del" value="0" />
<input type="hidden" name="{$primary_key}" value="{$formdata['id']}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<br />
<div class="temp-edit-buttons" style="height: 50px;">
<input type="submit" name="sub" value="{$optext}" class="edit-button submit"/>
<input type="button" value="取消" class="edit-button cancel" onclick="javascript:history.go(-1);"/>
</div>
</form>
</div>
<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}
<script type = "text/x-jquery-tmpl" id="option-tpl">
	<input _val="${txt}" name="catalog_default[]"  type= "text" placeholder="请输入预设选项" value="${value}">
</script>
<script type = "text/x-jquery-tmpl" id="add-option-tpl">
	<input name="catalog_default[]"  type= "text" placeholder="请输入预设选项" value="" />
</script>
<script>
$.globaldefault = {code} echo json_encode($formdata['catalog_default']);{/code};
$.formdata = {code}echo $formdata ? json_encode($formdata) : '{}';{/code};
console.log( $.formdata );
</script>
<script type = "text/x-jquery-tmpl" id="my-tpls">
{{if type=="classify"}}
<div class="style-setting-item">
	<span class="title">值: </span>
	<input name="catalog_default" value="{{= formdata.catalog_default && formdata.catalog_default.join(',')}}"/>
</div>
{{/if}}
{{if type=="price"}}
<div class="style-setting-item">
	<span class="title">现价: </span>
	<input name="catalog_default[]" value="{{= formdata.catalog_default && formdata.catalog_default[0]}}"/>
</div>
<div class="style-setting-item">
	<span class="title">原价: </span>
	<input name="catalog_default[]" value="{{= formdata.catalog_default && formdata.catalog_default[1]}}"/>
</div>
<div class="style-setting-item">
	<span class="title">折扣计算: </span>
	<input name="status" value="{{= formdata.status}}" type="checkbox" {{if formdata && formdata.status==1}}checked="checked"{{/if}}/>开启
</div>
{{/if}}
{{if type=="date"}}
<div class="style-setting-item">
	<span class="title">时间显示: </span>
	<input name="status" value="{{= formdata.status}}" type="checkbox" {{if formdata.status==1}}checked="checked"{{/if}}/>开启
</div>
{{/if}}
{{if type=="label"}}
<div class="style-setting-item">
	<span class="title">值: </span>
	<input name="catalog_default" value="{{= formdata.catalog_default && formdata.catalog_default.join(',')}}"/>
</div>
{{/if}}
{{if type=="custom"}}
<div class="style-setting-item">
	<span class="title">数据类型: </span>
	<input type="radio" name="datatype" value="0"{{if formdata.status != 1}}checked="checked"{{/if}}/>文本数据
	<input type="radio" name="datatype" value="1"{{if formdata.status == 1}}checked="checked"{{/if}}/>数值型数据
</div>
<div class="style-setting-item">
	<span class="title">单位: </span>
	<input name="unit" value="{{= formdata.unit}}"/>
</div>
{{/if}}
</script>