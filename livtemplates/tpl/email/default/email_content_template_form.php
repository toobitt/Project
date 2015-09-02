<?php 
/* $Id: member_platform_form.php 33760 2014-10-14 06:16:50Z youzhenghuan $ */
?>
{if is_array($formdata)}
	{foreach $formdata AS $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
{template:head}
{css:ad_style}
{css:style}
<script type="text/javascript">
</script>
{if $a}
	{code}
/*	hg_pre($formdata);*/
		$action = $a;
		
		if (!$formdata['id'])
		{
			$action = 'create';
		}
	{/code}
{/if}

<style>
.important{color:red;}
.img-box{display: -webkit-box;}
.img-box img{width:50px;height:50px;margin-right: 10px;}
.img-upload-btn{width:50px;height:50px;border:1px solid #ccc;text-align: center;font-size:30px;color:#ccc;cursor:pointer;}

#form-edit-box{display:inline-block;}

/*编辑器图片管理功能暂时不用 css隐藏*/
.editor-current-img .img-indexpic{display:none;}
.pic-edit-btn{opacity:0;}
.editor-current-img:hover .img-option{display:none;}
.edit-slide-sort{display:none;}
</style>

<div class="ad_middle" style="min-height:740px;">
	<form name="editform" id="editform" action="./run.php?mid={$_INPUT['mid']}" method="post" enctype='multipart/form-data' class="ad_form h_l ueditor-outer-wrap">
		<h2>{$optext}内容模板</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div">
					<span class="title">应用名称:</span>
					<input type="text" name="name" value="{$name}" style="width:192px"/>
					<font class="important">必填</font>
				</div>
				<div class="form_ul_div">
					<span class="title">标识:</span>
					<input type="text" name="appuniqueid" value="{$appuniqueid}" />
					<font class="important">必填 唯一</font>
				</div>
				<div class="form_ul_div">
					<span class="title">邮件标题:</span>
					<input type="text" name="subject" value="{$subject}" style="width:400px"/>
					<font class="important">必填</font>
				</div>
				<div class="form_ul_div">
	        		<span class="title">邮件内容:</span>
	        	<textarea name="body" class="hide-textarea" id="form-edit-box">{code}echo htmlspecialchars_decode($body);{/code}</textarea>
	        	</div>

			</li>
		</ul>
		</br></br>
		<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14"/>
		<input type="hidden" name="a" value="{$action}" id="action" />
		<input type="hidden" name="id" value="{$formdata['id']}" />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	</form>
</div>
<div class="right_version">
	<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
		占位符使用规则:<br/>1.标题 例如：尊敬的{$tspace1}，这是一封{$tspace2}验证码邮件 ，必须使用 tspace 表单名使用数组形式提交至接口 
	数组格式：array('阿尤','找回密码')，系统会自动 拼接 {$tspace+ tspace数组下标} 寻找相应占位符并使用该数组下表进行替换
	<br/>2.内容：如邮件标题不同的是 传值表单名为 bspace ;占位符为 {$bspace+数字} 数字起始为0，例如：您的验证码是 {$bspace0} ,传值 array('112233')
</div>
{template:foot}

<script>
$(function(){
	var	init = function(){
		$.myueditor = $.m2oEditor.get( 'form-edit-box', {
			initialFrameWidth : 590,
			initialFrameHeight : 290,
			editorContentName : 'body',	//编辑器内容的name名
			slide : false,					//风格
			relyDom : '.m2o-inner',		//slide风格依赖dom（用于计算定位和高度）
			needCount : true,				//字数统计
			countDom : '#editor-count',		//字数统计dom
		} );
	};

	$.includeUEditor( init, {
		plugins : ['imgmanage']
	});
})
</script>