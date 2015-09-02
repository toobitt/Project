<?php
/* $Id: station_search.php 87 2011-06-21 07:10:24Z repheal $ */
?>
{template:head}
<div class="vui">
	<div class="con-left">
		
		<div class="station_content">
			<div class="station_top search_top">
				<form action="" method="post">
					<input class="search_in" type="text" name="k" value="{$name}"/>
					<input class="search_bt" type="submit" value="搜索"/>
				</form>
			</div>
					
			{if !$station_info}
				<div class="search_li">
				{code}
					$search_content = $name;
				{/code}
					{template:unit/null_search}
				</div>
			{else}
			<div class="con_top">检索结果</div>
				<div class="pop" id="pop">
					<span style="font-size:12px;color:#0082CB;width:auto;" onclick="closevideo()">关闭</span>
					<div id="pop_s"></div>
				</div>
				<div class="con_middle con_middle_search">
				{if is_array($station_info)}
					<ul>
					{foreach $station_info as $key => $value}
							<li class="search_li">
								<ul class="search_result">
									<li><a target="_blank" href="<?php echo hg_build_link(SNS_VIDEO."station_play.php",array("sta_id"=>$value['id']));?>"><img src="{$value['small']}"/></a></li>
									<li>名称：<a target="_blank" href="<?php echo hg_build_link(SNS_VIDEO."station_play.php",array("sta_id"=>$value['id']));?>"><?php echo hg_match_red(hg_cutchars($value['web_station_name'],7," "),$name);?></a></li>
									<li>标签：<?php echo $value['tags']?hg_tags(hg_cutchars($value['tags'],7," "),$name,"station_search.php"):"暂无";?></li>
									<li>点击次数：{$value['click_count']}</li>
								</ul>
							</li>
						{/foreach}
					</ul>
					{$showpages}
					{/if}
				</div>
				<div class="con_bottom clear"></div>
			{/if}
		</div>
	</div>
	{template:unit/my_right_menu}
</div>
{template:foot}