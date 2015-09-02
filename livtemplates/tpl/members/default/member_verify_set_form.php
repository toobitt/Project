<?php 
/* $Id: stream_form.php 2361 2011-10-28 09:56:50Z ayou $ */
?>
{template:head}
{css:ad_style}
{css:bigcolorpicker}
{css:member_form}
{css:member_configuration}
{js:jqueryfn/jquery.tmpl.min}
{js:bigcolorpicker}
{js:area}

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
{code}//print_r($credits);{/code}
<script>
$.globaldefault = {code} echo json_encode($formdata['field_default']);{/code}
</script>

<style>
.important{color:red;}
.img-box{display: -webkit-box;}
.img-box img{width:50px;height:50px;margin-right: 10px;}
.img-upload-btn{width:50px;height:50px;border:1px solid #ccc;text-align: center;font-size:30px;color:#ccc;cursor:pointer;}
.img-box .img{position:relative;}
.img-box .img .del{display:block;width:15px;height:15px;line-height:15px;text-align:center;background:#629ee7;color:#fff;border-radius:50%;position:absolute;top:-5px;right:5px;cursor:pointer;display:none;}
.img-box .img:hover .del{display:block;}
</style>

<div class="wrap clear">
<div class="ad_middle" style="width:850px">
{if $formdata}
<form name="editform" action="" method="post" enctype='multipart/form-data' class="ad_form h_l">
<h2>实名认证配置</h2>
<ul class="form_ul">
<li class="i">
	<span class="basic-configuration">功能配置:</span>
	<div class="continuous-sign">
						<div class="item">
			<span class="showicon">显示认证图标:</span>
			<div class="configuration mar20">
				<div class="type">
					<input type="radio" name="showicon" class="on" value="1" id="yes" {if $showicon}checked{/if}/>
					<label for="yes">是</label>
				</div>
				<div class="type">
					<input type="radio" name="showicon" class="no" value="0" id="no" {if !$showicon}checked{/if}/>
					<label for="no">否</label>
				</div>
			</div>
		</div>
		<div id="is_showicon">
		<div class="form_ul_div clear">
				
		<span class="title" >已认证：</span>
			<div class="img-box">
				<div class="img">
					{if $icon}
					{code}$icon=hg_fetchimgurl($icon);{/code}
						<img  src="{$icon}">
					{/if}
					<span class="del">x</span>
				</div>
				<input type="hidden" name="icondel" />
				<input type="file" name="icon"  style="display: none;" />
				<div class="img-upload-btn">+</div>
			</div>
			</div>
			
				<div class="form_ul_div clear">
		<span class="title" >未认证：</span>
			<div class="img-box">
				<div class="img">
					{if $unverifyicon}
					{code}$unverifyicon=hg_fetchimgurl($unverifyicon);{/code}
						<img  src="{$unverifyicon}">
					{/if}
					<span class="del">x</span>
				</div>
				<input type="hidden" name="unverifyicondel" />
				<input type="file" name="unverifyicon"  style="display: none;" />
				<div class="img-upload-btn">+</div>
			</div>
			</div>
			
		</div>
		
	</div>
</li>
{if ($_configs['member_verify_field'])}
<li class="i">
	<span class="basic-configuration">可选资料项:</span>
	<div class="continuous-sign">
		<div style="display: -webkit-box;">
			<ul class="type-choose">
			{code}$field = @array_keys($field);{/code}
			{if is_array($_configs['member_verify_field'])}
			{foreach $_configs['member_verify_field'] as $key => $value}
		         {code}
		            $html_field = $value['field'];
			        $html_field_name = $value['field_name'];
			        $flag = 0;
			        if(is_array($field))
			        {
			            if (in_array($html_field,$field)) $flag=1;
			        }
			        else 
			        {
			            if($html_field == $field) $flag=1;
			        }
		         {/code}
		         <li><input type="checkbox" {if $flag} checked="checked"{/if} value="{$html_field}" size="50" name="field[]"/><span>{code}echo $html_field_name;{/code}</span></li>
	        {/foreach}
	        {/if}		
			</ul>
		</div>
	</div>
</li>
{/if}
</ul>
<input type="hidden" name="a" value="{$action}" />
<input type="hidden" name="is_del" id="is_del" value="0" />
<input type="hidden" name="{$primary_key}" value="{$formdata['id']}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<div class="temp-edit-buttons">
<input type="submit" name="sub" value="{$optext}" class="edit-button submit"/>
<input type="button" value="取消" class="edit-button cancel" onclick="javascript:history.go(-1);"/>
</div>
</form>
{else}
<div style="font-size:20px;color:red;padding: 30px;"> 没有数据 </div>
{/if}

</div>
<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}
<script type="text/javascript">
$(function(){
	var showicon = Number($('input[name="showicon"]:checked').val());
	if(showicon==0)
	{
		$('#is_showicon').toggle();
	}
		$('input[name="showicon"]').on('click' , function(){
			var checked = $('input[name="showicon"]:checked').val(),
				obj = $('#is_showicon');
			checked==0 ? obj.hide() :  obj.show();
		});

	
	var MC = $('.img-box');
	
	MC.on('click' , '.img-upload-btn' , function( e ){
		var self = $( e.currentTarget );
		self.closest('.img-box').find('input[type="file"]').trigger('click');
	});

	MC.on('change' , 'input[type="file"]' , function( e ){
		var self = e.currentTarget,
	   		file = self.files[0],
	   		type = file.type,
	   		box = $(self).closest('.img-box');
		var reader=new FileReader();
		reader.onload=function(event){
			imgData=event.target.result;
			var img = box.find('.img');
			!img[0] && (img = $('<div class="img"><img /><span class="del">x</span></div>').prependTo( box ));
			img.find('img').attr('src', imgData);
		}
    	reader.readAsDataURL( file );
    	box.find('input[type="hidden"]').val(0);
	});

	MC.on('click' , '.del' , function( event ){
		var self = $( event.currentTarget ),
			item = self.closest('.img-box');
			item.find('.img').remove();
			item.find('input[type="hidden"]').val(1);
	});
});
</script>

