<!-- 
<li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');"  onclick="hg_row_interactive(this, 'click', 'cur');" >
	<span class="left">
		<a class="lb"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}"  /></a>
	</span>
	<span class="right"  style="width:500px">
		<a class="fb" href="javascript:void(0);"  onclick="hg_showAddSort({$v['id']});"><em class="b2" ></em></a>
		<a class="fb" href="javascript:void(0);"  onclick="hg_delete_sort({$v['id']})"><em class="b3" ></em></a>
		<a class="fl zt" ><em>{$v['count']}</em></a>
		<a class="fl zt"><em>{$v['collect_count']}</em></a>
		<a class="tjr"><em>{$v['user_name']}</em><span>{$v['create_time']}</span></a>
	</span>
	<span class="title overflow"  style="cursor:pointer;" >
		<a href="run.php?mid={$_INPUT['mid']}&&fid={$v['id']}"><span id="sort_name_{$v['id']}"  {if $v['color']}style="color:{$v['color']}" {/if}>{$v['name']}</span><strong></strong></a>
	</span>
</li>
-->
<li class="common-list-data clear"  id="r_{$v['id']}" name="{$v['id']}" orderid="{$v['order_id']}"">
	<div class="common-list-left">
		<div class="common-list-item paixu">
			<div class="common-list-cell">
				<a class="lb"  name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}"  /></a>
			</div>
		</div>
	</div>
	<div class="common-list-right">
	    <div class="common-list-item sort-edit">
			<div class="common-list-cell">
				<em onclick="hg_showAddSort({$v['id']});"></em>
			</div>
		</div>
		<div class="common-list-item sort-delete">
			<div class="common-list-cell">
				<em onclick="hg_delete_sort({$v['id']})"></em>
			</div>
		</div>
		<div class="common-list-item vod-num">
			<div class="common-list-cell">
				{$v['count']}
			</div>
		</div>
		<div class="common-list-item set-num">
			<div class="common-list-cell">
				{$v['collect_count']}
			</div>
		</div>
		<div class="common-list-item ren">
			<div class="common-list-cell">
				<div class="name">{$v['user_name']}</div>
				<div class="time">{$v['create_time']}</div>
			</div>
		</div>
	</div>
	<div class="common-list-biaoti">
		<div class="common-list-item biaoti-transition">
			<div class="common-list-cell">
				<a href="run.php?mid={$_INPUT['mid']}&&fid={$v['id']}&infrm=1&nav=1"><span id="sort_name_{$v['id']}"  {if $v['color']}style="color:{$v['color']}" {/if}>{$v['name']}</span><strong></strong></a>
			</div>
		</div>
	</div>
</li> 