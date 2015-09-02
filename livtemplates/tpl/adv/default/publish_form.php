{template:head}
{js:common/common_publish}
{css:common/common_form}
{css:ad_style}
{js:ad}
<style>
.extend-item{display:none;}
</style>
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l" id="publish_form" onsubmit="return hg_publish_submit()">
<h2 style="background:none">发布广告</h2>
<div style="width:720px;overflow:hidden;height:90px;">
{template:unit/adv_mtype, adv, adv, $formdata['ad_content']}
</div>
<br />
<div style="padding:5px 0;"><span style="color:#949494;">广告名称：</span>{$formdata['ad_content']['title']}</div>
<div style="padding:5px 0;"><span style="color:#949494;">投放时间：</span>{if $formdata['ad_content']['start_time']}{$formdata['ad_content']['start_time']}<span style="color:#949494;"> 至 </span>{$formdata['ad_content']['end_time']}{else}当前时间段广告已停止投放{/if}</div>
{code}
	unset($formdata['ad_content']);
{/code}
<ul class="form_ul" id="alllist">
<!--隐藏添加按钮 处理边界问题-->
<li class="i" style="background:#DDEEFE;display:none;" onclick="hg_add_publish()" id="plus_li">
	<div class="form_ul_div clear">
	<span style="float:left" class="plus" title="增加发布"></span><span class="plus_desc clear">单机此处可以继续添加发布数据</span>
	</div>
</li>
<!--更新广告发布策略开始-->
{if !empty($formdata)}
	{code}
		$a='update_policy';
	{/code}
	{foreach $formdata as $key => $value}
	{code}
		$new_id = $key;
	{/code}
	<li class="i nobg" id="list_{$new_id}" handler="catch">
		<div class="form_ul_div clear publish_title">
			<span class="toggle" style="float:right;margin-right:2px;" onclick='toggleElem(this, ".adv_box", "toggleDown")'>高级</span>
			<div style="float:left;margin:0 10px 0 0;font-weight:700;line-height:30px;">发布到 </div>
			{code}
					/*select样式*/
					$group_style = array(
					'class' => 'down_list i select_margin s module_list',
					'show' => 'group_ul_' . $new_id,
					'width' => 120,	
					'state' => 0, 
					'is_sub'=>1,
					'onclick'=>'hg_getadvpos(' . $new_id . ')',
					'more'=> $new_id,
					);
					$d = $value['group'] ? $value['group'] : 0;
			{/code}
			{template:form/search_source,group,$d,$group[0],$group_style}
			{code}
					/*select样式*/
					$pos_style = array(
					'class' => 'down_list i select_margin',
					'show' => 'advpos_ul_' . $new_id,
					'width' => 200,	
					'state' => 0, 
					'is_sub'=>1,
					'onclick'=>'hg_advpos_para(' . $new_id . ');hg_ad_check(' . $new_id . ')',
					'more'=> $new_id,
					);
					$d = $value['pos_id'] ? $value['pos_id'] : 0;
			{/code}
			<span class="advpos" id="advpos_span_{$new_id}">{$value['zh_anipara'][$d]['name']}
			{template:form/search_source,advpos,$d,$value['advpos'],$pos_style}</span>
			<input id="advpub_{$new_id}" name="advpub[]" type="hidden" value="{$new_id}"/>
		</div>
		<div class="adv_box">
			<div id="advpara_{$new_id}">
				<ul class="publish_para clear">
					{if $value['zh_advpara']}
						{foreach $value['zh_advpara'] as $pos_id=>$zh}
							<li style="float:right">
								<a class="g" href="javascript:void(0)" onclick="hg_get_advsettings('run.php?mid={$_INPUT['mid']}&a=advanced_settings&pos_id={$pos_id}&id={$key}&groupflag={$value['group']}&edit={$_INPUT['content_id']}', '{$key}')">高级</a></li>
							<li>
							{foreach $zh[0] as $pkk=>$pvv}
							
								{code}
									$hg_attr['text'] = $pvv;
								{/code}
								{template:unit/para_setting, $pkk[$key],$value['param']['pos'][$pkk], $zh[1][$pkk]}
							
							{/foreach}
						{/foreach}
					{/if}
					{if $value['zh_anipara']}
						{foreach $value['zh_anipara'] as $ani_id=>$ani_zh}
							{foreach $ani_zh['para'] as $akk=>$avv}
								{code}
								$hg_attr['text'] = $avv;
								{/code}
								{template:unit/para_setting, $akk[$key],$value['param']['ani'][$akk], $ani_zh['form_style'][$akk]}
							{/foreach}
						{/foreach}
						<li>
						<div class="form_ul_div">
							<input type="hidden" name="ani_id[{$key}]" value='{$value["ani_id"]}'>
						</div>
						</li>
					{/if}
					</li>
				</ul>
			</div>
			<div id="advsettings_{$new_id}" _id="{$new_id}" class="setting-box"></div>
			<!--解决高级选项编辑时的bug-->
			<input type="hidden" name="needupdate[{$new_id}]" id="needupdate_{$new_id}" value="0">
		</div>
	</li>
	{/foreach}
