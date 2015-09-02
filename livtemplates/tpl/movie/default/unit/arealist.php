<!--
<li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
	<span class="left nb2" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');" >
		<a class="lb"  name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}"  /></a>
		<a class="shareslt"  >&nbsp;{$v['name']}</a>
		<a class="shareslt"  href="./run.php?mid={$_INPUT['mid']}&a=area_detail&id={$v['id']}">编辑</a>
	</span>
</li>   
-->
<li class="common-list-data clear" id="r_{$v['id']}" name="{$v['id']}">
	<div class="common-list-left">
		<div class="common-list-item paixu">
			<div class="common-list-cell">
				<a class="lb"  name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}"  /></a>
			</div>
		</div>
		<div class="common-list-item name">
			<div class="common-list-cell">
				{$v['name']}
			</div>
		</div>
		<div class="common-list-item option">
			<div class="common-list-cell">
				<a class="shareslt"  href="./run.php?mid={$_INPUT['mid']}&a=area_detail&id={$v['id']}&infrm=1&nav=1">编辑</a>
			</div>
		</div>
	</div>
</li>