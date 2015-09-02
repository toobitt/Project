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
<script>
	var gSourceData = [];
	var gSourceChild = [];
	var gSourceChildKey = [];
</script>
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data" method="post"   id="content_form">
<h2>{$optext}xml配置</h2>
<ul class="form_ul">
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">名称：</span><input type="text" value='{$title}' name='title' class="site_title" />
    </div>
</li>
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">内容：</span><textarea rows="10" cols="8" name="content" style="overflow-y: auto;">{$content}</textarea>
    </div>
</li>

<li class="i">
    <div class="form_ul_div clear">
        <span class="title">数据分类：</span>{if $source}
        <div style=" line-height: 24px; min-height: 24px; " id="source-data">
        	{foreach $source as $key => $value}
	        <input {if $source_id == $value['id']}checked="checked"{/if} type="radio" value="{$value['id']}" id="raido{$value['id']}" name="source_id" style="float: left;height: 22px"/><label for="raido{$value['id']}" style="margin:0 10px 0 5px;">{$value['title']}</label>
	        {code}
	        	$tmp_content = json_decode($value["content"],1);
	        	$tmp_source_data = json_encode(array_keys($tmp_content));
	        	$tmp_child = $tmp_child_key = array();
	        	foreach($tmp_content as $kk => $vv)
	        	{
	        		if(is_array($vv))
	        		{
	        			$tmp_child[$kk] = $vv;
	        			$tmp_child_key[] = $kk;
	        		}
	        	}
	        	$tmp_child = !empty($tmp_child) ? json_encode($tmp_child) : array();
	        	$tmp_child_key = !empty($tmp_child_key) ? json_encode($tmp_child_key) : array();
	        {/code}
	        <script>
		        gSourceData[{$value['id']}] = '{$tmp_source_data}';
	        </script>
	        {if !empty($tmp_child_key)}
	        <script>
		        gSourceChildKey[{$value['id']}] = '{$tmp_child_key}';
	        </script>
	        {/if}
	        {if !empty($tmp_child)}
	        <script>
		        gSourceChild[{$value['id']}] = '{$tmp_child}';
	        </script>
	        {/if}
	        {/foreach}</div>
        {/if}
    </div>
</li>
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">数据源：</span>
        <div id="source-list" class="source-list"></div>
        <div id="child-list"></div>
    </div>
</li>
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">文件名：</span>
        <input type="text" value='{$file_name}' name='file_name' class="site_title" style=" width: 70px; "/>.xml（增量文件格式为文件名+数字）
    </div>
</li>
<li class="i" style="padding-top: 5px;">
	<div class="form_ul_div">
		<span class="title">是否索引：</span>
		<div style="display:inline-block;width:255px">
			<label><input type="radio" name="is_index" value="1" {if $is_index}checked="checked"{/if} class="n-h"><span>是</span></label>
			<label><input type="radio" name="is_index" value="0" {if !$is_index}checked="checked"{/if} class="n-h"><span>否</span></label>
			(是否需要索引文件)
		</div>
		<!--<font class="important">审核</font>-->
	</div>
</li>
<li class="i" style="padding-top: 5px;" _child='1'>
	<div class="form_ul_div">
		<span class="title">索引文件：</span><input type="text" value='{$index_file}' name='index_file' class="site_title" style=" width: 70px; "/>.xml
	</div>
</li>
<li class="i" style="padding-top: 5px;" _child='1'>
	<div class="form_ul_div">
		<span class="title">文件格式：</span><textarea rows="10" cols="8" name="index_content" style="overflow-y: auto;margin-top: 10px;">{$index_content}</textarea>
	</div>
</li>
<li class="i" style="padding-top: 5px;" _child='1'>
	<div class="form_ul_div">
		<span class="title">索引源：</span>
		<div class="source-list" style="float: none;width: 94px;">
			{if !empty($xml_struct)}
			<ul class="list-ul">
				{foreach $xml_struct as $k => $v}
				<li class="list-li">{$v}</li>
				{/foreach}
			</ul>
			{/if}
		</div>
	</div>
</li>
<li class="i" style="padding-top: 5px;">
	<div class="form_ul_div">
		<span class="title">是否拆分：</span>
		<div style="display:inline-block;width:255px">
			<label><input type="radio" name="is_split" value="1" {if $is_split}checked="checked"{/if} class="n-h"><span>是</span></label>
			<label><input type="radio" name="is_split" value="0" {if !$is_split}checked="checked"{/if} class="n-h"><span>否</span></label>
		</div>
		<!--<font class="important">审核</font>-->
	</div>
</li>
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">数量：</span>
        <input type="text" value='{$count_num}' name='count_num' class="site_title" style=" width: 70px; "/>
    </div>
</li>
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">起始值：</span>
        <input type="text" value='{$offset_num}' name='offset_num' class="site_title" style=" width: 70px; "/>
    </div>
