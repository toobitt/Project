<ul class="form_ul" style="margin-bottom:50px;">
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">&nbsp;&nbsp;&nbsp;视频类型：</span>
			{foreach $settings['base']['video_type'] AS $k => $v}
			<input type="text" value="{$settings['base']['video_type'][$k]}" name='base[video_type][{$k}]' style="width:50px;" />
			{/foreach}
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i" style="margin-top:30px;margin-bottom:120px;">
		<div class="form_ul_div">
			<span  class="title">&nbsp;&nbsp;&nbsp;索贝签发：</span>
			<label>是否打开</label>
			<input type="text" value="{$settings['base']['App_suobei']['is_open']}" name='base[video_type][is_open]' style="width:50px;" /><br/>
			<label style="margin-left:85px;">ftp配置</label>
			<input type="text" value="{$settings['base']['App_suobei']['ftp']['host']}" name='base[video_type][ftp][host]' style="width:100px;" />
			<input type="text" value="{$settings['base']['App_suobei']['ftp']['username']}" name='base[video_type][ftp][username]' style="width:100px;" />
			<input type="text" value="{$settings['base']['App_suobei']['ftp']['password']}" name='base[video_type][ftp][password]' style="width:100px;" /><br/>
			<label style="margin-left:85px;">display_name</label>
			<input type="text" value="{$settings['base']['App_suobei']['display_name']}" name='base[video_type][display_name]' style="width:100px;" /><br/>
			<label style="margin-left:85px;">xmldir</label>
			<input type="text" value="{$settings['base']['App_suobei']['xmldir']}" name='base[video_type][xmldir]' style="width:100px;" /><br/>
			<label style="margin-left:85px;">xmlpath</label>
			<input type="text" value="{$settings['base']['App_suobei']['xmlpath']}" name='base[video_type][xmlpath]' style="width:100px;" /><br/>
			<font class="important" style="color:red"></font>
		</div>
	</li>
</ul>