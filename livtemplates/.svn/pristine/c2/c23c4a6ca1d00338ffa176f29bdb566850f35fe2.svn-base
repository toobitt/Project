{code}
$image_resource = RESOURCE_URL;
{/code}
{code}
	//print_r($formdata);
{/code}
{if $formdata['id']}
<div class="info clear vider_s"  id="vodplayer_{$formdata['id']}">
	<div id="contribute_pics_show" class="tuji_pics_show content-stand" >
	{code}
		$url = '';
		if (!empty($formdata['index_url']))
		{
			$url = $formdata['index_url']['host'].$formdata['index_url']['dir'].'400x300/'.$formdata['index_url']['filepath'].$formdata['index_url']['filename'];
		}
	{/code}	  	  	
		<img alt="索引图" src="{$url}">	
		<span onclick="hg_close_opration_info();" style="position:absolute;z-index:30;" title="关闭/ALT+Q"></span> 
	</div>
</div>
<div class="info clear cz"  >
	<ul id="video_opration" class="clear" style="border:0;">
		<li>
			<a class="button_4"   href="./run.php?mid={$_INPUT['mid']}&a=form&id={$formdata['id']}&infrm=1">编辑</a>
		</li>
		<li>
			<a class="button_4" onclick="return hg_ajax_post(this, '删除', 1);"  title=""  href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$formdata['id']}">删除</a>
		</li>
		<li>
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=audit&audit=1&id={$formdata['id']}" onclick="return hg_ajax_post(this, '审核', 0);">审核</a>		
		</li>
		<li>
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=audit&audit=2&id={$formdata['id']}" onclick="return hg_ajax_post(this, '打回', 0);">打回</a>
		</li>
		<li>
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=sale_state&state=1&id={$formdata['id']}" onclick="return hg_ajax_post(this, '设计中', 0);">设计/预售</a>
		</li>
		<li>
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=sale_state&state=2&id={$formdata['id']}" onclick="return hg_ajax_post(this, '售票', 0);">售票</a>
		</li>
		<li>
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=sale_state&state=3&id={$formdata['id']}" onclick="return hg_ajax_post(this, '结束', 0);">结束</a>
		</li>
		
	</ul>
</div>




<div class="info clear vo">
	<h4 onclick="hg_slide_up(this,'ticket_textinfo')"><span title="展开\收缩"></span>内容属性</h4>
	<ul id="ticket_textinfo" class="clear">	    
		<li class="h"><span>标题：{$formdata['title']}</span></li>
		<li class="h"><span>分类：{$formdata['name']}</span></li>
		{code}
			$left = $formdata['goods_total']-$formdata['goods_total_left'];
		{/code}
		{if $formdata['goods_total'] && $formdata['goods_total_left']}
		<li class="h"><span>售出票数：{$left}</span></li>
		{/if}
		<li class="h"><span>状态：{$formdata['sale_state_name']}</span></li>
		
		{if $formdata['goods_total']}
		<li class="h"><span>总票数：{$formdata['goods_total']}</span></li>
		{/if}
		
		{if $formdata['goods_total']}
		<li class="h"><span>剩余票数：{$formdata['goods_total_left']}</span></li>
		{/if}
		<li class="w"><span>时间：{$formdata['show_time']}</span></li>
		<li class="w"><span>场馆：{$formdata['venue']}</span></li>
		<li class="w"><span>地址：{$formdata['address']}</span></li>
		<li class="w"><span>开始时间：{$formdata['start_time']}</span></li>
		<li class="w"><span>结束时间：{$formdata['end_time']}</span></li>
	</ul>
</div>
<!--  
{if intval($formdata['latitude']) || intval($formdata['longitude'])}
<div class="info clear vo"  style="height: 350px">
{code}
	$hg_map = array(
		'height'=>350,
		'width'=>380,
		'longitude'=>$formdata['longitude'],          //经度
		'latitude'=>$formdata['latitude'], 			  //纬度
		'zoomsize'=>13,          							  //缩放级别，1－21的整数
		'areaname'=>$position,         						  //显示地区名称，纬度,经度与地区名称二选1
		'is_drag'=>0,            							  //是否可拖动 1－是，0－否
	);
{/code}
{template:form/google_map,longitude,latitude,$hg_map}
</div>
-->
{/if}
{else}
	此票务信息不存在,请刷新页面更新
{/if}