</li>
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">生成时间：</span>
        <input type="text" value='{$space_time}' name='space_time' class="site_title" style=" width: 30px; "/> /s（秒,0为单次）
    </div>
</li>
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">文件限制：</span>
        <input type="text" value='{$file_size}' name='file_size' class="site_title" style=" width: 50px; "/> /M（0为单个文件）
    </div>
</li>
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">有效期：</span>
        <input type="text" value='{$valid_time}' name='valid_time' class="site_title" style=" width: 50px; "/> /h（0为无结束时间）
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
<div class="right_version">
    <h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>
<script>
jQuery(function($){
	$(".form_ul").find("li").each(function(){
		if($(this).attr("_child"))
		{
			var is_child = 0;
			$("input[name='is_index']").each(function(){
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
	$("input[name='is_index']").click(function(){
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
	$("#source-data").find("input[type=radio]").each(function(){
		if($(this).attr('checked') == 'checked' && $(this).val())
		{
			$('#child-list').html('');
			var kk = $(this).val();
			var obj = eval("("+gSourceData[kk]+")");
		//	console.log(obj);
			var leng = obj.length;
			var html = '<ul class="list-ul">';
			var obj_key = '';
			var obj_data = '';
			if(gSourceChildKey[kk])
			{
				obj_key = eval("(" + gSourceChildKey[kk] + ")");
				obj_data = eval("(" + gSourceChild[kk] + ")");
			}
			//console.log(obj_key);
			for(i =0 ;i < leng;i++)
			{
				var tmp_key = '';
				for(j = 0;j<obj_key.length;j++)
				{
					if(obj_key[j] == obj[i])
					{
						tmp_key = ' _data="'+kk+',' + obj_key[j] +'" style="cursor:pointer;"';
						break;
					}
				}
				html += '<li class="list-li" ' + tmp_key  + '>' + obj[i] + '</li>';
			}
			html += '</ul>';
			$('#source-list').html(html);
		//	console.log(obj_data);
			$('.list-ul').find("li").each(function(){
				if($(this).attr('_data'))
				{
					$(this).mouseover(function(){
						$(this).attr('style','cursor:pointer;background-color:#DAD2C2;');
						console.log($(this).text());
						var tmp_key = $(this).text();
						var child_html = '<ul class="child-ul">';
						var child_length = obj_data[tmp_key].length;
						for(var m = 0 ;m < child_length;m++)
						{
							child_html += '<li class="child-li">' + obj_data[tmp_key][m] +'</li>';
						}
						child_html += '</ul>';
						$('#child-list').addClass("child-list").html(child_html);//
					});
					$(this).mouseout(function(){
						$(this).attr('style','cursor:pointer;background-color:#F0EFEF;');
						$('#child-list').removeClass("child-list").html('');						
					});
				}
			});
		}
		
	});
	$("#source-data").find("input[type=radio]").click(function(){
		if($(this).attr('checked') == 'checked')
		{
			$('#child-list').html('');
			var kk = $(this).val();
			var obj = eval("("+gSourceData[kk]+")");
		//	console.log(obj);
			var leng = obj.length;
			var html = '<ul class="list-ul">';
			var obj_key = '';
			var obj_data = '';
			if(gSourceChildKey[kk])
			{
				obj_key = eval("(" + gSourceChildKey[kk] + ")");
				obj_data = eval("(" + gSourceChild[kk] + ")");
			}
			//console.log(obj_key);
			for(i =0 ;i < leng;i++)
			{
				var tmp_key = '';
				for(j = 0;j<obj_key.length;j++)
				{
					if(obj_key[j] == obj[i])
					{
						tmp_key = ' _data="'+kk+',' + obj_key[j] +'" style="cursor:pointer;"';
						break;
					}
				}
				html += '<li class="list-li" ' + tmp_key  + '>' + obj[i] + '</li>';
			}
			html += '</ul>';
			$('#source-list').html(html);
		//	console.log(obj_data);
			$('.list-ul').find("li").each(function(){
				if($(this).attr('_data'))
				{
					$(this).mouseover(function(){
						$(this).attr('style','cursor:pointer;background-color:#DAD2C2;');
						console.log($(this).text());
						var tmp_key = $(this).text();
						var child_html = '<ul class="child-ul">';
						var child_length = obj_data[tmp_key].length;
						for(var m = 0 ;m < child_length;m++)
						{
							child_html += '<li class="child-li">' + obj_data[tmp_key][m] +'</li>';
						}
						child_html += '</ul>';
						$('#child-list').addClass("child-list").html(child_html);//
					});
					$(this).mouseout(function(){
						$(this).attr('style','cursor:pointer;background-color:#F0EFEF;');
						$('#child-list').removeClass("child-list").html('');						
					});
				}
			});
		}
		//console.log();
	});
});	
</script>

{template:foot}