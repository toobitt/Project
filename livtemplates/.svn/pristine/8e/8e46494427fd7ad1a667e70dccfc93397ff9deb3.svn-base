<script type="text/javascript">
  /*var tp_id = "{$formdata['id']}";*/
  var vs = hg_get_cookie('video_subinfo');
  $(document).ready(function(){
	$('#video_subinfo').css('display',vs?vs:'block');
  });
</script>
{code}
	
{/code}
{if $formdata['group_id']}
<div class="info clear vider_s"  id="vodplayer_{$formdata['group_id']}">
   
  <div id="vodPlayer" style="width:400px;height:300px;">
  {if $formdata['logo']}
	<img src="{$formdata['logo']}" alt="缩略图"/>
  {/if}
  </div>
   
  <span onclick="hg_close_opration_info();" title="关闭/ALT+Q"></span>
</div>
<!--<div class="info clear cz">
	<ul id="video_opration" class="clear" style="border:0;">
		<li>
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=audit&id={$formdata['id']}" onclick="return hg_ajax_post(this, '审核', 0);">审核</a>
		</li>
		<li>
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=back&id={$formdata['id']}" onclick="return hg_ajax_post(this, '打回', 0);">打回</a>
		</li>
		<li><a class="button_6" href="./run.php?mid={$_INPUT['mid']}&a=recommend&id={$formdata['id']}" onclick="return hg_ajax_post(this, '推荐', 0);">发布至网站</a></li>
	</ul>
</div>-->
<div class="info clear vo">
	<h4 onclick="hg_slide_up(this,'video_subinfo')"><span title="展开\收缩"></span>地盘详情</h4>
	<ul id="video_subinfo" class="clear">
		<li class="h"><span>地盘名称：{$formdata['name']}</span></li>
		<li class="h"><span>地盘类型：{$formdata['type_name']}</span></li>
		<li class="h"><span>创建时间：{$formdata['create_time']}</span></li>
		<li class="h"><span>更新时间：{$formdata['update_time']}</span></li>
		{if $formdata['description']}<li class="w"><span>描述：{$formdata['description']}</span></li>{/if}
		{if $formdata['group_addr']}<li class="w"><span>描述：{$formdata['group_addr']}</span></li>{/if}
	</ul>
</div>
{else}
此视频已经不存在,请刷新页面更新
{/if}