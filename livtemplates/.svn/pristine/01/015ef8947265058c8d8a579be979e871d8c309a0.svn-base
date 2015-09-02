{template:head}
{js:common/common_form}
{js:switch_radio}
{css:ad_style}
{css:column_node}
{css:common/common}
{css:common/common_form}
{css:2013/form}
{css:site_form}
<script type="text/javascript">
$(function(){
	$('input[name="weburl"]').blur(function(){
		var domain=$(this).val();
		$('input[name="domain"]').val(domain);
	});
});

	/*$('.weburl').blur(function(){
    var weburl=$(this).val();
    var url= './run.php?mid='+gMid+'&a=web_confirm';
    alert(url);
    $.get(url,{domain:weburl},function(data){
        var data = data[0];
        alert(data.error);
   });
   });*/


function mod_hidden_show(self,id)
{
    $(self).addClass('on').siblings().removeClass('on');
	$('#basic_info,#video_set').css('display','none')
	$('#'+id).css('display','');
}
function delete_site(id)
{
	window.location.href = "?mid={$_INPUT['mid']}&a=delete&infrm={$_INPUT['infrm']}&site_id="+id;
}
function video_record()
{
	if($('input[name=is_video_record]:checked').val()==1)
	{
		$('#video_record_count,#video_update_peri,#video_record_url,#video_record_filename').attr('disabled',false);
	}
	else
	{
		$('#video_record_count,#video_update_peri,#video_record_url,#video_record_filename').attr({disabled:true});
	}
}
function check_domain()
{
	var site_id = $('#site_id').val();
	var weburl = $('#weburl').val();
	//var sub_weburl = $('#sub_weburl').val();
	var site_dir = $('#site_dir').val();
	if(weburl&&site_id)
	{
		var url= "./run.php?mid=" + gMid + "&a=check_domain";
    	$.ajax({
		type:'get',
		url:url,
		data:{
			weburl 	: weburl,
			site_id : site_id,
		},
		dataType:'Json',
		success:function(msg){
			if(msg!=1)
			{
				alert('该域名已存在');
				$('input[name="weburl"]').val('');
			}
		},
		error:function(){
		
		}
		})
	}
}



</script>
{code}
	$site_form[0] = $formdata;
{/code}

<div id="hg_page_menu" class="head_op_program">
	<a href="?mid={$_INPUT['mid']}&a=site_form&infrm={$_INPUT['infrm']}" target="formwin" class="button_6" style="font-weight:bold;">新增站点</a>
