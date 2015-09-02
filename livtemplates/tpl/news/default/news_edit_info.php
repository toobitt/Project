<script type="text/javascript">
  /*var tp_id = "{$formdata['id']}";*/
  var vs = hg_get_cookie('video_subinfo');
  $(document).ready(function(){
	$('#video_subinfo').css('display',vs?vs:'block');
  });
</script>
{code}
$eidt_a_name = $formdata['outlink'] ? 'form_outerlink' : 'form';
{/code}
{if $formdata['id']}
<div class="info clear vider_s"  id="vodplayer_{$formdata['id']}">
   
  {if $formdata['url']}
  <div id="vodPlayer" class="content-stand">
	<img src="{$formdata['url']}" alt="缩略图"/>
  </div>
  {else}
  <div class="info clear pd20">暂无索引图</div>
  {/if} 
  <span onclick="hg_close_opration_info();" title="关闭/ALT+Q"></span>
</div>
<div class="info clear cz">
	<div id="video_opration" class="clear" style="border:0; ">
			<div class="common-opration-list">
			    <a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a={$eidt_a_name}&id={$formdata['id']}&infrm=1" target="formwin">编辑</a>
			    <a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$formdata['id']}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>
			</div>
			<div class="common-opration-list">
			    <a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=audit&audit=1&id={$formdata['id']}" onclick="return hg_ajax_post(this, '审核', 0, 'hg_change_status');">审核</a>
			    <a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=audit&audit=0&id={$formdata['id']}" onclick="return hg_ajax_post(this, '打回', 0, 'hg_change_status');">打回</a>
			</div>
			<div class="common-opration-list">
			    <a class="button_6" href="./run.php?mid={$_INPUT['mid']}&a=recommend&id={$formdata['id']}" onclick="return hg_ajax_post(this, '推荐', 0);">发布至网站</a>
			</div>
	</div>
</div>
<div class="info clear vo">
	<h4 onclick="hg_slide_up(this,'video_subinfo')"><span title="展开\收缩"></span>文章属性</h4>
	<ul id="video_subinfo" class="clear">
		<li class="h"><span>分类：<em class="common-color">{$formdata['name']}</em></span></li>
		<li class="h"><span>来源：{$formdata['source']}</span></li>
		<li class="h"><span>作者：{$formdata['author']}</span></li>
		<li class="h"><span>状态：<em class="common-color">{if $formdata['state']}已审核{else}未审核{/if}</em></span></li>
		<!--  <li class="h"><span>发布至：<em class="common-color-fb">{$formdata['cu']}</em></span></li>-->
		<li class="h"><span>是否置顶：{if $formdata['istop']}是{else}否{/if}</span></li>
        <li class="h"><span>排序：{$formdata['order_id']}</span></li>
		<li class="h"><span>是否独立模板：{if $formdata['istpl']}是{else}否{/if}</span></li>
		<li class="h"><span>模板文件名：{$formdata['tpl_file']}</span></li>
		<li class="h"><span>点击次数：<em class="common-color">{$formdata['click_num']}次</em></span></li>
		<li class="h"><span>评论次数：<em class="common-color">{$formdata['comm_num']}次</em></span></li>
		<li class="h"><span>创建者IP：{$formdata['ip']}</span></li>
		<li class="h"><span>创建时间：{$formdata['create_time']}</span></li>
		<li class="h"><span>更新时间：{$formdata['update_time']}</span></li>
		<li class="h"><span>发布时间：--</span></li>
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
		{if $formdata['subtitle']}<li class="w"><span>副标题：{$formdata['subtitle']}</span></li>{/if}
		{if $formdata['keywords']}<li class="w"><span>关键字：{$formdata['keywords']}</span></li>{/if}
		{if $formdata['brief']}<li class="w"><span>描述：{$formdata['brief']}</span></li>{/if}
	</ul>
</div>
{else}
此新闻已经不存在,请刷新页面更新
{/if}