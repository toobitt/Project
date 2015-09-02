{if $formdata['id']}
<div class="info clear vider_s"  id="vodplayer_{$formdata['id']}"  style="width:100%;min-height:50px;background:white;overflow:hidden;">
	<div class="ajax_view_html" style="width:100%;max-height:350px;background:white;text-align:center;padding:10px;overflow:auto;">
	{code}
		$hg_attr['video'] = 'video';
	{/code}
	{template:unit/adv_mtype,video,adv,$formdata}
	</div>
	<span onclick="hg_close_opration_info();" title="关闭/ALT+Q"></span>
</div>
<div class="info clear cz">
	<h4 onclick="hg_slide_up(this,'adv_opration')"><span title="展开\收缩"></span>操作选项</h4>
	<ul id="adv_opration" class="clear" style="height:23px;">
		<li><a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=content_form&id={$formdata['id']}&infrm=1">编辑</a></li>
		{if !$v['iscopy']}
		<li><a class="button_4" onclick="return hg_copy_ad(this.href, {$formdata['id']})" href="./run.php?mid={$_INPUT['mid']}&a=copy&id={$formdata['id']}">复制</a></li>
		{/if}
		<li><a class="button_4" onclick="return hg_ajax_post(this, '删除', 1);"   href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$formdata['id']}">删除</a></li>
		{if $v['distribution']}
		<li><a class="button_4" target="_blank"  href="./run.php?mid={$_INPUT['mid']}&a=adpreview&content_id={$formdata['id']}&mtype={$v['mtype']}">预览</a></li>
		{/if}
		{if in_array($v['status'],array(1,3,4))}
		<li><a class="button_4"   href="./run.php?mid={$_INPUT['mid']}&a=form_publish&content_id={$formdata['id']}&infrm=1">投放</a></li>
		{/if}
		{if in_array($v['status'],array(1)) && $v['distribution']}
		<li><a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=adcancell&id={$formdata['id']}" onclick="return hg_ajax_post(this, '下架', 0);">下架</a></li>
		{/if}
		{if $v['status'] == '6'}
		<li><a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=adonline&id={$formdata['id']}" onclick="return hg_ajax_post(this, '上架', 0);">上架</a></li>
		{/if}
	</ul>
</div>
<div class="info clear vo">
	<h4 onclick="hg_slide_up(this,'adv_subinfo')"><span title="展开\收缩"></span>素材属性</h4>
	<ul id="adv_subinfo" class="clear">
		<li class="h"><span>广告链接：</span>{$v['link']}</li>
		<li class="h"><span>所属客户：</span>{if $v['customer_name']}{$v['customer_name']}{else}本站投放{/if}</li>
		<li class="w"><span>素材类型：</span>{$_configs['mtype'][$v['mtype']]}{if $v['iscopy']}[复制]{/if}</li>
		<li class="w"><span>投放时间：{if !$v['alltime']}无限期{/if}</span>
			<ul style="margin-top:8px;">
				{if $v['alltime']}
					{foreach $v['alltime'] as $t=>$tt}
						{code}
							$color = '';
							if($tt['start_time'] == $v['start_time'])
							{
								if($v['status'] == 1)
								$color = 'style="color:green"';
							}
							else if($tt['start_time'] < $v['start_time'])
							{
								$color = 'style="color:red;text-decoration:line-through"';
							}
						{/code}
						<li {$color}>{$tt['start_time']} 至 {$tt['end_time']}</li>
					{/foreach}
				{/if}
			</ul>
		</li>
	</ul>
</div>
{if $v['show'] == 4}
<script type="text/javascript">
$(function(){
	$("#vodplayer_{$formdata['id']}").find(".ajax_view_html").html($("#r_{$formdata['id']}").find(".view_html").html());
})
</script>
{/if}
<div class="info clear vo">
	<h4 onclick="hg_slide_up(this,'fabu')"><span title="展开\收缩"></span>发布简要统计</h4>
	<style type="text/css">
	#fabu tr{height:25px;line-height:25px;}
	#fabu{border-top:1px solid #e0e0e0;}
	</style>
		{if $formdata['distribution']}
		<table id="fabu" class="clear">
			<tr><td width="80">分组</td><td width="80">广告位</td><td width="100">输出/点击</td></tr>
			{foreach $formdata['distribution'] as $i=>$p}
				{if $p}
				<tr>
				{code}
				$_index__ = implode('_',array_keys($p));
				{/code}
					{foreach $p as $pk=>$pv}
						<td>{$pv}</td>
					{/foreach}
					<td>
						{if $formdata['statistic'][$_index__]}
							{$formdata['statistic'][$_index__]['output']}/{$formdata['statistic'][$_index__]['click']}
						{/if}
					</td>
				</tr>
				{/if}
			{/foreach}
		</table>
		{else}
		<div id="fabu" style="margin:0px 0 4px 6px;padding-top:4px">暂时未有相关数据</div>
		{/if}
</div>
{else}
此广告已经不存在,请刷新页面更新
{/if}