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
{js:ad}
{css:column_node}
{js:column_node}
{css:xml_setting}
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data" method="post"   id="content_form">
<h2>{$optext}数据源</h2>
<ul class="form_ul">
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">名称：</span><input type="text" value='{$title}' name='title' class="site_title"><!--<a style="margin-left:10px;" href="###" onclick="alert(123);">xml格式</a>-->
    </div>
</li>
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">host：</span><input type="text" value='{$host}' name='host' class="site_title">
    </div>
</li>
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">dir：</span><input type="text" value='{$dir}' name='dir' class="site_title">
    </div>
</li>
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">文件名：</span><input type="text" value='{$filename}' name='filename' class="site_title">
    </div>
</li>
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">参数：</span><input type="text" value='{$parameter}' name='parameter' class="site_title">
    </div>
</li>
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">端口：</span><input type="text" value='{$port}' name='port' class="site_title" style="width: 30px;">
    </div>
</li>
<li class="i" style="padding-top: 5px;">
	<div class="form_ul_div">
		<span class="title">本地数据：</span>
		<div style="display:inline-block;width:255px">
			<label><input type="radio" name="islocal" value="1" {if $islocal}checked="checked"{/if} class="n-h"><span>是</span></label>
			<label><input type="radio" name="islocal" value="0" {if !$islocal}checked="checked"{/if} class="n-h"><span>否</span></label>
		</div>
		<!--<font class="important">审核</font>-->
	</div>
</li>
<li class="i" style="padding-top: 5px;" _child='1'>
	<div class="form_ul_div">
		<span class="title">数据类型：</span>
		{code}
			$item_source = array(
				'class' => 'down_list',
				'show' => 'item_show',
				'width' => 100,/*列表宽度*/		
				'state' => 0, /*0--正常数据选择列表，1--日期选择*/
				'is_sub'=>1,
			);
			$type_array = $_configs['data_type'];
			$default = $data_type ? $data_type : -1;
			$type_array[$default] = $data_type == -1 ? '选择分类':$type_array[$data_type];
		{/code}
		{template:form/search_source,data_type,$default,$type_array,$item_source}
		<div style="clear:both;"></div>
	</div>
</li>
<li class="i" style="padding-top: 5px;" _childsun='1'>
	<div class="form_ul_div">
		<span class="title">关联ID：</span><input type="text" value='{$cid}' name='cid' class="site_title" style="">（栏目id-内容ID，内容为栏目的简介，用英文逗号隔开表示多个）
	</div>
</li>
<li class="i" style="padding-top: 5px;">
	<div class="form_ul_div">
		<span class="title">审核：</span>
		<div style="display:inline-block;width:255px">
			<label><input type="radio" name="state" value="1" {if $state}checked="checked"{/if} class="n-h"><span>是</span></label>
			<label><input type="radio" name="state" value="0" {if !$state}checked="checked"{/if} class="n-h"><span>否</span></label>
		</div>
		<!--<font class="important">审核</font>-->
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
jQuery(function($){
	$(".form_ul").find("li").each(function(){
		if($(this).attr("_child"))
		{
			var is_child = 0;
			$("input[name='islocal']").each(function(){
				if($(this).attr('checked'))
				{
					is_child = $(this).val();
				}
			});
			if(parseInt(is_child))
			{
				$(this).show();
			}
			else
			{
				$(this).hide();
			}
		}
	});
	$("input[name='islocal']").click(function(){
		if(parseInt($(this).val()))
		{
			$(".form_ul").find("li").each(function(){
				if($(this).attr("_child"))
				{
					$(this).show();
				}
			});
		}
		else
		{
			$(".form_ul").find("li").each(function(){
				if($(this).attr("_child"))
				{
					$(this).hide();
				}
			});
		}
	});
		$("#item_show").find("li").click(function(){
			
			if($("#data_type").val())
			{
				//console.log($("#data_type").val());
				switch($("#data_type").val())
				{
					case 'variety':
						$(".form_ul").find("li").each(function(){
							if($(this).attr("_childsun"))
							{
								$(this).show();
							}
						});
					break;
					case 'tv':
					break;
					default:
						$(".form_ul").find("li").each(function(){
							if($(this).attr("_childsun"))
							{
								$(this).hide();
								$("input[name=cid]").val('');
							}
						});
					break;
				}
			}
			
		});
		$("#data_type").each(function() { 
		console.log($(this).val());
			switch($(this).val())
			{
				case 'variety':
					$(".form_ul").find("li").each(function(){
						if($(this).attr("_childsun"))
						{
							$(this).show();
						}
					});
				break;
				case 'tv':
					$(".form_ul").find("li").each(function(){
						if($(this).attr("_childsun"))
						{
							$(this).show();
						}
					});
				break;
				default:
					$(".form_ul").find("li").each(function(){
						if($(this).attr("_childsun"))
						{
							$(this).hide();
							$("input[name=cid]").val('');
						}
					});
				break;
			}
		});
	});
	</script>
<div class="right_version">
    <h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>

{template:foot}