</div>
<form action="" method="post">
	<header class="m2o-header">
	  <div class="m2o-inner">
        <div class="m2o-title m2o-flex m2o-flex-center">
            <h1 class="m2o-l">{if $site_form[0]['site']['id']}编辑站点{else}新增站点{/if}</h1>
            <div class="m2o-m m2o-flex-one">
                <input placeholder="填写站点名称" name='site_name' class="m2o-m-title" value="{$site_form[0]['site']['site_name']}" />
            </div>
            <div class="m2o-btn m2o-r">
                <input type="submit" value="保存" class="m2o-save" name="sub" id="sub" />
                <span class="option-iframe-back m2o-close"></span>
            </div>
        </div>
      </div>
    </header>
    <div class="m2o-inner">
    <div class="m2o-main m2o-flex">
    	<aside class="m2o-l">
    	 <div class="m2o-item">
    	        <span class="title" style="float:left;">网站关键字:</span>
	    	 	<div class="form-dioption-keyword form-dioption-item clearfix" style="position:relative;">
	                <span class="keywords-del">添加关键字</span>
	                <span class="form-item" _value="添加关键字" id="keywords-box">
	                    <span class="keywords-start">添加关键字</span>
	                    <span class="keywords-add">+</span>
	                </span>
	                <input name="keywords" value="{$site_form[0]['site']['site_keywords']}" id="keywords" style="display:none;"/>
	            </div>
            </div>
            
   			<div class="m2o-item">
        	        <span class="title">网站描述:</span>
        	        <textarea rows="3" cols="80" name="content" placeholder="添加页面描述" >{$site_form[0]['site']['content']}</textarea>
   			</div>
   			<div class="m2o-item">
        	        <span class="title ">网站域名:</span>
        	        <input type="text" onblur="check_domain()" value="{$site_form[0]['site']['weburl']}" name='weburl' id="weburl" placeholder="添加网站域名 如:hoge.cn" />
   			</div>
   			<div class="m2o-item">
        	        <span class="title">发布目录:</span>
        	        <input type="text" value="{$site_form[0]['site']['site_dir']}" name='site_dir' placeholder="添加网站关键字 多个关键字请用逗号隔开" />
   			</div>
   			<div class="m2o-item">
			<span class="title">生成方式:</span>
			<select name='produce_format' value="{$site_form[0]['site']['produce_format']}">
				{foreach $_configs['site_produce_format'] as $k=>$v}
				<option value="{$k}" {code}if($site_form[0]['site']['produce_format']==$k) echo "selected";{/code}>
					{$v}
				</option>
				{/foreach}
			</select>
			<select name='suffix' value="{$site_form[0]['site']['suffix']}">
				{foreach $_configs['site_suffix'] as $k=>$v}
				<option value="{$k}" {code}if($site_form[0]['site']['suffix']==$k) echo "selected";{/code}>
					{$v}
				</option>
				{/foreach}
			</select>
			<font class="important" style="color:red">*</font>
			<span class="site_fill_tip">
			</span>
		</div>
    	</aside>
    	<section class="m2o-m m2o-flex-one">
			<div class="m2o-item">
				<a href="javascript:void(0)" onclick="mod_hidden_show(this,'basic_info')" class="m2o-channel-btn on">基本信息 </a>
				<a href="javascript:void(0)" onclick="mod_hidden_show(this,'video_set')" class="m2o-channel-btn">百度视频收录 </a>
			</div>
			<div id="basic_info">
				<div class="m2o-item site-form-title">
					<span  class="title" >访问域名:</span>
					<input type="text" value="{$site_form[0]['site']['sub_weburl']}" name='sub_weburl'>
					<input class="url-domain" name="domain" disabled="disabled" value="{$site_form[0]['site']['weburl']}" style="width:50px!important;">
					<span  class="title" style="width:50px;">目录:</span>
					<input type="text" value="{$site_form[0]['site']['sub_wdir']}" name='sub_wdir' >
					<span  class="title" style="width:50px;">文件名:</span>
					<input type="text" value="{if $site_form[0]['site']['indexname']}{$site_form[0]['site']['indexname']}{else}index{/if}" name='indexname'>
				</div>
				<!--<div class="m2o-item">
					<span class="title">域名:</span>
					<input type="text" onchange="check_domain()" value="{$site_form[0]['site']['weburl']}" name='weburl' id="weburl">
					<font class="important" style="color:red">*</font>
					<span class="site_fill_tip">
					您网站的网址,不包含www,如:www.hogesoft.com只需要填写:hogesoft.com
					</span>
				</div>
				<div class="m2o-item">
					<span class="title">子域名:</span>
					<input type="text" onchange="check_domain()" value="{$site_form[0]['site']['sub_weburl']}" name='sub_weburl' id="sub_weburl">
					<font class="important" style="color:red">*</font>
					<span class="site_fill_tip">
					您网站的子域名。如:www.hogesoft.com只需要填写:www.
					</span>
				</div>
				<div class="m2o-item">
					<span class="title">子域名目录:</span>
					<input type="text" onchange="check_domain()" value="{$site_form[0]['site']['sub_wdir']}" name='sub_wdir' id="sub_wdir">
					<font class="important" style="color:red">*</font>
					<span class="site_fill_tip">
					</span>
				</div>
				<div class="m2o-item">
					<span class="title">首页的文件名:</span>
					<input type="text" value="{$site_form[0]['site']['indexname']}" name='indexname'>.html
					<font class="important" style="color:red">*</font>
					<span class="site_fill_tip">
					不用添加扩展名.
					</span>
				</div>-->
				<div class="m2o-item">
					<span class="title">内容生成目录:</span>
					<input type="text" value="{$site_form[0]['site']['custom_content_dir']}" name='custom_content_dir'>
					<span class="site_fill_tip">
					一旦设定，所有内容将按照指定它格式生成在此目录下；若栏目名用子域名，则在栏目下建立在此目录存放；不设定则存储在栏目目录下
					</span>
				</div>
				<!--<div class="m2o-item">
					<span class="title">内容生成域名:</span>
					<input type="text" value="{$site_form[0]['site']['custom_content_url']}" name='custom_content_url'>
					<font class="important" style="color:red">*</font>
					<span class="site_fill_tip">
					需要填写 http:// 如:http:tmp1.xxx.com
					</span>
				</div>-->
				<div class="m2o-item">
					<span class="title">页面素材访问域名:</span>
					<input type="text" value="{$site_form[0]['site']['tem_material_url']}" name='tem_material_url'>
					<font class="important" style="color:red">*</font>
					<input class="url-domain" name="domain" disabled="disabled" value="{$site_form[0]['site']['weburl']}">
					<span class="site_fill_tip">
					生成后，模板中的素材存放目录
					</span>
				</div>
				<div class="m2o-item">
					<span class="title">页面素材目录:</span>
					<input type="text" value="{$site_form[0]['site']['tem_material_dir']}" name='tem_material_dir'>
					<font class="important" style="color:red">*</font>
					<span class="site_fill_tip">
					不用添加扩展名.
					</span>
				</div>
				<div class="m2o-item">
					<span class="title">动态程序目录:</span>
					<input type="text" value="{if $site_form[0]['site']['program_dir']}{$site_form[0]['site']['program_dir']}{else}m2o{/if}" name='program_dir'>
					<font class="important" style="color:red">*</font>
					<span class="site_fill_tip">
					</span>
				</div>
				<div class="m2o-item">
					<span class="title">动态程序访问域名:</span>
					<input type="text" value="{$site_form[0]['site']['program_url']}" name='program_url'>
					<font class="important" style="color:red">*</font>
					<input class="url-domain" name="domain" disabled="disabled" value="{$site_form[0]['site']['weburl']}">
				</div>
				<!--<div class="m2o-item">
					<span class="title">js调用文件存放目录:</span>
					<input type="text" value="{$site_form[0]['site']['jsphpdir']}" name='jsphpdir'>
					<font class="important" style="color:red">*</font>
					<span class="site_fill_tip">
					</span>
				</div>
				<div class="m2o-item">
					<span class="title">js调用文件访问域名:</span>
					<input type="text" value="{$site_form[0]['site']['jsphpurl']}" name='jsphpurl'>
					<font class="important" style="color:red">*</font>
					<span class="site_fill_tip">
					需要填写 http:// 如:http:site.xxx.com
					</span>
				</div>-->
			</div>
			<div id="video_set" style="display:none;">
				<div class="m2o-item">
					<span class="title">用户email:</span>
					<input type=text name="user_email" id="user_email" value="{$site_form[0]['site']['user_email']}">
					<span class="site_fill_tip">
					</span>
				</div>
				<div class="m2o-item">
					<span class="title">百度视频收录:</span>
					<div class="common-switch {if $site_form[0]['site']['is_video_record']}common-switch-on{/if}">
				       <div class="switch-item switch-left" data-number="0"></div>
				       <div class="switch-slide"></div>
				       <div class="switch-item switch-right" data-number="100"></div>
				    </div>
					<input type=radio name="is_video_record" onclick="video_record()" value=1 {if $site_form[0]['site']['is_video_record']}checked{/if} style="display:none;">
					<input type=radio name="is_video_record" onclick="video_record()" value=0 {if !$site_form[0]['site']['is_video_record']}checked{/if} style="display:none;">
				</div>
				<div class="m2o-item">
					<span class="title">收录周期(分钟):</span>
					<input type=text name="video_update_peri" id="video_update_peri" value="{if $site_form[0]['site']['is_video_record']}{$site_form[0]['site']['video_update_peri']}{/if}" {if !$site_form[0]['site']['is_video_record']}disabled{/if}>
					<span class="site_fill_tip">
					搜索引擎将遵照此周期访问该页面
					</span>
				</div>
				<div class="m2o-item">
					<span class="title">每次取收录条数:</span>
					<input type=text name="video_record_count" id="video_record_count" value="{if $site_form[0]['site']['is_video_record']}{$site_form[0]['site']['video_record_count']}{/if}" {if !$site_form[0]['site']['is_video_record']}disabled{/if}>
					<span class="site_fill_tip">
					</span>
				</div>
				<div class="m2o-item">
					<span class="title">视频收录XML目录:</span>
					<input type=text name="video_record_url" id="video_record_url" value="{$site_form[0]['site']['video_record_url']}" style="width:200px;">
					<span class="site_fill_tip">
					</span>
				</div>
				<div class="m2o-item">
					<span class="title">视频收录XML文件名:</span>
					<input type=text name="video_record_filename" id="video_record_filename" value="{$site_form[0]['site']['video_record_filename']}" style="width:100px;">
					<span class="site_fill_tip">
					</span>
				</div>
			</div>	
    	</section>
    </div>
   </div>
    <input type="hidden" name="a" value="create_update" />
	<input type="hidden" id="site_id" name="site_id" value="{$site_form[0]['site']['id']}" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
</form>
{template:foot}