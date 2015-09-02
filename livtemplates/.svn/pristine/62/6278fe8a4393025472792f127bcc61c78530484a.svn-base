{template:head}
{css:ad_style}
{css:column_node}
{js:interview}
<style type="text/css">
.source_item {cursor:pointer; border:1px solid #CCC; display:inline-block; padding:3px 5px; margin:5px;}
</style>
{code}
	$info = $formdata;
	if( $info ){$optext = '修改';}else{$optext = '新增';}
{/code}
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form 
			action="run.php?mid={$_INPUT['mid']}&infrm=1&nav=1" 
			method="post" enctype="multipart/form-data" class="ad_form h_l">
				
				<input type="hidden" value="{$info['id']}" name='id' style="width:440px;">
				<h2>{$optext}人物信息</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">人物名称：</span>
								<input type="text" value="{$info['name']}" name='name' style="width:440px;">
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">审核状态：</span>
					
								<input type="radio" name="type" value="2"  {if  $info['type']=='2'}checked="checked"{/if}/> 导演
								<input type="radio" name="type" value="1"  {if  $info['type']=='1'}checked="checked"{/if}/> 演员
							</div>
						</li>
						{code}
							if( $info )
							{
						{/code}
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">参演作品：</span>
								<input type="text" value="{$info['film_work_names']}" style="width:440px;" readonly>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">作品数量：</span>
								<input type="text" value="{$info['film_num']}" style="width:440px;" readonly>
							</div>
						</li>
						{code}
							}
						{/code}
					</ul>
				<br />
				<input type="hidden" name="a" value="{code}if( $formdata['id'] ){echo 'person_update';}else{echo 'person_create';}{/code}" />
				<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}
