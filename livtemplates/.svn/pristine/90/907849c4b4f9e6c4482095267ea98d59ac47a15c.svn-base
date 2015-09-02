<?php 

?>
{template:head}
{css:column_form}
{css:ad_style}
{js:publishsys/datasource_out_variable}
{css:common/common_publish}
{code}
$info = $formdata;
foreach($info['data'] as $key=>$val)
{
	$name[] = $key;
	$title[] = $val;
}
$info['out_arment']['name'] = $name;
$info['out_arment']['title'] = $title;
//var_dump($info['data']);
//array(5) { ["title"]=> string(6) "标题" ["content"]=> string(6) "内容" ["author"]=> string(6) "作者" ["cate"]=> string(6) "栏目" ["url"]=> string(6) "联接" } 
//Array ( [name] => Array ( [8472] => title [8524] => id ) [title] => Array ( [8472] => 栏目名称 [8524] => 栏目id ) 
//Array ( [name] => Array ( [0] => title [1] => content ) [title] => Array ( [0] => 标题 [1] => 内容 ) )

$id = $_INPUT['id'];
$type = $_INPUT['type'];
$file_name = $_INPUT['file_name'];
if($type)
{
	$file_name = explode('.', $file_name);
	$file_name = $file_name[0].'_copy'.'.php';
}
$css_attr['style'] = 'style="width:100px"';


$out_arment  = !empty($info['out_arment']['name']) ? $info['out_arment'] : $_configs['defaultdata'];


{/code}

{css:2013/list}
{css:template_list}
<style>
.form_ul_div.l input{float:left}
.form_ul .n-h{float:none}
.ad_middle table textarea{min-width:0;min-height: 0;}
.option_del_box{display:inline-block;width:16px;height:16px;cursor:pointer;float:right;position: relative;right: 50px;top: 4px;}
.option_del{display:none;width:16px;height:16px;cursor:pointer;float:right;background: url('{$RESOURCE_URL}close_plan.png') 0 0 no-repeat;}
.option_del_b{width:16px;height:16px;cursor:pointer;float:right;position: relative;right: 140px;top: 4px;background: url('{$RESOURCE_URL}close_plan.png') 0 0 no-repeat;}
.temp-edit-buttons {margin:0 0 20px;}
#extend input{border:1px solid #DEDEDE!important;}
</style>
<style>
.column-delete-button {
    color: #115BA4;
    cursor: pointer;
    margin-left: 10px;
    text-decoration: underline;
    float:none;
    background:none;
}
.hide{
	display:none!important;
}
.ad_form .form_ul .domain span.title{float:none;display:inline-block;}
</style>

<script type="text/javascript">
	function hg_addoutArgumentDom(str)
	{
		var div = "<div class='form-each m2o-flex m2o-flex-center'><div class='form-item m2o-flex-two form-para'><input type='text' name='"+str+"new_out_name[]' class='title' value=''/></div><div class='form-item m2o-flex-one form-mark'><input type='text' name='"+str+"new_out_title[]' style='width:50px;' class='title' value=''/></div><div class='form-item form-delete'><span name=''+str+'option_del[]' class='option_delete' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline; '></span></div></div>";
		if(str=='')
		{
			$('.out-form-div').append(div);
		}
		get_out_ids();
		hg_resize_nodeFrame();
	}
	function hg_optionTitleDel(obj)
	{
		if(confirm('确定删除该参数配置吗？'))
		{
			$(obj).parent().parent().remove();
		}
		
		get_out_ids();
		hg_resize_nodeFrame();
	}
	$(document).ready(function(){
		var t1 = $("form select[name=sort_id]").find('option:selected').val();
		var c1 = $("input[name=referto]").val() + '&sortid=' + t1;
		$("input[name=referto]").val(c1);

		$("form select[name=sort_id]").change(function(){
			var t2 = $("form select[name=sort_id]").find('option:selected').val();
			var c2 = $("input[name=referto]").val() + '&sortid=' + t2;
			$("input[name=referto]").val(c2);
		});
		get_out_ids();
	});
	
	function get_out_ids()
	{
		var ids = $('.out_arment .form-each').map(function(){
		     return $(this).data('id');
		}).get().join(',');
		$('#out_ids').val(ids);
		//alert($('#out_ids').val());
	}
