<?php 
/* $Id: list.php 18206 2013-03-20 02:07:46Z yizhongyue $ */
?>
<script type="text/javascript">
function hg_chg_state(id, clew)
{
	$('#is_use_' + id).html(clew);
}
function hg_show_mod_input(id)
{
	$('#space_' + id).hide();
	$('#space_input_' + id).show();
	$('#space_input_' + id).focus();
}
function hg_submit_space(id)
{
	var space = $('#space_input_' + id).val();
	var oldspace = $('#space_' + id).html();
	if (oldspace != space)
	{
		var url = 'settings.php?app_uniqueid={$app_uniqueid}&id=' + id + '&a=modify_space&space=' + space;
		hg_ajax_post(url);
	}
	else
	{
		hg_chg_space(id, space);
	}
}
function hg_chg_space(id, space)
{
	$('#space_' + id).show();
	$('#space_input_' + id).hide();
	$('#space_' + id).html(space);
}
</script>
<style type="text/css">
    .sure {position: absolute;bottom: -7px;left: -50px;width: 40px;text-align: center;height: 28px;line-height: 28px;background: #8fa8c6;cursor:pointer;color:#FFFFFF;border-radius:2px;}
</style>
<!-- 
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list form_table">
	<tr class="h" align="left" valign="middle">
		<th width="50" class="left"></th>
		<th class="left">名称</th>
		<th width="200" class="left">任务文件</th>
		<th class="left">执行间隔</th>
		<th class="left">下次执行时间</th>
		<th class="left">是否启用</th>
	</tr>
{if $crontabs}
		{foreach $crontabs AS $k => $v}
		<tr title="{$v['brief']}">
		<td class="left"></td>
		<td class="left">{$v['name']}&nbsp;</td>
		<td class="left">{$v['file_name']}&nbsp;</td>
		<td class="left"><span id="space_{$v['id']}" onclick="hg_show_mod_input({$v['id']});">{$v['space']}</span><input type="text" size="3" id="space_input_{$v['id']}" value="{$v['space']}" onblur="hg_submit_space({$v['id']})" style="display:none;" />s</td>
		<td class="left">{$v['run_time']}</td>
		<th class="left"><a href="settings.php?app_uniqueid={$app_uniqueid}&id={$v['id']}&a=chgstate" id="is_use_{$v['id']}" _op="{$v['op']}" onclick="return hg_ajax_post(this,'', 0);">{$v['is_use']}</a></th>
		</tr>
		{/foreach}
{/if}
</table>
 -->
{css:common/common_list}
{css:setting}
<ul class="common-list public-list">
	<li class="common-list-head">
		<div class="common-list-left">
			<div class="common-list-item wd100">名称</div>
			<div class="common-list-item kong wd50"></div>
		</div>
		<div class="common-list-right">
			<div class="common-list-item wd80">执行间隔</div>
			<div class="common-list-item wd150">下次执行时间</div>
			<div class="common-list-item wd80">是否启用</div>
		</div>
		<div class="common-list-biaoti">
			<div class="common-list-item">任务文件</div>
		</div>
	</li>
</ul>
<ul class="common-list public-list">
{if $crontabs}
	{foreach $crontabs AS $k => $v}
	<li class="common-list-data">
		<div class="common-list-left">
			<div class="common-list-item wd100"><span style="cursor:pointer;">{$v['name']}</span></div>
			<div class="common-list-item kong wd50"></div>
		</div>
		<div class="common-list-right">
			<div class="common-list-item wd80">
                <span style="cursor:pointer;">
                    <span id="space_{$v['id']}" onclick="hg_show_mod_input({$v['id']});">{$v['space']}</span>
                    <input type="text" size="3" id="space_input_{$v['id']}" value="{$v['space']}" onblur="hg_submit_space({$v['id']})" style="display:none;" />s
                </span>
            </div>
			<div class="common-list-item wd150">
                <span style="cursor:pointer; position: relative;" class="cron-next-time">
                    <span class="next_time" _id="{$v['id']}">{$v['run_time']}</span>
                    <a class="sure" style="display: none;">更新</a>
                    <input type="text" size="23"  _id="{$v['id']}" class="next_time_input date-picker hasDatepicker" _time="true" _second=true value="{$v['run_time']}" style="display:none;" />
                </span>
            </div>
			<div class="common-list-item wd80"><span style="cursor:pointer;"><a href="settings.php?app_uniqueid={$app_uniqueid}&id={$v['id']}&a=chgstate" id="is_use_{$v['id']}" _op="{$v['op']}" onclick="return hg_ajax_post(this,'', 0);" style="color:#8FA8C6;">{$v['is_use']}</a></span></div>
		</div>
		<div class="common-list-biaoti biaoti-transition">
			<div class="common-list-item"><span style="cursor:pointer;">{$v['file_name']}</span></div>
		</div>
	</li>
	{/foreach}
{/if}
</ul>

<script type="text/javascript">

$(document).ready(function(){
    $(".cron-next-time .next_time").click(function(){
        $(this).hide();
        $(this).parent().find("a.sure").show();
        $(this).parent().find("input.next_time_input").show().focus();
    });


    $('.cron-next-time a.sure').click(function(){
        var time_input = $(this).parent().find("input.next_time_input"),
            time_span = $(this).parent().find("span.next_time");

        var next_time = $(time_input).val();
        var old_next_time = $(time_span).html();
        if (next_time != old_next_time) {

            var id = $(time_input).attr("_id");
            if (id) {
                $.ajax({
                    type: "GET",
                    url: 'settings.php?app_uniqueid={$app_uniqueid}&id=' + id + '&a=modify_next_time&next_time=' + next_time + '&ajax=1'
                });
            } else {
                $(this).myTip({
                    string: "数据异常,修改失败"
                });
            }
        }

        $(this).hide();
        $(time_input).hide();
        $(time_span).show().html(next_time);
    });
});

</script>

