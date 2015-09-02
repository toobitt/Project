<?php 
/* $Id: stream_form.php 2361 2011-10-28 09:56:50Z ayou $ */
?>
{template:head}
{css:ad_style}
{css:bigcolorpicker}
{css:member_form}
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
{code}//print_r($updatetype);{/code}
<script>
$.globaldefault = {code} echo json_encode($formdata['field_default']);{/code}
</script>
<style>
.important{color:red;}
.img-box{display: -webkit-box;}
.img-box img{width:50px;height:50px;margin-right: 10px;}
.img-upload-btn{width:50px;height:50px;border:1px solid #ccc;text-align: center;font-size:30px;color:#ccc;cursor:pointer;}
</style>
<script type="text/javascript">
$(function(){
	var MC = $('.img-box');
	
	MC.on('click' , '.img-upload-btn' , function( e ){
		var self = $( e.currentTarget );
		self.closest('.img-box').find('input[type="file"]').trigger('click');
	});

	MC.on('change' , 'input[type="file"]' , function( e ){
		var self = e.currentTarget,
	   		file = self.files[0],
	   		type = file.type;
		var reader=new FileReader();
		reader.onload=function(event){
			imgData=event.target.result;
			var box = $(self).closest('.img-box').find('.img'),
			img = box.find('img');
			!img[0] && (img = $('<img />').appendTo( box ));
			img.attr('src', imgData);
		}
    	reader.readAsDataURL( file );
	});
})
</script>
<div class="wrap clear">
<div class="ad_middle" style="width:850px">
<form name="editform" action="" method="post" enctype='multipart/form-data' class="ad_form h_l">
<h2>{$optext}心情</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div clear info">
		<span class="title">心情名称: </span>
		<input type="text" name="name"  value="{$name}" style="width:130px;" {if $is_sys}readonly="true"{/if}/>
	</div>
		<div class="form_ul_div clear info">
		<span class="title">心情标识: </span>
		<input type="text" name="qdxq"  value="{$qdxq}" style="width:80px;" {if $qdxq}readonly="true"{/if}/>
	</div>
	<div class="form_ul_div clear info">
		<span class="title">描述备注: </span><textarea name="description" id="description"  cols="45" rows="4" />{$description}</textarea>
	</div>
</li>
<li class="i icon">
	<div class="form_ul_div clear">
		<span class="title" >心情图标：</span>
			<div class="img-box">
				<div class="img">
					{if $img}
					{code}$img=hg_fetchimgurl($img);{/code}
						<img id="img" src="{$img}">
					{/if}
				</div>
				<input type="file" name="img"  style="display: none;" />
				<div class="img-upload-btn">+</div>
			</div>
			</div>
		</li>
</ul>

<input type="hidden" name="a" value="{$action}" />
<input type="hidden" name="is_del" id="is_del" value="0" />
<input type="hidden" name="{$primary_key}" value="{$formdata['id']}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<br />
<div class="temp-edit-buttons">
<input type="submit" name="sub" value="{$optext}" class="edit-button submit"/>
<input type="button" value="取消" class="edit-button cancel" onclick="javascript:history.go(-1);"/>
</div>
</form>
</div>
<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}

