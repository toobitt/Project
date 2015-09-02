<?php 
/* $Id: program_list_week.php 5656 2011-12-10 09:30:02Z repheal $ */
?>
{template:head}
{js:program_plan}
{template:list/common_list}
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
 	<a class="add-button mr10" href="?mid={$_INPUT['mid']}&a=form&infrm=1&channel_id={code} echo $_INPUT['channel_id'] . '&channel_name=' . $_INPUT['channel_name'] . $_ext_link;{/code}" target="formwin">新增计划</a>
</div>
<div class="common-list-content" style="min-height:auto;min-width:auto;">
<ul class="common-list public-list-head">
	<li class="common-list-head public-list-head clear">
		<div class="common-list-left"></div>
		<div class="common-list-right" style="margin-right: -10px;">
			<div class="common-list-item wd180">日期范围</div>
			<div class="common-list-item wd100">播放时间</div>
			<div class="common-list-item wd100">周期</div>
			<div class="common-list-item wd80">操作</div>
		</div>
		<div class="common-list-biaoti">
			<div class="common-list-item biaoti-transition">节目名称</div>
		</div>
	</li>
</ul>
<form method="post" action="" name="listform">
<style>
.paixu{display:none !important;}
</style>
{code}
$channel = $plan['channel_info'];
$plan = $plan['program_plan'];
{/code}

{if !$plan}
	<p style="color: #da2d2d; text-align: center; font-size: 20px; line-height: 50px; font-family: Microsoft YaHei;">没有您要找的内容！</p>
	<script>hg_error_html('p',1);</script>	
{else}
	<ul id="channellist" class="common-list channel-list  public-list">
	{foreach $plan AS $k => $v}
		<li class="common-list-data public-list-head clear" id="plan_{$v['id']}">
			<div class="common-list-left"></div>
			<div class="common-list-right">
				<div class="common-list-item wd180">{$v['start_date']}~{code} echo $v['toff'] ? $v['end_date'] : '无限';{/code}</div>
				<div class="common-list-item wd100">{$v['start']}</div>
				<div class="common-list-item week-box wd100">
				    <span class="self-week-title">
                        {foreach $v['week_day_ch'] as $kk => $vv}
                            <a>{$vv}</a>
                        {/foreach}
				    </span>
				</div>
				<div class="common-list-item wd80">
					<a href="?mid={$_INPUT['mid']}&id={$v['id']}&a=form&channel_id={code} echo $_INPUT['channel_id'] . '&channel_name=' . $_INPUT['channel_name'] . $_ext_link;{/code}" target="formwin">编辑</a> <a class="plan-delete" href="javascript:void(0);" onclick="hg_plan_delete(this);" _href="?mid={$_INPUT['mid']}&id={$v['id']}&a=delete&channel_id={code} echo $_INPUT['channel_id'] . '&channel_name=' . $_INPUT['channel_name'] . $_ext_link;{/code}">删除</a>
				</div>
			</div>
			<div class="common-list-biaoti">
				<div class="common-list-item biaoti-transition">{$v['program_name']}</div>
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
{/if}
</form>
</div>
<div onclick="hg_check_plan();" class="show_bg" id="show_bg"></div>