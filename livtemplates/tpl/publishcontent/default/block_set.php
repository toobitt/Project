{template:head}
{code}
//print_r($formdata);exit;
$block = $formdata['block']['block'];
foreach ($block as $k => $v) {
	$block[$k]['children'] = $formdata['block_line'][$k];
}

foreach ($block as $k => $v) {
	foreach ($block[$k]['children'] as $kk => $vv) {
		$block[$k]['children'][$kk]['children'] = $formdata['content'][$k][$kk] ? $formdata['content'][$k][$kk] : null;
	}
}
$jsvar = json_encode($block);
{/code}
<script type="text/javascript">
	var gData = {$jsvar};
</script>
{css:block_set}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{js:publishcontent/block_set}

{code}
$sizeSelect = '<select name="font_size"><option value="">字体大小</option>';
foreach ( range(12, 20) as $v ) {
	$sizeSelect .= '<option>'.$v.'px</option>';
}
$sizeSelect .= '</select>';


$prefixSelect = preg_replace( "/\s/", '', '
	<select>
		<option>另一面</option>
		<option>这一面</option>
	</select>
');

$sufixSelect = preg_replace( "/\s/", '', '
	<select>
		<option>另一面</option>
		<option>这一面</option>
	</select>
');
{/code}


<div style="padding:0 10px;">
	<h1 id="head">区块编辑</h1>
{if !$block}
	<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">未选择栏目或者没有内容</p>
	<script>hg_error_html("p",1);</script>
{else}
	{foreach $block as $k => $v}
	<div class="block-content" id="block{$v['id']}" data-id="{$v['id']}">
		<div class="meta-info clear">
			<p>
				<label>
					栏目：
			{if $formdata['block']['block_record'][$v['id']]}
			{foreach $formdata['block']['block_record'][$v['id']] as $kk=>$vv}
			{if $formdata['column'][$vv]}
			{$formdata['column'][$vv]}
			{else}
			不限
			{/if}
			{/foreach}
			{else}
			不限
			{/if}
				</label>
			</p>
			<p>
				<label>
					应用：
			{code}
			if($v['app'])
			{
				foreach(explode(',',$v['app']) as $va)
				{
					echo $formdata['app'][$va].' ';
				}
			}
			else
			{
				echo "不限";
			}
			{/code}
				</label>
			</p>
			<p><label>权重：{$v['datasource_argument']['weight']}</label></p>
			<p><label>条数：{$v['line_num']}</label></p>
		</div>
		<ul class="has-event">
		{for $j = 1; $j <= count($v['children']); $j++}
		{code}$vv = $v['children'][$j]{/code}
			<li id="line{$vv['id']}" data-id="{$vv['id']}">
				<div class="line-wrap clear">
					<div class="anchor-box"> 
						<div class="anchor-box-inner">
						
						{for $i = 0; $i < count($vv['children']); $i++}
						{code}$vvv = $vv['children'][$i]{/code}
							<a class="anchor" id="anchor{$vvv['id']}" data-id="{$vvv['id']}">{$vvv['title']}</a>
						{/for}
							<a class="option-add">添加一条新闻</a>
						</div>
					</div>
					<a class="option-setting">设置</a>
				</div>
			</li>
		{/for}
		</ul>
		<p class="clear"><span class="operate-sort" title="排序模式切换">排序</span><span class="has-event operate-bat-setting">批量设置</span></p>
		<div class="ordertip">排序模式已开启<span style="color:#666;margin:0 0 0 5px;font-size:12px;cursor: pointer;">退出</span><input class="button_4" style="margin-left:10px;" type="button" value="保存排序"></div>
	</div>
	{/foreach}
	
	<div id="line-prop-editor" class="line-prop-editor">
		<span class="line-prop-editor-pointer"></span>
		<h1>行属性设置</h1>
		<ul>
			<li><div class="font-attr"><span>颜色<input type="hidden" name="font_color" /></span><span>加粗<input type="hidden" name="font_b" /></span><span>{$sizeSelect}</span></div></li>
			<li><label>前缀：</label><span data-cname="with-prefix-1"></span><span data-cname="with-prefix-2"></span><span data-cname="with-prefix-3"></span></li>
			<li><label>前缀：</label><span>{$prefixSelect}</span></li>
			<li><label>后缀：</label><span>{$sufixSelect}</span></li>
			<li><label>图标：</label><span data-cname="with-pic"></span><span data-cname="with-music"></span><span data-cname="with-video"></span></li>
			<li><label>背景：</label><span></span></li>
		</ul>
	</div>
	<div id="full-screen-masking"></div>
	<div id="anchor-prop-editor">
		<div class="anchor-editor-type"><span class="current">自定义</span><span>从数据源选择</span></div>
		<div class="anchor-fill-editor">
			<h1><label id="headLabel">编辑: </label><span id="titleLabel">标题</span></h1>
			<form class="clear">
				<p class="index-pic-box"><img width="120" height="90" _src="{$RESOURCE_URL}publish/default.png" id="indexpic" /><input type="hidden" name="indexpic" value="" /><input type="file" id="upload" /></p>
				<p><input type="text" value="标题" name="title" /></p>
				<p><textarea rows="" cols="" name="brief">提要</textarea></p>
				<p><input type="text" value="链接" name="outlink" /></p>
				<ul>
					<li><div class="font-attr"><span>颜色<input type="hidden" name="font_color" /></span><span>加粗<input type="hidden" name="font_b" /></span><span>{$sizeSelect}</span></div></li>
					<li><label><input type="checkbox" />边框</label></li>
					<li><label><input type="checkbox" />前缀</label></li>
					<li><label><input type="checkbox" />图标</label></li>
				</ul>
			</form>
			<p><span>保存并关闭</span><span>删除</span><span>关闭</span></p>
			<!--  <div class="enter-data-source">从数据源选择</div>-->
		</div>
		<div class="anchor-select-editor">
			<div class="source-header"></div>
			<div class="source-content">
				<div>
					<h1 class="overflow"><label></label><span></span></h1>
					<form class="text-search">
						<div class="button_search">
							<input type="submit" value="" name="hg_search"  />
						</div>
						{template:form/search_input,key,$_INPUT['key']}
					</form>
				</div>
				<ul></ul>
			</div>
			<div align="center" class="hoge_page">
				<span class="page_all"></span>
			</div>
		</div>
	</div>
	<div id="color-picker"></div>
{/if}
</div>


{template:foot}