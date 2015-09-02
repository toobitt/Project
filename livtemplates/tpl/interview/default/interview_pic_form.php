{template:head}
{css:ad_style}
{css:column_node}
{js:interview_pic}
<style>
.option_del_box{display:inline-block;width:16px;height:16px;cursor:pointer;float:right;position: relative;right: 180px;top: 4px;}
.option_del{display:none;width:16px;height:16px;cursor:pointer;float:right;background: url('{$RESOURCE_URL}close_plan.png') 0 0 no-repeat;}
.option_del_b{width:16px;height:16px;cursor:pointer;float:right;position: relative;right: 160px;top: 4px;background: url('{$RESOURCE_URL}close_plan.png') 0 0 no-repeat;}
</style>
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
				{if $formdata['id']}
				<h2>修改图片信息</h2>
				
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">图片名称：</span>
								<input type="text" value="{$formdata['name']}" name="title" id="title">
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">图片地址：</span>
								<input type="file" name="Filedata" onchange="picChange()" id="Filedata"/>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">显示位置：</span>
								<input type="radio" name="show_pos" value="0"  {if  $formdata['show_pos']==0}checked="checked"{/if}/> 头部
								<input type="radio" name="show_pos" value="2"  {if  $formdata['show_pos']==2}checked="checked"{/if}/> 其他
								
							</div>
						</li>
				
						<li class="i">
							<div class="form_ul_div clear">
								<span><font color='red'>*</font>为必填选项</span>
							</div>
						</li>
					</ul>
					{else}
					<h2>上传图片</h2>
					<ul class="form_ul">
						<li class="i">
							<div  id="addfile">
								<div class='form_ul_div clear'>
									<span class='title'>图片名：</span>
									<input type='text' style='width:150px;' id='upload1_name'  name='upload1_name'/>
									<input type='file' id='upload1' onchange='uploadChange(this.id)' name='uploadinput1' style='width: 64px;height:24px;line-height:24px;'/>
									<input type='hidden' name='picid[]' value='1'/>
									</div>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span onclick="hg_addFileDom();" style="cursor:pointer;padding: 5px 20px;margin-left: 75px;background-color: #5B5B5B;color: white;border-radius: 2px;" type="text">继续上传</span>
							</div>
						</li>
					</ul>
					{/if}
				<input type="hidden" name="kid" value="{$formdata['kid']}" />
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}
