<li class="product-item m2o-each" _id="{$v['id']}" data-id="{$v['id']}" _orderid="{$v['order_id']}">
	<a href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" target="formwin">
	<div class="prev" >
		<img src="{$v['indexpic_url']}">
		<p class="sort">{$v['company_name']}/{$v['sort_name']}
		</p>
	</div>
	</a>
	<div class="info">
		<div class="name">
		<span style="color:{$_configs['status_color'][$v['cheap_color']]}">{$v['cheap_status']}</span>
		<!--
			<div class="common-switch {if $v['use_live']}common-switch-on{/if}">
               <div class="switch-item switch-left" data-number="0"></div>
               <div class="switch-slide"></div>
               <div class="switch-item switch-right" data-number="100"></div>
            </div>
         -->
			<span class="type">{$v['type']} |</span>
			{$v['title']}
		</div>
		<p class="count">
			<span>评论:{$v['comment_num']}</span>
			<span>订单:{$v['order_num']}</span>
			<a class="check-order" href="run.php?mid={$_INPUT['mid']}&a=show_order&product_id={$v['id']}&infrm=1" target="formwin">查看订单</a>
		</p>
		<p class="time">
			<span>{$v['user_name']}</span>
			<span>{$v['create_time']}</span>
		</p>
	</div>
	<i class="del m2o-delete"></i>
	<span class="audit m2o-audit" _status="{$v['status']}" style="color:{$_configs['status_color'][$v['status']]}">{$v['audit']}</span>
</li>