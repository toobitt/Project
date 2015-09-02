{template:unit/header}
{css:live_interactive}
{code}
$channel 	  = $list['channel'];
$channel_id   = $channel['channel_id'];
$start_end	  = $list['start_end'];
$channel_name = $channel['channel_name'];
$channel_logo = $channel['channel_logo'];
$dates		  = $list['dates'];
$program	  = $list['program'];
$interactive_program	  = $list['interactive_program'];
$total_all	  = $list['total_all'];
$total_2	  = $list['total_2'];
$interactive  = $list['interactive'];
$interactive_2  = $list['interactive_2'];
$presenter_info	  = $list['presenter_info'];

//hg_pre($list);
/* css-class
 * letter-new:		亮麦	for:li
 * letter-normal:	暗麦	for:li
 * new-letter:		红字	for:a
 *new-program       红色字for:a-本期节目环节
 */
{/code}
<style>
.live-left-nav{position:absolute;width:130px;top:75px;margin:0px;left:20px;}
.chair-left{position:absolute;left:150px;width:710px;padding:0;margin:0 2% 0 10px;}
.chair-right{position:absolute;overflow:auto;left:890px;}
.customSelect{z-index:1;}
</style>
  {template:unit/head}
  
   <div class="live-content clearfix">
   		{template:unit/nav}
        <div class="chair-left">
              <div class="chair-left-title">
                    <span>听众来信</span>
                    <span class="chair-suggest" id="typeToggler">
                    	<em class="typeToggle current" data-type="0">导播推荐<a >({$total_2})</a></em>|
                    	<em data-type="-1" class="typeToggle">全部<a id="total_all">({$total_all})</a></em>
                    </span>
              </div>
              <div class="msgListWrap">
	              <ul class="live-area-list plr8" id="msgList">       	 
	              </ul>
	              <ul class="live-area-list plr8" style="display:none;" id="msgListAll">
	              </ul>
	          </div>
        </div>
        
        <div class="chair-right">
            <div class="program-box">
                 <div class="program-box-title"><span>本期节目环节</span></div>
                 <div class="program-box-con">
                       <ul class="program-list" id="programView">
                      {if !empty($interactive_program)}
                           {foreach $interactive_program AS $k => $v}
                            <li data-id="{$v['id']}" title="点击发布"><a href="#" {if $v['status']}class="current"{/if}><em class="program-date"></em><em>{$v['theme']}</em></a></li>
                           {/foreach}
                      {else}
                      		<li><a href="#" style="color:#f20000;"><em class="program-date">此时段没有节目环节</em></a></li>
                      {/if}
                      </ul>
                 </div>
            </div>
            <!--
<div class="program-box">
            <div class="program-box">
                 <div class="program-box-title"><span>互动项目</span></div>
                 <div class="program-box-con">
                      <ul class="likeFilm-list">
                            <li>
                               <span class="like-title">1.竞猜：2012年12月上映的电影，你最期待哪部？</span>
                               <ul>
                                    <li>血滴子</li>
                                    <li>十二生肖</li>
                                    <li>人在囧途之泰途</li>
                               </ul>
                            </li>
                            <li>
                               <span class="like-title">2.投票：男子点蜡烛求婚，你怎么看待这种行为？</span>
                            </li>
                            <li>
                               <span class="like-title">3.活动报名：幕府登高，俯视滔滔长江水</span>
                            </li>
                      </ul>
                 </div>
            </div>
            <div class="program-box">
                 <div class="program-box-title"><span>在线统计</span><span class="online-number"><em>当前在线：8500</em><em>今天</em></span></div>
                 <div class="program-box-con">
                 </div>
            </div>
-->
        </div>
   </div>

<script>
/*js需要的数据放这里*/
var globalData = window.globalData || {};
globalData.time_modal = {$time_modal};
globalData.current_program_index = {$current_programe};
globalData.zhi_play = {$zhi_play};
globalData.channel_id = {code}echo $channel_id ? $channel_id : 'null';{/code};
globalData.start_end = {code}echo $start_end ? "'$start_end'" : 'null';{/code};/*代表当前节目*/
globalData.dates = "{$dates}"; 
globalData.interactive = {code}echo json_encode(array_values($interactive));{/code};
globalData.interactive_2 = {code}echo json_encode(array_values($interactive_2));{/code};/*导播推荐的来信*/
globalData.program = {code}echo json_encode(array_values($program));{/code};/*节目单*/
globalData.interactive_program = {code}echo json_encode(array_values($interactive_program));{/code};/*当前节目的环节单*/
globalData.total_2 = {$total_2};
globalData.total_all = {$total_all};
globalData.in_program_id = {code}echo $list['in_program_id'] ? $list['in_program_id'] : "null"{/code};
globalData.fullNav = {$fullNav};
</script>  

<script type="text/temolate" id="template_program">
<% _.each(interactive_program, function ($v, index) { %>
	<li data-id="<%= $v['id'] %>" <%= $v['status'] == 1 ? 'class="current" index="' + index + '"' : '' %>><a><em class="program-date"></em><em><%= $v['theme'] %></em></a></li>
<% }); %>
</script>

<script type="text/template" id="template_msg">
	<div class="live-info">
		<a class="info-pic"><img src="<% if(avatar_url){ %><%- avatar_url %><% }else{ %>{$RESOURCE_URL}live/tem_pic.png<% } %>" width="36" height="36"></a>
		<div class="live-info-detail">
			<p class="info-descr"><a class="info-name"><%= member_name %>：</a><a class="new-letter"><%= content %></a></p>
			<p class="info-origin">
				<span>来自<em><%= plat_name %></em><em><%= create_time %></em></span>
			</p>
		</div>
		<a class="live-readBtn" title="标记已读"></a>
	</div>
</script>
{js:live_interactive/underscore}
{js:live_interactive/Backbone}
{js:live_interactive/customSelect}
{js:live_interactive/interactive_list}
{template:unit/footer}