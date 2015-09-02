<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{css:2013/form}
{css:common/common}
{css:mobile_form}
{css:ad_style}
{code}
$id = $_INPUT['id'];
if($id)
{
	$a = 'update_map';
}
else
{
	$a = 'create_map';
}
$file_name = $_INPUT['file_name'];
$css_attr['style'] = 'style="width:100px"';
$map = $formdata;

{/code}
<style>
.form_ul_div.l input{float:left}
.form_ul .n-h{float:none}
.ad_middle table textarea{min-width:0;min-height: 0;}
.option_del_box{display:inline-block;width:16px;height:16px;cursor:pointer;margin-left:10px;}
.option_del{vertical-align:middle;display:none;width:16px;height:16px;cursor:pointer;margin-left:10px;background: url('{$RESOURCE_URL}close_plan.png') 0 0 no-repeat;}
.option_del_b{width:16px;height:16px;cursor:pointer;margin-left:10px;background: url('{$RESOURCE_URL}close_plan.png') 0 0 no-repeat;}
</style>
<script type="text/javascript">
	function hg_addArgumentDom()
	{
		var sel = $("#sel_map").html();
		
		var div = "<div class='form_ul_div clear'><span class='title'>模块字段: </span><input type='text' name='mod[]' style='width:90px;' class='title'>&nbsp;&nbsp;&nbsp;<span>映射字段:&nbsp;<input type='text' name='map[]' style='width:250px;' class='title'></span><span class='option_del_box'><span name='option_del[]' class='option_del' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline-block; '></span></span></div>";
		$('#extend').append(div);
	}
	function hg_optionTitleDel(obj)
	{
		if($(obj).data('save'))
		{
			if(confirm('确定删除该映射吗？'))
			{
				$(obj).closest('.form_ul_div').remove();
			}
		}
		else
		{
			$(obj).closest('.form_ul_div').remove();
		}
		hg_resize_nodeFrame();
	}
</script>
<form name="editform" action="run.php?mid={$_INPUT['mid']}" method="post" class="ad_form h_l">
	<header class="m2o-header">
      <div class="m2o-inner">
        <div class="m2o-title m2o-flex m2o-flex-center">
            <h1 class="m2o-l">{$file_name}字段映射</h1>
            <div class="m2o-l m2o-flex-one"></div>
            <div class="m2o-btn m2o-r">
                <input type="submit" value="保存" class="m2o-save" name="sub" id="sub" />
                <span class="m2o-close option-iframe-back"></span>
            </div>
        </div>
      </div>
    </header>
    <div class="m2o-inner">
    	<div class="m2o-main m2o-flex">
    		<section class="m2o-m m2o-flex-one">
    			{if $message}
				<div class="error">{$message}</div>
				{/if}
    			<ul class="form_ul">
					<li class="i">
						<div class="form_ul_div clear">
							<span class='title'>映射规则: </span><span>映射字段用{}括起来，节点前加$，字符串不用加，支持多个节点拼接</span>
						</div>
					</li>
					<li class="i">
						
						{if($map)}
							{foreach $map as $kk=>$vv}
							<div class='form_ul_div clear'><span class='title'>模块字段: </span><input type='text' name='mod[]' value='{$vv}' style='width:90px;' class='title'>&nbsp;&nbsp;
							<span>映射字段: </span>
							
								<input type='text' name='map[]' value='{$kk}' style='width:250px;' class='title'>
								
							<span class='option_del_box'><span name='option_del[]' class='option_del' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline-block; margin-left:6px;'></span></span></div>
							{/foreach}
						{else}
							<div class='form_ul_div clear'><span class='title'>模块字段: </span><input type='text' name='mod[]' value='{$vv}' style='width:90px;' class='title'>&nbsp;&nbsp;
							<span>映射字段: </span>
							<input type='text' name='map[]' style='width:250px;' class='title'>
							<span class='option_del_box'><span name='option_del[]' data-save='1' class='option_del' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline-block; margin-left:6px; '></span></span></div>
						{/if}
						<div id="extend"></div>
						<div class="form_ul_div clear">
							<span type="text" style="cursor:pointer;padding: 5px 20px;margin-left: 75px;background-color: #5B5B5B;color: white;border-radius: 2px;" onclick="hg_addArgumentDom();">添加映射</span>
						</div>
					</li>
				</ul>
    		</section>
    	</div>
    </div>
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
</form>
{template:foot}