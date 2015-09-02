<div class="action_right">
			<div class="ac_r_top"></div>
			<div class="ac_r_mid">
				<!--行动分类-->
				<h2 class="ac_fen"><a href="#">行动分类</a></h2>
				<ul class="ac_fenlist">
				{foreach $types as $type}
					<li><a href="{$urldate}&type_search={$type['id']}">{$type['name']}({$type['num']})</a></li>
				{/foreach}
				</ul>
				{if count($olds)}
				<h2 class="ac_huigu"><span></span>行动回顾</h2>
				<ul class="ac_hui_list">
				{foreach $olds as $old}
					<li>
						<a href="activity.php?action_id={$old['id']}"><img src="{$old['action_img']}"></a>
						<p><a href="activity.php?action_id={$old['id']}">{$old['action_name']}</a></p>
					</li>
				{/foreach}	
				</ul>
				{/if}
			</div>
			<div class="ac_r_btn"></div>
		</div>