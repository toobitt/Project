
<script type="text/javascript">
  var vs = hg_get_cookie('video_subinfo');
  $(document).ready(function(){
	$('#video_subinfo').css('display',vs?vs:'block');
  });
</script>
{if $formdata['id']}
<div class="info clear vider_s"  id="vodplayer_{$formdata['id']}">
   
  <div id="vodPlayer" class="content-stand" style="width:400px;height:300px;">
  {if $formdata['url1']}
	<img src="{$formdata['url1']}" alt="缩略图"/>
  {/if}
  </div>
   
  <span onclick="hg_close_opration_info();" title="关闭/ALT+Q"></span>
</div>
<div class="info clear cz">
	<ul id="video_opration" class="clear" style="border:0;">
		<li>
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$formdata['id']}&infrm=1">编辑</a>
		</li>
		<li>
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$formdata['id']}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>
		</li>
		<li>
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=audit&id={$formdata['id']}" onclick="return hg_ajax_post(this, '审核', 0);">审核</a>
		</li>
		<li>
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=back&id={$formdata['id']}" onclick="return hg_ajax_post(this, '打回', 0);">打回</a>
		</li>
		<li>
			<!-- <a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=recommend&id={$formdata['id']}" onclick="return hg_ajax_post(this, '推荐', 0);">发布</a> -->
		</li>

	</ul>
</div>
<div class="info clear vo">
	<h4 onclick="hg_slide_up(this,'video_subinfo')"><span title="展开\收缩"></span>杂志属性</h4>
	<ul id="video_subinfo" class="clear">
		<li class="h"><span>杂志名称：{$formdata['name']}</span></li>
		<li class="h"><span>来源：{$formdata['source']}</span></li>
		<li class="h"><span>创建者：{$formdata['user_name']}</span></li>
		<li class="h"><span>状态：{if $formdata['state']}已审核{else}未审核{/if}</span></li>
		<li class="h"><span>是否置顶：{if $formdata['istop']}是{else}否{/if}</span></li>
        <li class="h"><span>排序：{$formdata['order_id']}</span></li>
		<li class="h"><span>是否独立模板：{if $formdata['istpl']}是{else}否{/if}</span></li>
		<li class="h"><span>模板文件名：{$formdata['tpl_file']}</span></li>
		<li class="h"><span>点击次数：{$formdata['click_num']}次</span></li>
		<li class="h"><span>评论次数：{$formdata['comm_num']}次</span></li>
		<li class="h"><span>创建者IP：{$formdata['ip']}</span></li>
		<li class="h"><span>创建时间：{$formdata['create_time']}</span></li>
		<li class="h"><span>更新时间：{$formdata['update_time']}</span></li>
		<li class="h"><span>发布时间：{$formdata['pub_date']}</span></li>
		{if $default[$formdata['id']]}
			<li class="w"><span>发布至：</span>
			{if $default[$formdata['id']][1]}
			<div class="earth"></div>
				 {foreach $default[$formdata['id']][1] as $v}
				 <span>{$v}</span>
				 {/foreach}
			{/if}
			{if $default[$formdata['id']][2]}
			<div class="iphone"></div>
				 {foreach $default[$formdata['id']][2] as $v}
				 <span>{$v}</span>
				 {/foreach}
			{/if}
			</li>
		{/if}
	</ul>
</div>
{else}
<h1>此杂志已经不存在,请刷新页面更新!</h1>
{/if}