<?php
/* $Id: stream_form.php 2361 2011-10-28 09:56:50Z ayou $ */
?>
<?php ?>
{template:head}
{css:2013/form}
{css:2013/button} 
{css:hg_sort_box}
{css:medal_manage_form} 
{js:hg_preview} 
{js:hg_sort_box}
{js:ajax_upload} 
{js:common/common_form} 
{js:2013/ajaxload_new}
{js:members/medal_manage_form} 
{code} 
if ( is_array($formdata ) ) 
{
foreach ( $formdata as $k => $v ) 
{ $$k = $v; 
} 
} 
if($id) {
$optext="更新";
$ac="update";
 } 
 else 
 { 
 $optext="添加"; 
 $ac="create"; 
 }
$currentSort[$sort_id] = ($sort_id ? $sort_name : '选择分类'); $markswf_url
= RESOURCE_URL.'swf/'; {/code}
<body>
	<form class="m2o-form" name="editform"
		action="run.php?mid={$_INPUT['mid']}" method="post"
		enctype="multipart/form-data" id="tv_interact_form" data-id="{$id}">
		{template:unit/bg_picture}
		<header class="m2o-header">
		<div class="m2o-inner">
			<div class="m2o-title m2o-flex m2o-flex-center">
				<h1 class="m2o-l">{$optext}勋章</h1>
				<div class="m2o-m m2o-flex-one">
					<input placeholder="输入勋章名称" name="name"
						class="m2o-m-title need-word-count" title="{$name}" required
						value="{$name}" /> <input type="hidden" name="old_name"
						value="{$name}" />
				</div>
				<div class="m2o-btn m2o-r">
					<input type="submit" value="保存勋章" class="m2o-save" name="sub"
						id="sub" data-target="run.php?mid={$_INPUT['mid']}&a={$ac}"
						data-method="{$ac}" /> <span class="m2o-close option-iframe-back"></span>
					<input type="hidden" name="a" value="{$ac}" /> <input type="hidden"
						name="{$primary_key}" value="{$$primary_key}" /> <input
						type="hidden" name="referto" value="{$_INPUT['referto']}" /> <input
						type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				</div>
			</div>
		</div>
		</header>
		<div class="m2o-inner">
			<div class="m2o-main m2o-flex">
				<aside class="m2o-l m2o-aside">
				<div class="m2o-item img-info" style="position: relative">
					<div class="indexpic icon {if !$formdata['image_url']}icons{/if}">
						<img src="{$image_url}" /> <span
							class="indexpic-suoyin {if $formdata['image_url']}indexpic-suoyin-current{/if}"></span>
					</div>
					<input type="file" name="image" style="display: none;"
						class="file" id="photo-file" />
				</div>
				<label class="title">Tips：<br/>1.为保持勋章美观，请自行处理图片尺寸，建议使用不超过80*80图标<br/>2.总限制量和日期限制对管理员无效，但管理员发放勋章会计入发放数量</label>
				</aside>
				<section class="m2o-m m2o-flex-one">
				<div class="basic-info">
					<div class="m2o-item tv-info">
						<a class="tv-title active" data-type="basic">基本信息</a>
					</div>
					<div class="m2o-item cut-off">
						<label class="title">描述简介: </label>
						<textarea class="brief" name="brief" cols="120" rows="5"
							placeholder="描述简介">{$brief}</textarea>
					</div>
					<div class="m2o-item cut-off">
						<label class="title">日期限制: </label>
						<div class="info">
							<div class="info-switch">
								<div class="switch">
									<input type="radio" name="is_award_time" value=1
										{if $is_award_time}checked="checked" {/if}/>
									<p>开启</p>
								</div>
								<div class="switch">
									<input type="text" name="start_date" class="date-picker"
										value="{if $formdata['start_date'] && $formdata['start_date'] !=0 }{$formdata['start_date']}{/if}" />
									<span>至</span> 
									<input type="text" name="end_date" class="date-picker" 
										value="{if $formdata['end_date'] && $formdata['end_date'] !=0 }{$formdata['end_date']}{/if}" />
								</div>
								<div class="switch">
									<input type="radio" name="is_award_time" value=0
										{if !$is_award_time}checked="checked" {/if}/>
									<p>关闭</p>
								</div>
							</div>
						</div>
					</div>
					<div class="m2o-item cut-off">
						<label class="title">总量限制: </label>
						<div class="info">
							<input type="number" min='0' name="limit_num"
								value="{$limit_num}" class="value-verify w100"
								placeholder="0为不限制,单位:枚" />
						</div>
					</div>
					<div class="m2o-item cut-off">
						<label class="title">使用期限: </label>
						<div class="info">
							<input type="number" min='0' name="expiration"
								value="{$expiration}" class="value-verify w100"
								placeholder="0为不限制;单位:天" />
						</div>
					</div>
					<div class="m2o-item cut-off">
						<label class="title">发放方式: </label>
						<div class="select">
							<select name="medal_type">
								{foreach $_configs['medal_type'] as $k => $v}
								<option {if $type == $k}selected="selected"{/if} value={$k}>{$v}</option>
								{/foreach}
							</select>
						</div>
					</div>

				</div>
				</section>
			</div>
	
	</form>
</body>
