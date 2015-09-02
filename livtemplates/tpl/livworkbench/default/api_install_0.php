{template:head}
<style type="text/css">
.shop_box{width:980px;min-height:608px;margin-top:20px;height:auto;margin-left:20px;}
.shop_box .shop_app{width:243px;height:96px;border-bottom:1px dotted #D9D9D9;position:relative;float:left;}
.shop_box .shop_app .app_name{color:blue;font-size:15px;width:138px;height:27px;position:absolute;left:95px;top:11px;}
.shop_box .shop_app .shop_app_img{width:70px;height:70px;position:absolute;left:12px;top:12px;}
.shop_box .shop_app .install{color:blue;font-size:15px;width:138px;height:27px;position:absolute;left:95px;top:47px;}
.shop_box .shop_app .install a{color:#000;font-size:12px;cursor:pointer;font-weight:blod;}
.shop_box .shop_app .install a:hover{color:blue;font-size:14px;cursor:pointer;}
</style>
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_system first dq"><em></em><a>应用商店</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
</div>
<div class="wrap n">
	<div class="shop_box">
		{foreach $all_app AS $k => $v}
		<div class="shop_app">
			<div class="shop_app_img">
				<img src="{$RESOURCE_URL}images/{$v['img']}" width="70" height="70" />
			</div>
			<span class="app_name">{$v['name']}</span>
			<span class="install">
			{if $v['install']}
				<a href='###'>已安装</a>
			{else}
				<a href='?a=show_api&name={$v["name"]}&bundle_id={$v["bundle_id"]}&host={$v["apihost"]}&dir={$v["apidir"]}' style="color:red;">免费安装</a>
			{/if}
			</span>
		</div>
		{/foreach}
	</div>
</div>
{template:foot}