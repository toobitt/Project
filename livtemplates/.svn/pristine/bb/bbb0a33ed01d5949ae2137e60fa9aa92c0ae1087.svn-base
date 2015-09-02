{code}
$w = $v['albums_cover']['width'];
$h = $v['albums_cover']['height'];
if ($w >= $h) {
	$sw = 150;
	$sh = 1;
}else {
	$sw = 1;
	$sh = 150;
}
{/code}
<li class="clear m2o-each" id="{$v['id']}" name="{$v['id']}" _id="{$v['id']}" data-id="{$v['id']}">
	<div class="albums-list">
		<div class="preview">
			<!--<a href="./run.php?mid={$_INPUT['mid']}&a=viewAlbumsPhoto&id={$v['id']}&pic=1" target="formwin" title="点击查看图片">-->
			<a href="./run.php?mid=528&albums_id={$v['id']}" target="formwin" title="点击查看照片">
				<img src="{code}echo hg_bulid_img($v['albums_cover'], $sw, $sh);{/code}"/>
				<span class="pl-count">{$v['photos_total']}张</span>
			</a>
		</div>
		<div class="second"></div>
		<div class="third"></div>
	</div>
	<div class="albums-info-contain" style="background:#eee;width: 160px;height:46px;margin-left: 3px;">
		<div class="albums-info">
			<span class="albums-name m2o-common-title" title="标题">{$v['albums_name']}</span>
			<!--
			<a class="pl-count" href="./run.php?mid={$_INPUT['mid']}&a=viewAlbumsComment&id={$v['id']}" target="formwin">{$v['comment_total']}评论</a>
			-->
			<a class="pl-count comment" href="./run.php?mid=529&albums_id={$v['id']}" target="formwin" title="点击查看评论">{$v['comment_total']}评论</a>
		
		</div>
		<div class="albums-info">
			<a class="move-publish" title="点击分类">{$v['category']['name']}</a>
		
		<!--  	<a class="aduit-list reaudit m2o-audit" data-method="audit" _state="{$v['is_audit']}" style="color:{if $v['is_audit']== 1}#17b202;{else if$v['is_audit']== 2}#f8a6a6;{/if}">{if $v['is_audit']== 1}已审核{else}已打回{/if}</a>-->
			<span class="audit m2o-audit" _status="{$v['is_audit']}" style="color:{$_configs['status_color'][$v['is_audit']]}">{if $v['is_audit']== 1}已审核{else}已打回{/if}</span>
		</div>
	</div>
	<a class="del" data-method="delete"></a>
</li>




