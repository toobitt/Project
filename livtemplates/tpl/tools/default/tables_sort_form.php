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
jQuery(function($){
	$('.btn-add').click(function(){
		var parent_div = $(this).parent().clone();
		var parent_html = '<span class="title"></span>' + trim(parent_div.prop("outerHTML"));
		var self_html = $(this).prop("outerHTML");
		parent_html = parent_html.replace(self_html,'');
		$(this).parent().parent().append(parent_html);
		//console.log(parent_html);
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
</style>
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data" method="post"   id="content_form">
<h2>{$optext}信息</h2>
<ul class="form_ul">
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">名称：</span><input type="text" value='{$name}' name='name' class="site_title"> 
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