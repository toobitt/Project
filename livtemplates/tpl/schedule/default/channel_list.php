<?php 
/* $Id: channel_list.php 26665 2013-09-11 06:50:48Z wangleyuan $ */
?>
{code}
    //hg_pre($list);
function getCurrentDay($date){
    $date = explode('-', $date);
    return $date[2];
}
$today = $list['dates'];
$weekFlip = array_flip($list['week']);
$index = $weekFlip[$today];
$months = array('一', '二', '三', '四', '五', '六', '七', '八', '九', '十', '十一', '十二');
$currentMonth = $months[(int)$list['month'] - 1] . '月';
$months = json_encode($months);
{/code}
{template:head}
{template:list/common_list}
{css:2013/week}
{js:2013/week}
<script>
var months = {$months};
var today = '{$today}';
var thisweek = '{$list["this_week"]}';
var currentIndex = '{$index}';
</script>
<div class="common-list-content" style="min-height:auto;min-width:auto;">
<style>
.paixu{display:none !important;}
.plan-button{position:absolute;right:10px;top:10px;line-height:28px;color:#fff;width:114px;height:28px;text-align:center;border-radius:2px;background:url({$RESOURCE_URL}split/small-blue.jpg);}
.plan-button:hover{background:url({$RESOURCE_URL}split/small-blue-hover.jpg);}
.channel-pd-name, .channel-pd-taihao{font-size:14px;}
@media only screen and (-webkit-min-device-pixel-ratio: 2),
only screen and (-moz-min-device-pixel-ratio: 2),
only screen and (-o-min-device-pixel-ratio: 2/1),
only screen and (min-device-pixel-ratio: 2) { 
	.plan-button{background-image:url({$RESOURCE_URL}split/small-blue-2x.jpg);background-size:2px 28px;}
	.plan-button:hover{background-image:url({$RESOURCE_URL}split/small-blue-hover-2x.jpg);background-size:2px 28px;}
}
</style>
{if !$list}
	<p style="color: #da2d2d; text-align: center; font-size: 20px; line-height: 50px; font-family: Microsoft YaHei;">没有您要找的内容！</p>
	<script>hg_error_html('p',1);</script>	
{else}
	<form method="post" action="" name="listform">
		<ul class="common-list">
			<li class="common-list-head public-list-head clear">
				<div class="common-list-left">
					<div class="common-list-item paixu">
						<a title="排序模式切换/ALT+R" style="cursor:pointer;" class="common-list-paixu" id="is_order"></a>
					</div>
					<div class="common-list-item wd120">台标</div>
				</div>
				<div class="common-list-right">
					<div class="common-list-item week-box">
					    <span class="self-week-title">
                            {foreach $list['short_week'] as $kk => $vv}
                                <a>{$vv}</a>
                            {/foreach}
					    </span>
					</div>
				</div>
				<div class="common-list-biaoti">
					<div class="common-list-item">频道名称/台号</div>
				</div>
			</li>
		</ul>
		<ul id="channellist" class="common-list channel-list  public-list">
		{foreach $list['channel_info'] AS $k => $v}
			<li class="common-list-data clear" orderid="{$v['order_id']}"  id="r_{$v['id']}" class="h"   name="{$v['id']}">
				<div class="common-list-left">
					<div class="common-list-item paixu">
						<a name="alist[]">
							<input type="checkbox" title="{$v['id']}" value="{$v['id']}" name="infolist[]" id="primary_key_19">
						</a>
					</div>
					<div class="common-list-item wd120">
						{if $v['logo_rectangle_url']}
						<img src="{$v['logo_rectangle_url']}" width="90" height="30" class="img-middle"/>
						{/if}
					</div>
				</div>
				<div class="common-list-right ">
					<div class="common-list-item week-box">
					    <span class="self-month">{$currentMonth}</span>
					    <span class="prev-week-btn" title="上一周">&lt;</span>
					    <span class="self-week" data-week="{$list['this_week']}" data-channelid="{$v['id']}">
                        {foreach $list['week'] as $kk => $vv}
                        <a class="{if $v['is_schedule'][$kk] > 0}is-set{/if}" href="run.php?a=relate_module_show&app_uniq=schedule&mod_uniq=schedule&mod_main_uniq=channel&mod_a=show&channel_id={$v['id']}&infrm=1&dates={$vv}" target="mainwin" data-date="{$vv}" title="{$vv}">{code}echo getCurrentDay($vv);{/code}</a>
                        {/foreach}
                        </span>
                        <span class="next-week-btn" title="下一周">&gt;</span>
					</div>
				</div>
				<a class="plan-button">查看节目计划</a>
				<div class="common-list-biaoti">
					<div class="common-list-item biaoti-transition">
							<span style="cursor:pointer;"><span><a href="run.php?a=relate_module_show&app_uniq=schedule&mod_uniq=schedule&mod_main_uniq=channel&mod_a=show&channel_id={$v['id']}&infrm=1" target="mainwin"><span class="channel-pd-name m2o-common-title">{$v['name']}</span><span class="channel-pd-taihao">{$v['code']}</span></a></span></span>
					</div>
				</div>
			</li>
		{/foreach}
		</ul>
		<ul class="common-list public-list">
			<li class="common-list-bottom clear">
				<div class="common-list-left paixu">
					<input type="checkbox" title="全选" value="infolist" name="checkall">
				</div>
				{$pagelink}
			</li>
		</ul>
	</form>
{/if}
</div>

{template:foot}