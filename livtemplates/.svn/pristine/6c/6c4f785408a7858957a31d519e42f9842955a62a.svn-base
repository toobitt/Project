{code}
	$first_pic = $v['indexpic'][0];
	$index_img = $first_pic ? $first_pic['host'].$first_pic['dir'].'40x30/'.$first_pic['filepath'].$first_pic['filename'] : '';
{/code}
<div class="m2o-each m2o-flex m2o-flex-center" id="r_{$v['id']}" data-id="{$v['id']}" orderid="{$v['order_id']}">
    <div class="m2o-item m2o-paixu">
		<input type="checkbox" value="{$v['id']}" title="{$v['id']}" name="infolist[]" class="m2o-check" style="visibility: visible;">
	</div>
	<div class="m2o-item m2o-flex-one m2o-bt" title="{$v['title']}">
		 <div class="m2o-title-transition m2o-title-overflow">
			 <a href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
				{if $index_img}<img style="margin-right:10px" src="{$index_img}" width="40" height="30">{/if}
				<span>{$v['title']}</span>
			</a>
		 </div>
	</div>
    <div class="m2o-item m2o-time">
        <span class="name">{$v['user_name']}</span>
        <span class="time">{$v['create_time']}</span>
    </div>
    <div class="m2o-item m2o-ibtn">
    </div>
</div>
