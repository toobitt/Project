{code}
	//print_r($formdata['material']);
{/code}
{foreach $formdata['material'] as $key=>$val}
	<div class="weather-pic-item">
		{code}
			$url = $val['pic']['host'].$val['pic']['dir'].'70x65/'.$val['pic']['filepath'].$val['pic']['filename'];
		{/code}
		<img alt="{$val['title']}" src="{$url}" id="img_url_{$key}" onclick = "selectImg({$key})"/>
		<span>{$val['title']}</span>
	</div>
{/foreach}