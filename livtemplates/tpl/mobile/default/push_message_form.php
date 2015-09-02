<form action="run.php?mid={$_INPUT['mid']}&a=doinstantMessaging" method="post" enctype="multipart/form-data" class="ad_form h_l" onsubmit="return hg_ajax_submit('instantMessaging')" id="instantMessaging" name="instantMessaging">
	<ul class="form_ul clear send-info-inner">
		<li class="i item">
			<div class="form_ul_div clear">
			<span class="title"></span><textarea name="push_message" rows="10" cols="70" onfocus="if(this.value=='键入你需要发送的消息'){this.value=''};" onblur="if(this.value==''){this.value='键入你需要发送的消息'};">键入你需要发送的消息</textarea>
			</div>
		</li>
		<li class="i item">
			<div class="form_ul_div clear">
				<span class="title item-title">模块标识：</span><input style="width: 306px" type="text" name="link_module"/>
			</div>
		</li>
		<li class="i item">
			<div class="form_ul_div clear">
				<span class="title item-title">内容id：</span><input style="width: 306px" type="text" name="content_id"/>
			</div>
		</li>
	</ul>
	<input type="hidden" name="infrm" value="1" />
	<input type="hidden" name="device_token" value="{$formdata['device_token']}" />
	<br/>
	<input style="margin-right:20px;" type="submit" name="sub" value="确定" class="button_2"/>
	<input type="button" name="sub" onclick="$('#push_form').fadeOut()" value="返回" class="button_2"/>
</form>