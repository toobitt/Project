{template:unit/header}
{css:live_interactive}
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
$interactive = $list['interactive'];
//hg_pre(get_defined_vars());
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

<div class="live-content">
	{template:unit/nav}
	<div class="live-right-area">
		<div class="chair-left-title">
			<h2>本期节目基本信息</h2>
		</div>
		<form class="metaInfoForm">
			<div class="formItem">
				<label>节目导播：</label>
				<select class="select" multiple="multiple" data-title="选择导播">
					<option selected>你节目导播</option>
					<option>我节目导播</option>
					<option>他节目导播</option>
				</select>
			</div>
			<div class="formItem">
				<label>主持人：</label>
				<select class="select" multiple="multiple" data-title="选择主持人">
					<option>你</option>
					<option>我</option>
					<option>他</option>
				</select>
			</div>
			<div class="formItem">
				<label>微博账号：</label>
				<select class="select" multiple="multiple" data-title="选择微博账号">
					<option>你</option>
					<option>我</option>
					<option>他</option>
				</select>
			</div>
			<div class="formItem formItem2 clearfix">
				<label>本期话题：</label>
				<div class="addablefield">
					<p><input class="w400" /></p>
					<p><span class="addBtn">添加话题</span></p>
				</div>
			</div>
			<div class="formItem formItem2 clearfix">
				<label>现场嘉宾：</label>
				<div class="addablefield">
					<p><input class="w50 mr10" placeholder="姓名" /><input class="w340" placeholder="个人主页地址" /></p>
					<p><span class="addBtn">添加嘉宾</span></p>
				</div>
			</div>
			<div class="formItem formItem2 clearfix">
				<label>场外嘉宾：</label>
				<div class="addablefield">
					<p><input class="w50 mr10" placeholder="姓名" /><input class="w340" placeholder="个人主页地址" /></p>
					<p><span class="addBtn">添加嘉宾</span></p>
				</div>
			</div>
			<div class="formItem">
				<input type="submit" value="保存" class="submit" />
			</div>
		</form>
	</div>
	{template:unit/programEdit}
</div>

{js:jquery.multiselect.min}
<script>
$(function ($) {
	$(".select").each(function (n) {
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
	var form = $(".metaInfoForm");
	form.on('click', '.addBtn', function () {
		var p = $(this).parent();
		p.before( p.prev().clone().find("input").val('').end() );
	});
});
</script>
{template:unit/footer}