<!--更新广告发布策略结束-->
{else}
<!--创建广告发布开始-->
	{code}
	$a='create_policy';
	$new_id = hg_rand_num(10);
	{/code}
	<li class="i nobg" id="list_{$new_id}" handler="catch">
	<div class="form_ul_div clear publish_title">
			<span class="toggle" style="float:right;margin-right:2px;" onclick='toggleElem(this, ".adv_box", "toggleDown")'>高级</span>
		<div style="float:left;margin:0 10px 0 0;font-weight:700;line-height:30px;">发布到 </div>
	{code}
			/*select样式*/
			$group_style = array(
			'class' => 'down_list i select_margin s module_list',
			'show' => 'group_ul_' . $new_id,
			'width' => 120,	
			'state' => 0, 
			'is_sub'=>1,
			'onclick'=>'hg_getadvpos(' . $new_id . ')',
			'more'=> $new_id,
			);
			$d = 0;
	{/code}
	{template:form/search_source,group,$d,$group[0],$group_style}
	{code}
			/*select样式*/
			$pos_style = array(
			'class' => 'down_list i select_margin',
			'show' => 'advpos_ul_' . $new_id,
			'width' => 200,	
			'state' => 0, 
			'is_sub'=>1,
			'onclick'=>'hg_advpos_para(' . $new_id . ');hg_ad_check(' . $new_id . ')',
			'more'=> $new_id,
			);
	{/code}
	<!--此处的span样式advpos仅用于寻找节点-->
	<span class="advpos" id="advpos_span_{$new_id}">
	{template:form/search_source,advpos,$d,$advpos,$pos_style}</span>
	<input id="advpub_{$new_id}" name="advpub[]" type="hidden" value="{$new_id}"/>
	</div>
	<div class="publist_box">
	<div id="advpara_{$new_id}"></div>
	<div id="advsettings_{$new_id}" style="display:block" _id="{$new_id}" class="s setting-box"></div>
	</div>
	</li>
{/if}
<!--创建广告发布策略结束-->
</ul>
<div class="publish_title" style="width:698px;margin-left:2px;"><span class="select_matrial" style="display:inline;padding:5px 10px;" onclick="hg_add_publish()" title="增加发布">添加广告位</span></div>
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="ad_id" id="ad_id" value="{$_INPUT['content_id']}" />
<input type="hidden" name="goon" value="0" id="goon"/>
<input type="hidden" name="needconfirm" value="0" id="needconfirm"/>
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<br />
<input type="submit" name="sub" value="确定发布" class="button_6_14" style="margin-left:45px"/>
<input type="button" name="cancel" onclick="hg_cancel_publish()" value="取消所有发布" class="button_6_14" style="margin-left:25px"/>
</form>
</div>
<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
<!--用于克隆发布html-->
<div id="li_clone_obj">
{code}
$new_id = 22222;
{/code}
<li class="i nobg" id="list_{$new_id}" handler="catch" style="display:none">
<div class="form_ul_div clear publish_title">
<span class="toggle" style="float:right;margin-right:2px;" onclick='toggleElem(this, ".adv_box", "toggleDown")'>高级</span>
<div style="float:left;margin:0 10px 0 0;font-weight:700;line-height:30px;">发布到 </div>
{code}
		/*select样式*/
		$group_style = array(
		'class' => 'down_list i select_margin s module_list',
		'show' => 'group_ul_' . $new_id,
		'width' => 120,	
		'state' => 0, 
		'is_sub'=>1,
		'onclick'=>'hg_getadvpos(' . $new_id . ')',
		'more'=> $new_id,
		);
		$d = 0;
{/code}
{template:form/search_source,group,$d,$group[0],$group_style}
{code}
		/*select样式*/
		$pos_style = array(
		'class' => 'down_list i select_margin',
		'show' => 'advpos_ul_' . $new_id,
		'width' => 200,	
		'state' => 0, 
		'is_sub'=>1,
		'onclick'=>'hg_advpos_para(' . $new_id . ');hg_ad_check(' . $new_id . ')',
		'more'=> $new_id,
		);
{/code}
<!--此处的span样式advpos仅用于寻找节点-->
<span class="advpos" id="advpos_span_{$new_id}">
{template:form/search_source,advpos,$d,$advpos,$pos_style}</span>
<span class="minus clear" style="margin-top:5px;" onclick="hg_del_publish(this)"></span>
<input id="advpub_{$new_id}" name="advpub[]" type="hidden" value="{$new_id}"/>
</div>
<div class="adv_box">
<div id="advpara_{$new_id}"></div>
<div id="advsettings_{$new_id}" class="setting-box" _id="{$new_id}"></div>
</div>
</li>
{template:unit/publish_for_form, 1, $formdata['column_id']}
</div>
<!--克隆发布html结束-->
<script type="text/x-jquery-tmpl" id="column-tmpl">
	<div class="column-area">
	 <input name="${module}col_${id}" class="column-id" type="hidden" />
	 <input name="${module}col_${id}_name" class="column-name" type="hidden" />
	</div>
</script>
{template:foot}