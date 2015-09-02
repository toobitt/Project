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

//print_r($photos);
{/code}
<li class="clear m2o-each" id="{$v['id']}" name="{$v['material_id']}" _id="{$v['albums_id']}" data-id="{$v['id']}">
	<div class="set-cover" data-method="set_surface_pic">置顶</div>
	<div class="preview">
		<img src="{code}echo hg_bulid_img($v['photos_info'], $sw, $sh);{/code}" /></a>
	</div>
	<div class="pic-info">
		<p class="handle" data-method="auditPhoto">
			<a class="user-name">{$v['user_name']}</a>
			{if $v['state']}
			<span class="icons" _state="{$v['state']}">
				<!--
				<a class="pl" href="./run.php?mid={$_INPUT['mid']}&a=viewPhotoComment&id={$v['id']}" target="formwin">{$v['comment_total']}</a>
				-->
				<a class="pl" href="./run.php?mid=530&photo_id={$v['id']}" target="formwin">{$v['comment_total']}</a>
				<a class="zan">{$v['praise_num']}</a>
			</span>
			{else}
		<!--  	<span style="float:right;" _state="{$v['state']}" class="reaudit" data-method="audit" _state="{$v['is_audit']}">{if $v['state']== 1}已审核{else}待审核{/if}</span>-->
			{/if}
		</p>
		<p class="pic-intro">{$v['photos_brief']}<span class="audit m2o-audit" _status="{$v['state']}" style="color:{$_configs['status_color'][$v['state']]}">{if $v['state']== 0}待审核{else if$v['state']== 1}已审核{else}已打回{/if}</span></p>
		<div class="more-info">
			<p class="pos">{$v['address']}</p>
			<p class="time">{code}echo date('Y-m-d H:i:s', $v['create_time']);{/code}</p>
		</div>
	</div>
	<a class="del" data-method="delete"></a>
</li>