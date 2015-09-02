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
  <div id="vodPlayer" class="content-stand">
	  {code}
		$hg_map = array(
			'height'=>300,
			'width'=>420,							
			'longitude'=>isset($formdata['baidu_longitude']) ? $formdata['baidu_longitude'] : '0',          //经度
			'latitude'=>isset($formdata['baidu_latitude']) ? $formdata['baidu_latitude'] : '0', 			  //纬度
			'zoomsize'=>13,          //缩放级别，1－21的整数
			'areaname'=>$_configs['areaname'],          //显示地区名称，纬度,经度与地区名称二选1
			'is_drag'=> 0,            //是否可拖动 1－是，0－否
		);  
	  {/code}   
	  {template:form/baidu_map,baidu_longitude,baidu_latitude,$hg_map}
  </div>   
  <span onclick="hg_close_opration_info();" title="关闭/ALT+Q"></span>
</div>
<div class="info clear cz">
	<ul id="video_opration" class="clear" style="border:0;">
		<li>
			<div class="common-list"><a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$formdata['id']}&infrm=1">编辑</a></div>
		</li>
		<li>
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$formdata['id']}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>
		</li>
		<li>
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=audit&audit=1&id={$formdata['id']}" onclick="return hg_ajax_post(this, '审核', 0, 'hg_change_status');">审核</a>
		</li>
		<li>
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=audit&audit=0&id={$formdata['id']}" onclick="return hg_ajax_post(this, '打回', 0, 'hg_change_status');">打回</a>
		</li>
	</ul>
</div>
{else}
此路况已经不存在,请刷新页面更新
{/if}