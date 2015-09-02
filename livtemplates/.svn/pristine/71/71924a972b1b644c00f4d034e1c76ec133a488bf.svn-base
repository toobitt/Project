{code}
$w = $v['photos_info']['width'];
$h = $v['photos_info']['height'];
if ($w >= $h) {
	$sw = 150;
	$sh = 1;
}else {
	$sw = 1;
	$sh = 150;
}
{/code}
<li class="clear" id="{$v['id']}" name="{$v['material_id']}">
	<div class="preview">
		<img src="{code}echo hg_bulid_img($v['photos_info'], $sw, $sh);{/code}" /></a>
	</div>
	<div class="pic-info">
		<p class="handle">
			<a class="user-name"><span class="m2o-common-title">{$v['user_name']}</span></a>
			{if $v['state']}
			<span class="icons" _state="{$v['state']}">
				<!--
				<a class="pl" href="./run.php?mid={$_INPUT['mid']}&a=viewPhotoComment&id={$v['id']}" target="formwin">{$v['comment_total']}</a>
				-->
				<a class="pl" href="./run.php?mid=530&photo_id={$v['id']}" target="formwin">{$v['comment_total']}</a>
				<a class="zan">{$v['praise_num']}</a>
			</span>
			{else}
			<span style="float:right;" _state="{$v['state']}" class="reaudit" data-method="audit">待审核</span>
			{/if}
		</p>
		<p class="pic-intro">{$v['photos_brief']}</p>
		<div class="more-info">
			<p class="pos">{$v['address']}</p>
			<p class="time">{code}echo date('Y-m-d H:i:s', $v['create_time']);{/code}</p>
		</div>
	</div>
	<a class="del" data-method="delete"></a>
</li>