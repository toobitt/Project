{if $location}
<ul class="menu_part">
	<li class="first"><a href="#"></a><em></em></li>
	{code}
	$count = count($location);
	$i = 0;
	{/code}
	{foreach $location AS $k => $v}
	{code}
		$i++;
		if ($i == $count)
		{
			$class  = ' class="last"';
		}
		else
		{
			$class = '';
		}
	{/code}
	<li{$class}><a href="#">{$v['name']}</a></li>
	{/foreach}
</ul>
{/if}