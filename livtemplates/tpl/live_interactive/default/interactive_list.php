{template:unit/header}
{css:live_interactive}
<script>
window.App = $({});
</script>
{js:live_interactive/underscore}
{js:live_interactive/Backbone}
{js:live_interactive/customSelect}
{js:live_interactive/interactive_d}
{code}
$channel 	  = $list['channel'];
$dates 	  	  = $list['dates'];
$channel_id   = $channel['channel_id'];
$channel_name = $channel['channel_name'];
$channel_logo = $channel['channel_logo'];
$program	  = $list['program'];
$start_end	  = $list['start_end'];
$director_info	  = $list['director_info'];
$interactive_program	  = $list['interactive_program'];
$first_data = $interactive_program[0];
$presenter	  = $list['presenter'];
$in_program_id = $list['in_program_id'];
$program_id = $list['program_id'];
$interactive = $list['interactive'];
//unset($list['channel'], $list['program'], $list['start_end'], $list['director_info'], $list['dates'], $list['interactive_program'], $list['presenter'], $list['in_program_id'], $list['interactive']);
//hg_pre($list);
{/code}
<style>
html{overflow:hidden;}
.live-head{z-index:1;top:0;left:0;}
.live-content{min-width:auto;}
.live-left-nav{position:absolute;width:130px;top:75px;margin:0px;left:20px;}
.live-right-area{position:absolute;left:150px;width:710px;padding:0;}
.live-program-area{position:absolute;overflow:auto;left:890px;top:75px;right:20px;width:321px;}
.live-program-area form{background:white;padding:20px;}
.programeItem{background:#ccc;padding:8px 10px 8px 5px;margin-bottom:1px;}
.programeItem p{width:10px;height:10px;margin-top:8px;background:white;margin-right:10px;float:left;}
.programeItem input{width:200px;}
.delBtn{cursor: pointer; background: url("{$RESOURCE_URL}close_plan.png") no-repeat; height:16px;width: 16px;position:relative;top:5px;display:inline-block;visibility:hidden;}
.programeItem:hover .delBtn{visibility:visible;}
.addProgram{cursor:pointer;margin:10px 0;display:block;}
.s_title{font-size:16px;padding-bottom:10px;}
._title_{border-bottom:1px dotted;padding-bottom:15px;margin-top:15px;}
._title_ p{margin-bottom:5px;}
._title_ label{margin-right:8px;}
._title_ label input{margin-right:4px;}
._title_:last-child{border:none;}

.live-bottom-controll{/*position:absolute;bottom:-16px;*/margin-bottom:10px;}
.notCurrent{display:none;}
a.hightlight{color:red;}
.noMsg{display:none;}
.customSelect{z-index:1;}
.futureModal{left:300px;}
.live-area-list .no-bottom-border{border:0;}
.live-area-list .cut-off-rule{margin:20px;border:0;height:1px;padding:0;background:#ccc;text-algin:center;}
.live-area-list .cut-off-rule label{position:absolute;left:260px;top:-10px;background:white;padding:0 20px;}

.message{cursor:pointer;line-height:30px;margin: 10px;text-align: center;background:#FEFDED;color: #F48C12;border: 1px solid #F9F2A7;}
.message:hover{cursor:pointer;line-height:30px;margin: 10px;text-align: center;background:RGB(255,253,224);color: #F48C12;border: 1px solid #F9F2A7;text-decoration: underline;}
</style>
{template:unit/head}
<div class="live-content">
	{template:unit/nav}
	
	<div class="live-right-area" {if $time_modal == 1}style="display:none;"{/if}>
		<div class="chair-left-title">
			<span>听众来信</span>
			<span class="chair-suggest" id="typeToggler">
				<em class="typeToggle current" data-type="-1">全部<a >({$list['total_all']})</a></em>| 
				<em data-type="0" class="typeToggle">推荐总数<a id="total_all">({$list['total_2']})</a></em>
			</span>
		</div>
		<div id="newMsgNum" style="{if $time_modal}display:none;{/if}" class="message">
			0条新消息
		</div>
		<div class="msgListWrap">
			<ul class="live-area-list" id="msgListAll">
			{if $interactive}
			{foreach $interactive as $v}
				<li class="clearfix msgItem">
					<a class="live-check-box"><input type="checkbox" title="{$v['id']}"></a> 
					<div class="live-info">
						<a class="info-pic"><img src="{if $v['avatar_url']}{$v['avatar_url']}{else}{$RESOURCE_URL}live/tem_pic.png{/if}" width="36" height="36"></a>
						<div class="live-info-detail">
							<p class="info-descr"><a class="info-name">{$v['member_name']}</a>&nbsp;&nbsp;<a>{$v['content']}</a></p>
							<p class="info-origin">
								<span>来自<em>{$v['plat_name']}</em><em></em><em>{$v['create_time']}</em></span>
								<span class="info-controll">   
									<!-- <a class="audit">{code}echo $v['status'] == 1 ? '打回' : '审核';{/code}</a>| -->
									<a class="type">推荐</a>|
									<a class="type">警告</a>|
									<a class="type">屏蔽</a>
									<!--  <a class="delete">删除</a>-->
								</span>
							</p>
						</div>
					</div>
				</li>
			{/foreach}
			{else}
				<li class="emptyTip"><p class="emptyTip">没有此类来信！</p></li>
			{/if}
			</ul>
			
			<ul class="live-area-list notCurrent" id="msgList"></ul>
			
    	</div>
		
		<div class="live-bottom-controll" id="batBar-1">
			<a class="all-check-box"><input type="checkbox" title="全选" value="infolist" name="checkall" id="checkall"></a>
			<span class="live-global-controll">
				<!--
<a class="audit" data-v="1">审核</a>
				<a class="audit" data-v="2">打回</a>
-->
				<a class="interactive_operate" data-v="2">推荐</a>
				<a class="interactive_operate" data-v="3">警告</a>
				<a class="interactive_operate" data-v="4">屏蔽</a>
				<!--<a class="delete">删除</a>-->
			</span>
		</div>
		
		<div class="live-bottom-controll notCurrent" id="batBar0">
			<a class="all-check-box"><input type="checkbox" title="全选" value="infolist" name="checkall" id="checkall"></a>
			<span class="live-global-controll">
				<a>取消推荐</a>
			</span>
		</div>
	</div>

	{template:unit/programEdit}	
</div>

<script type="text/template" id="template_program">
<% _.each(interactive_program, function ($v) { %>
<div class="programeItem">
	<p></p>
	<input name="theme[]" data-default="<%- $v['theme'] %>" value="<%- $v['theme'] %>" />
	<span class="delBtn" data-saved="1" />
	<input type="hidden" name="ids[]" value="<%- $v['id'] %>" />
	<input class="flag" name="flag[]" type="hidden" />
</div>
<% }); %>
</script>

<script type="text/template" id="template_msg">
<a class="live-check-box"><input type="checkbox" title="<%- id %>"></a> 
<div class="live-info">
	<a class="info-pic"><img src="<% if(avatar_url){ %><%- avatar_url %><% }else{ %>{$RESOURCE_URL}live/tem_pic.png<% } %>" width="36" height="36"></a>
	<div class="live-info-detail">
		<p class="info-descr"><a class="info-name"><%- member_name %></a>&nbsp;&nbsp;<a><%- content %></a></p>
		<p class="info-origin">
			<span>来自<em><%- plat_name %></em><em></em><em><%- create_time %></em></span>
			<span class="info-controll">   
				<!--<a class="audit"><%- status == 1 ? '打回' : '审核' %></a>| -->
				<a class="type">推荐</a>|
				<a class="type">警告</a>|
				<a class="type">屏蔽</a>
			</span>
		</p>
	</div>
</div>
</script>
<script>
var globalData = window.globalData || {};
globalData.time_modal = {$time_modal};
globalData.channel_id  = {$channel_id};
globalData.program = {code}echo json_encode($program);{/code};
globalData.current_program_index = {$current_programe};
globalData.zhi_play = {$zhi_play};
globalData.interactive_program = {code}echo json_encode($interactive_program);{/code};
globalData.interactive = {code}echo json_encode(array_values($interactive));{/code};
globalData.total_all = {$list['total_all']};
globalData.total_2 = {$list['total_2']};
globalData.start_end = '{$list['start_end']}';
globalData.dates = "{$dates}"; 
</script>


{template:footer}