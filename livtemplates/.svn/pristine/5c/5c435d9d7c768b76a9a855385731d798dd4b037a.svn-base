{template:head}
{code}
if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}
$attr_date = array(
	'class' => 'colonm down_list data_time',
	'show' => 'colonm_show',
	'width' => 104,/*列表宽度*/
	'state' => 1,/*0--正常数据选择列表，1--日期选择*/
	'is_sub'=> 0,
);

/*状态控件*/
$status_source = array(
	'class' => 'transcoding down_list',
	'show' => 'status_show',
	'width' => 104,/*列表宽度*/
	'state' => 0,/*0--正常数据选择列表，1--日期选择*/
);

if(!isset($_INPUT['status']))
{
    $_INPUT['status'] = 0;
}

/*申请类型控件*/
$type_source = array(
	'class' => 'transcoding down_list',
	'show' => 'type_show',
	'width' => 104,/*列表宽度*/
	'state' => 0,/*0--正常数据选择列表，1--日期选择*/
);

if(!isset($_INPUT['type']))
{
    $_INPUT['type'] = 0;
}

{/code}
{css:vod_style}
{template:list/common_list}
<div class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}></div>
<div class="content clear">
<div class="f">

	   <div class=''>总用户数：{$ret[0]['all_user_count']}</div>
       
       
       
       总app数：{$ret[0]['all_app_count']}<br/>
       三个月内活跃过的app：{$ret[0]['three_month_activate']}<br/>
       1个月内活跃过的app：{$ret[0]['one_month_activate']}<br/>
       每日新增用户：{$ret[0]['day_add_user_count']}<br/>
       每日新增APP：{$ret[0]['day_add_app_count']}<br/>
       每日活跃app：{$ret[0]['day_activate_app']}<br/>
       每日活跃前10：
       {if $ret[0]['top_ten']}
           {foreach $ret[0]['top_ten'] as $k => $v}
               {$v['app_info']['name']}<br/>
           {/foreach}
       {/if}
</div>
   <div id="infotip"  class="ordertip"></div>
</div>
{template:foot}