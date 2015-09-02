{template:unit/header}
{css:live_interactive}
<script>
window.App = $({});
</script>
{js:live_interactive/underscore}
{js:live_interactive/customSelect}
{js:jquery.multiselect.min}
{code}
/*hg_pre($list);*/
$channel 	  = $list['channel'];
$dates 	  	  = $list['dates'];
$channel_id   = $channel['channel_id'];
$channel_name = $channel['channel_name'];
$channel_logo = $channel['channel_logo'];
$program	  = $list['program'];
$start_end	  = $list['start_end'];

$director_info	  = $list['director_info'];
$presenter_info	  = $list['presenter_info'];
$interactive_program	  = $list['interactive_program'];

$director	  = $list['director'];
$presenter	  = $list['presenter'];
$topic	  	  = $list['topic'];
$site_guests  = $list['site_guests'];
$otc_guests  = $list['otc_guests'];
$in_program_id = $list['in_program_id'];
$program_id = $list['program_id'];
$in_program = $list['in_program'];

//hg_pre(get_defined_vars());
//hg_pre($program);
{/code}
<style>
input{height:18px;padding: 3px 0 1px}
.live-head{z-index:1;top:0;left:0;}
.live-content{min-width:auto;}
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
.msgListWrap{position:relative;overflow-y:auto;width:100%;}
.live-bottom-controll{/*position:absolute;bottom:-16px;*/margin-bottom:10px;}
.notCurrent{display:none;}
a.hightlight{color:red;}
.noMsg{display:none;}
.customSelect{z-index:1;}
.futureModal{left:300px;}
.live-area-list .no-bottom-border{border:0;}
.live-area-list .cut-off-rule{margin:20px;border:0;height:1px;padding:0;background:#ccc;text-algin:center;}
.live-area-list .cut-off-rule label{position:absolute;left:260px;top:-10px;background:white;padding:0 20px;}

.formItem{margin:15px;}
.formItem > label{display:inline-block;text-align:right;width:70px;}
.formItem2 > label{float:left;}
.formItem2 > div{float:left;}
.addablefield p{margin-bottom:5px;}
.addBtn{display:inline-block;width:60px;height:25px;text-align:center;line-height:25px;background:#aaa;cursor:pointer;}
.w400{width:400px;}
.w50{width:55px;}
.w340{width:330px;}
.mr10{margin-right:10px;}
h2{font-size:18px;}
.submit{height:30px;margin-left:70px;}
.addablefield-del{cursor:pointer;display:none;}

p:hover .addablefield-del{display:inline;}
.addablefield-del.hide{display:none;}
p:hover .addablefield-del.hide{display:none;}

/*多选框*/
.ui-multiselect { padding:2px 0 2px 4px; text-align:left }
.ui-multiselect span.ui-icon { float:right }
.ui-multiselect-single .ui-multiselect-checkboxes input { position:absolute !important; top: auto !important; left:-9999px; }
.ui-multiselect-single .ui-multiselect-checkboxes label { padding:5px !important }

.ui-multiselect-header { margin-bottom:3px; padding:3px 0 3px 4px }
.ui-multiselect-header ul { font-size:0.9em }
.ui-multiselect-header ul li { float:left; padding:0 10px 0 0 }
.ui-multiselect-header a { text-decoration:none }
.ui-multiselect-header a:hover { text-decoration:underline }
.ui-multiselect-header span.ui-icon { float:left }
.ui-multiselect-header li.ui-multiselect-close { float:right; text-align:right; padding-right:0 }

.ui-multiselect-menu { display:none; padding:3px; position:absolute; z-index:10000; text-align: left }
.ui-multiselect-checkboxes { position:relative /* fixes bug in IE6/7 */; overflow-y:auto }
.ui-multiselect-checkboxes label { cursor:default; display:block; border:1px solid transparent; padding:3px 1px }
.ui-multiselect-checkboxes label input { height:auto; margin-right:5px; }
.ui-multiselect-checkboxes li { clear:both; font-size:0.9em; padding-right:3px }
.ui-multiselect-checkboxes li.ui-multiselect-optgroup-label { text-align:center; font-weight:bold; border-bottom:1px solid }
.ui-multiselect-checkboxes li.ui-multiselect-optgroup-label a { display:block; padding:3px; margin:1px 0; text-decoration:none }

/* remove label borders in IE6 because IE6 does not support transparency */
* html .ui-multiselect-checkboxes label { border:none }

</style>
{template:unit/head}
<script type="text/javascript">
	/*数据提交*/
	function hg_edit(form)
	{
	}
	function edit_form_back(obj)
	{	
		
	}
</script>
<div class="live-content">
	{template:unit/nav}
	<div class="live-right-area">
		<div class="chair-left-title">
			<h2>本期节目基本信息</h2>
		</div>
		<form class="metaInfoForm" name="edit_form" id="edit_form" action="./run.php?mid={$_INPUT['mid']}" method="POST">
			<div class="formItem">
				<label>节目导播：</label>
				<select class="select" multiple="multiple" data-title="选择导播" name="director_id[]">
				{if $director_info}
					{foreach $director_info AS $k => $v}
					<option {if in_array($k,$director)}selected="selected"{/if} value="{$k}">{$v}</option>
					{/foreach}
				{/if}
				</select>
			</div>
			<div class="formItem">
				<label>主持人：</label>
				<select class="select" multiple="multiple" data-title="选择主持人" name="presenter_id[]">
				{if $presenter_info}
					{foreach $presenter_info AS $k => $v}
					<option {if in_array($k,$presenter)}selected="selected"{/if} value="{$k}">{$v}</option>
					{/foreach}
				{/if}
				</select>
			</div>
			<div class="formItem">
				<label>微博账号：</label>
				<select class="select" multiple="multiple" data-title="选择微博账号" name="member_id[]">
				{if $member_info}
					{foreach $member_info AS $k => $v}
					<option {if in_array($v['id'],$in_program[0]['member_id'])}selected="selected"{/if} value="{$v['id']}">{$v['member_name']}</option>
					{/foreach}
				{/if}
				</select>
			</div>
			<div class="formItem formItem2 clearfix">
				<label>本期话题：</label>
				<div class="addablefield">
					{code}
						$topic = $topic ? $topic : array(array());
					{/code}
					{foreach $topic AS $k => $v}
					<p>
						<input class="w400" value="{$v['name']}" name="topic_name[]" />
						<input type="hidden" name="topic_id[]" value="{$v['id']}" />
						<span class="addablefield-del" data-type="topic_id">删除</span>
					</p>
					{/foreach}
					<p><span class="addBtn">添加话题</span></p>
				</div>
			</div>
			<div class="formItem formItem2 clearfix">
				<label>现场嘉宾：</label>
				<div class="addablefield">
					{code}
						$site_guests = $site_guests ? $site_guests : array(array());
					{/code}
					{foreach $site_guests AS $k => $v}
					<p>
						<input class="w50 mr10" placeholder="姓名" value="{$v['name']}" name="site_guests_name[]" />
						<input class="w340" placeholder="个人主页地址" value="{$v['profile']}" name="site_guests_profile[]" />
						<input type="hidden" name="site_id[]" value="{$v['id']}" />
						<span class="addablefield-del" data-type="site_id">删除</span>
					</p>
					{/foreach}
					<p><span class="addBtn">添加嘉宾</span></p>
				</div>
			</div>
			<div class="formItem formItem2 clearfix">
				<label>场外嘉宾：</label>
				<div class="addablefield">
					{code}
						$otc_guests = $otc_guests ? $otc_guests : array(array());
					{/code}
					{foreach $otc_guests AS $k => $v}
					<p>
						<input class="w50 mr10" placeholder="姓名" value="{$v['name']}" name="otc_guests_name[]" />
						<input class="w340" placeholder="个人主页地址" value="{$v['profile']}" name="otc_guests_profile[]" />
						<input type="hidden" name="otc_id[]" value="{$v['id']}" />
						<span class="addablefield-del" data-type="otc_id">删除</span>
					</p>
					{/foreach}
					<p><span class="addBtn">添加嘉宾</span></p>
				</div>
			</div>
			<div class="formItem msgShow">
			</div>
			<div class="formItem">
				<input type="submit" value="保存" class="submit" />
				<input type="hidden" value="edit" name="a" />
				<input type="hidden" value="{$in_program_id}" name="in_program_id" />
				<input type="hidden" value="{$dates}" name="dates" />
				<input type="hidden" value="{$channel_id}" name="channel_id" />
				<input type="hidden" value="{$start_end}" name="start_end" />
				<input type="hidden" value="{if $program_id}{$program_id}{else}{$program[$current_programe]['id']}{/if}" name="program_id" />
			</div>
		</form>
	</div>
	{template:unit/programEdit}
</div>
<script>
var globalData = window.globalData || {};
globalData.time_modal = {$time_modal};
globalData.channel_id  = {$channel_id};
globalData.program = {code}echo json_encode($program);{/code};
globalData.current_program_index = {$current_programe};
globalData.zhi_play = {$zhi_play};
</script>
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
<script>
$(function ($) {
	var form = $(".metaInfoForm");
	App.on("add_in_program_id", function (event, id) {
		form.find('.formItem input[name=in_program_id]').val(id);
	});
	form.find(".select").each(function (n) {
		var select = $(this);
		select.multiselect({
			checkAllText: '全选',
			uncheckAllText: '取消全选',
			noneSelectedText: select.data( 'title' ),
			selectedList: 10,
			//minWidth: 500,
			beforeopen: function () {
				
			},
			close: function () {
				
			}
		});
	});
	function initUi() {
		form.find(".addablefield").each(function () {
			var p = $(this).find("p");
			if (p.size() > 2) {
				p.eq(0).find(".addablefield-del").removeClass("hide");
			} else {
				p.eq(0).find(".addablefield-del").addClass("hide");
			}
		});
	}
	initUi();
	var typeDict = {
		"topic_id": 1,
		"site_id": 2,
		"otc_id": 3
	};
	form.on('click', '.addBtn', function () {
		var p = $(this).parent();
		var el = p.prev().clone().find("input").val('').end();
		el.find(".addablefield-del").removeClass("hide");
		p.before(el);
		initUi();
	}).on("click", ".addablefield-del", function () {
		var btn = $(this),
			type = btn.data("type"),
			id = btn.parent().find("input[name*=" + type + "]").val();
		
		if ( id ) {
			jConfirm("你确定要删除吗?", "删除提醒", function (yes) {
				if (yes) {
					btn.closest("p").remove();
					$.post("run.php", {
						mid: gMid,
						a: "delete_basic_info",
						type: typeDict[type],
						id: id
					});
				}
			});
		} else {
			btn.closest("p").remove();
		}
		initUi();
	}).submit(function () {
		form.ajaxSubmit({
			beforeSend: function () {
				form.find("input:submit").prop("disabled", true);
				form.find(".msgShow").html("<P style=\"margin-left: 70px;\">保存中，请等待...</p>");
			},
			success: function (data) { 
				form.find("input:submit").prop("disabled", false);
				var el = $("<P style=\"margin-left: 70px;\">保存成功！</p>");
				form.find(".msgShow").html(el);
				el.fadeOut(4000, function () { el.remove() });
	
				data = $.parseJSON(data)[0];
				$.each(["topic_id", "site_id", "otc_id"], function (i, idname) {
					var p = form.find(".addablefield").eq(i).find("p");
					$.each(data[idname], function (ii, n) {
						p.eq(ii).find("input[name*=" + idname + "]").val(n);
					});
				});
				App.trigger("add_in_program_id", [ data.in_program_id ]);
			}
		});
		return false;
	});
});
</script>
{template:unit/footer}