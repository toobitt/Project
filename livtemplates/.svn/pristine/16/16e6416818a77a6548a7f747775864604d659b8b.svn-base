{if $channel_info}
<style type="text/css">
#channel_select {height:34px; line-height:34px; background:#414040; text-align:center;}
#channel_select li {display:inline-block; color:#FFF; width:20px; height:20px; text-align:center; line-height:20px; background:#505050; margin:7px 5px 0; cursor:pointer;}
#channel_select li.selected {background:#5798d0; cursor:default;}
</style>
<div class="video">
<script type="text/javascript">
if (!ISIOS && !ISANDROID)
{
	{code}$url = $channel_info[0]['streams'][0]['stream_uri'];{/code}
	$(function(){
		setSwfPlay('flashBox', "{$url}", '290', '219', 100, 'flashBox');
		$('#channel_select li.selectBtn:first').addClass('selected');
		$('#channel_select li.selectBtn').click(function() {
			$('#channel_select li.selectBtn').removeClass('selected');
			$(this).addClass('selected');
			var url = $(this).attr('uri');
			document.getElementById('flashBox_1').setUrl(url);
		});
	});
}
</script>
<div id="flashBox"></div>
<ul id="channel_select">
	<li id="channel_select_left"><span>&lt;</span></li>
	{code}unset($channel_info['server_info']);{/code}
	{foreach $channel_info as $k=>$v}
	{code}$k++;{/code}
	{code}$uri = $v['streams'][0]['stream_uri'];{/code}
	<li title="{$v[name]}" uri="{$uri}" class="selectBtn">{$k}</li>
	{/foreach}
	<li id="channel_select_right"><span>&gt;</span></li>
</ul>
<div class="clearfix"></div>
</div>
{/if}
<div class="run-info common-r-area">
    <h3>运行信息</h3>
    <ul class="clearfix">
    {if $index_live}
    	{if $index_live['live_status'] == 200}
    	<li class="true">直播正常</li>
    	{else}
    	<li class="false">直播异常</li>
    	{/if}
    {/if}
    {if $index_program_record}
    	{if $index_program_record['record_status'] == 200}
    	<li class="true">录制正常</li>
    	{else}
    	<li class="false">录制异常</li>
    	{/if}
    {/if}
    {if $index_livmedia}
    	{if $index_livmedia['upload_status'] == 200}
    	<li class="true">视频上传正常</li>
    	{else}
    	<li class="false">视频上传异常</li>
    	{/if}
    	{if $index_livmedia['trans_status'] == 200}
    	<li class="true">视频转码正常</li>
    	{else}
    	<li class="false">视频转码异常</li>
    	{/if}
    {/if}
    </ul>
</div>
<style type="text/css">
.layer_mask {position:absolute; left:0; top:0; z_index:999; background:#999; filter:alpha(opacity=50); -moz-opacity:0.5; opacity:0.5;}
.popup {position:absolute; z_index:9999; background:#FFF; width:500px; height:300px; overflow:hidden; border:1px solid #999;}
.popup h3 {height:35px; line-height:35px; font-size:14px; background:#EEE; padding:0 10px;}
.popup h3 span.closeMenuBtn {float:right; cursor:pointer; width:35px; text-align:center;}
.popup div.conWrap {padding:10px; height:245px; overflow:auto;}
.popup div.conWrap .show_data td,.popup div.conWrap .show_data th {text-align:center; border-bottom:1px solid #EEE;}
.popup div.conWrap .show_data th {font-weight:bold; padding:5px 0;}
.popup div.conWrap .show_data td {padding:5px;}
.popup div.conWrap .op_nav {margin-top:5px; /*padding:5px;*/ height:40px; line-height:40px; padding-left:6px;}
.popup div.conWrap .op_nav span {cursor:pointer;}
.popup div.conWrap .show_data .op_btn {cursor:pointer;}

#addMenuForm h1 {font-size:14px; font-weight:bold; margin-bottom:10px;}
#addMenuForm p {margin:10px 0;}
.addMenuBtn {text-align:center; padding:5px;}
.addMenuBtn span {cursor:pointer; border:1px solid #CCC; border-radius:2px; display:inline-block; padding:5px; background:#EEE;}
</style>
<div class="express-controll common-r-area">
    <h3><span>{if $_user['group_type'] == 1}<a href="javascript:;" id="setting_link">设置</a>{/if}<a href="javascript:;" id="select_link">自定义</a></span>快捷操作</h3>
    <ul class="clearfix" id="myCustomMenu">
    {if $menu_info}
    	{foreach $menu_info as $menu}
        <li id="menuList_{$menu['id']}"><a href="{$menu['menu_link']}">{$menu['menu_name']}</a></li>
        {/foreach}
    {/if}
    </ul>
    {if !$menu_info}
    <p class="addMenuBtn"><span>添加快捷操作</span></p>
    {/if}
</div>