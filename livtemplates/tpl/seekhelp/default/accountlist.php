{foreach $formdata as $v}
{code}
	$imgUrl = $v['avatar']['host'].$v['avatar']['dir'].'80x70/'.$v['avatar']['filepath'].$v['avatar']['filename'];
{/code}
<li class="snap-img" data-id="{$v['id']}">
	<div class="middle-img-wrap">
		<img src="{$imgUrl}"/>
		<span>{$v['name']}</span>
	</div>
</li>
{/foreach}

