{code}
$hg_attr = $hg_attr ? $hg_attr : array(
	func => 'hg_select_value',
	state => 0,
    is_sub => 1,
    show => 'site-nav'
)
{/code}
<script>
	var datalist = $.globalListData = {code}echo $hg_data ? json_encode($hg_data) : '{}';{/code};
</script>
<style>
.site_list{position:relative;}
.site-nav{width: 90px;height: 43px;background: #fff;margin: 0;line-height: 43px;text-align: center;color: #727272;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;}
.site-box{width: 600px;height: 350px!important;padding: 10px;border: 1px solid #cfcfcf;position: absolute;z-index: 9999999;background: #fff;top: 43px;left: -1px;background: #efefef;overflow:hidden;display:none;}
.site-box .switch-slider-box{width: 600px;height: 300px;overflow: hidden;}
.site-box .list-box{overflow: hidden;width: 400000px;height: 300px;position: absolute;}
.site-box li{float:left;}
.site-box .list-box li{height: 300px;width: 600px;margin-right: 10px;}
.site-box .list-box  span{cursor: pointer;display: block;float: left;width:90px;padding: 5px 10px;margin: 0 5px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;}
.site-box .list-box  span:hover{background:#5c99cf;color:#fff;}
.site_list:hover .site-box{display:block;}
.site-box .no-data{font-size: 18px;margin-left: 20px;color: #6ea5e8;}
.arrow{position: absolute;bottom: 6px;left: 280px;font-size: 14px;display:none;}
.arrow li{margin-right:20px;cursor:pointer;width:20px;height:20px;}
.arrow li.left{background:url({$RESOURCE_URL}calendar_date_left.png) no-repeat;}
.arrow li.right{background:url({$RESOURCE_URL}calendar_date_right.png) no-repeat;}
.arrow li.left:hover{background:url({$RESOURCE_URL}calendar_date_left_hover.png) no-repeat;}
.arrow li.right:hover{background:url({$RESOURCE_URL}calendar_date_right_hover.png) no-repeat;}
.search-box{width: 300px;height: 30px;display:-webkit-box;float: right;margin: 0px 10px 10px;border-radius: 3px;border: 1px solid #cfcfcf;background:#fff}
.search-box input{width:265px;border:none;margin: 1px;text-indent: 10px;}
.search-box input:focus{-webkit-box-shadow:none}
.search-box .search-img{display:block;width:25px;height:30px;background:url({$RESOURCE_URL}menu2013/search.png) no-repeat center center #fff;cursor:pointer;}
@media only screen and (-webkit-min-device-pixel-ratio: 2),
only screen and (-moz-min-device-pixel-ratio: 2),
only screen and (-o-min-device-pixel-ratio: 2/1),
only screen and (min-device-pixel-ratio: 2) {
	.search-box .search-img{background-image:url({$RESOURCE_URL}menu2013/search-2x.png);background-size:16px 16px;}
	.arrow li{background-size:8px 12px!important;}
	.arrow li.left{background-image:url({$RESOURCE_URL}calendar_date_left-2x.png);}
	.arrow li.right{background-image:url({$RESOURCE_URL}calendar_date_right-2x.png);}
	.arrow li.left:hover{background-image:url({$RESOURCE_URL}calendar_date_left_hover-2x.png);}
	.arrow li.right:hover{background-image:url({$RESOURCE_URL}calendar_date_right_hover-2x.png);}
}
</style>
<div class="site_list">
	<div class="colonm down_list site-nav" id="display_{$hg_attr['show']}">{$hg_data[$hg_value]}</div>
	<div class="site-box" id="{$hg_attr['show']}">
		<div class="search-box">
			<input type="text" name="search" placeholder="输入关键字搜索站点"/>
			<span class="search-img"></span>
		</div>
		<div class="switch-slider-box">
			<ul class="list-box" >
			{code}
				$hg_data_chunk = array_chunk( $hg_data, 50, true );
			{/code}
			{foreach $hg_data_chunk as $k => $v}
				<li class="list-item">
					{foreach $v as $kk => $vv}
					<span onclick="if({$hg_attr['func']}(this,{$hg_attr['state']},'{$hg_attr['show']}','{$hg_name}',{$hg_attr['is_sub']})){};" attrid="{$kk}" class="site_point" _id="{$kk}" title="{$vv}">{$vv}</span>
					{/foreach}
				</li>								
			{/foreach}
			</ul>
		</div>
		<ul class="arrow">
			<li class="left prev"></li>
			<li class="right next"></li>
		</ul>
		<input type="hidden" name="{$hg_name}" id="{$hg_name}" value="{$hg_value}">
	</div>
	<script type="text/x-jquery-tmpl" id="select-tpl">
		<ul class="list-box" >
			{{each option}}
				<li class="list-item">
					{{each option[$index]}}
						<span onclick="if({$hg_attr['func']}(this,{$hg_attr['state']},'{$hg_attr['show']}','{$hg_name}',{$hg_attr['is_sub']})){};" attrid="{{= $value[0]}}" class="site_point" _id="{{= $value[0]}}" title="{{= $value[1]}}">{{= $value[1]}}</span>
					{{/each}}
				</li>								
			{{/each}}
		</ul>
	</script>
</div>
<script>
	var infrm = {code} echo $_INPUT['infrm'] ? $_INPUT['infrm'] : 0; {/code};
	$( function(){
		if( !+infrm ){
			$('.site_list').site_list();
		}
	} );
</script>