</script>

<div class="wrap clear">
<div class="ad_middle" style="width:850px">
<form name="editform"  id="editform" action="run.php?mid={$_INPUT['mid']}" method="post" class="ad_form h_l">
<h2>{if $_INPUT['id']}{$info['name']}编辑{else}分类新增{/if}</h2>
<ul class="form_ul">
<li class="i">
<div class="form_ul_div clear">
<span class="title">名称: </span>
<input type="text" name="name"  value="{$info['name']}" />
</div>
</li>

<li class="i">
<div class="form_ul_div clear">
<span class="title">描述: </span><textarea name="desc" cols="60" rows="5">{$info['desc']}</textarea>
</div>
</li>



<li class="i out-form-div-i"> 
  <div class="form_ul_div out-form-div form-div  clear">
    <div class="form-title">
     <span> 设置类别参数</span>
    </div>
  <div class="form-list m2o-flex m2o-flex-center">
     <div class="form-item m2o-flex-two form-para">参数名</div>
     <div class="form-item m2o-flex-one form-mark">标题</div>
     <!--<div class="form-item form-value">对应参数标识</div>-->
     <div class="form-item form-delete">&nbsp;</div> 
  </div>
  {code}
  {/code}
	{if $out_arment}
	<div class="out_arment">
		{foreach $out_arment['name'] as $k=>$v}
		  <div class="form-each m2o-flex m2o-flex-center items" data-id="{$k}">
	          <div class="form-item m2o-flex-two form-para form-border"><input type='text' name='out_arname[{$k}]' value='{$v}'  class='title'></div>
	          <div class="form-item m2o-flex-one form-mark form-border"><input type='text' name='out_artitle[{$k}]' value='{$out_arment["title"][$k]}' class='title bs'></div>
	          <!--<div class="form-item form-value form-border"><input type='text' name='out_arvalue[{$k}]' value='{$out_arment["value"][$k]}' class='title va' /></div>-->
	          <div class="form-item form-delete">
	          <!-- <span class='option_del_box'><span name='option_del[]' class='option_del' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span>  -->  
	            <span name='option_del[]' class='option_delete' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline; '></span>
	          </div>
	        </div>
	        <input type="hidden"  name="out_ar[]" value="{$k}" />
		{/foreach}
	</div>
	{/if}
	<input type="hidden" name="new_out_ar[]"  id="out_ids" />
 </div>
 <br />
 	<div id="out_extend">
	</div>
	<div class="form_ul_div clear">
		<span type="text" style="cursor:pointer;padding: 5px 20px;margin-left:15px;background-color: #5B5B5B;color: white;border-radius: 2px;" onclick="hg_addoutArgumentDom('');">添加数据参数</span>
	</div> 
</li>

</ul>


<input type="hidden" name="a" value="{$a}" id="a" />
<input type="hidden" name="id" id="id" value="{$id}" />
<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
<input type="hidden" name="fid" value="{$info['fid']}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<br />
<div class="temp-edit-buttons">
<input type="submit" name="sub" value="{$optext}" class="edit-button submit"/>
{if $a=='update'}
<input type="button" name="lcw" value="另存为" class="edit-button submit" onclick="$('#a').val('create');$('#editform').submit()"/>
{/if}
<input type="button" value="取消" class="edit-button cancel" onclick="javascript:history.go(-1);"/>
</div>
</form>
</div>
<div class="right_version"><h2><a href="javascript:void(0);" onclick="javascript:history.go(-1);">返回前一页</a></h2></div>
</div>
{template:foot}