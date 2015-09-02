{template:head}
{css:ad_style}
{css:column_node}

<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
				<ul class="form_ul">
					<li class="i">
						<div class="form_ul_div">
							<span class="title">样式图片文件：</span>
							<input type="file" name="Filedata" id="Filedata" value="submit"/>
							<font class="important">可上传图片格式以及zip压缩包</font>      
							<font class="important" style="color:red">*</font>
						</div>
					</li>
				</ul>
				<input type="hidden" name="a" value="upload_update" />
				<input type="hidden" name="site_id" value="{$_INPUT['site_id']}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<input type="hidden" name="html" value="true" />
				<br />
				<input type="submit" name="sub" value="确定" class="button_6_14"/>
				<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
<script type="text/javascript">
$(function ($) {
	$("#Filedata").on('change', function (e) {	
		var file = this.files[0];
		if ( !/.+\.(jpeg|gif|bmp|jpg|zip)$/.exec(file.name) ) {
			alert('文件格式不对');
			this.value = '';
		}
	});
});
</script>
{template:foot}