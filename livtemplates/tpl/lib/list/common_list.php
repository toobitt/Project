{code}

$list_setting['status_color'] = $_configs['status_color'];
$status_key = isset($status_key) ? $status_key : 'state';
$audit_value = isset($audit_value) ? $audit_value : 1;
$audit_label = isset($audit_label) ? $audit_label : '已审核';
$back_value = isset($back_value) ? $back_value : 2;
$back_label = isset($back_label) ? $back_label : '已打回';

$default_attrs_for_edit = array(
	'id', 'title', 'status', 'state', 'special_id', 'click_num', 'share_num', 'expand_id','block',
	'click_count' ,'comm_num', 
	'img_info', 'downcount', 
	'video_url', 'weight', 
	'sampling_rate',
	'outlink'
);
$attrs_for_edit = isset($attrs_for_edit) ? 
	array_merge($attrs_for_edit, $default_attrs_for_edit) : 
	$default_attrs_for_edit;

function utils_pluck(&$arr, $attrs, $status_key) {
	$ret = array();
	$attrs = is_array($attrs) ? $attrs : array($attrs);
	foreach ($arr as $k => $v) {
		foreach($attrs as $attr) {
			$ret[$k][$attr] = $v[$attr];
		}
		$ret[$k]['status'] = $v[$status_key];
		$ret[$k]['state'] = $v[$status_key];
	}
	return $ret;
}
$js_globalData = utils_pluck($list, $attrs_for_edit, $status_key);

{/code}
{css:common/common_list}
{js:underscore}
{js:Backbone}
{js:jqueryfn/jquery.tmpl.min}
{js:domcached/jquery.json-2.2.min}
{js:domcached/domcached-0.1-jquery}
{js:common/common_list}
{js:2013/ajaxload_new}
<!-- 待合并 -->
{js:common/ajax_cache}
{js:common/record}
{js:common/record_view}
{js:common/weight_box}
{js:common/action_box}
{js:common/publish_box}
{js:common/share_box}
{js:common/list_bootstrap}
<script>
globalData = window.globalData || {};
globalData.list = {code}echo json_encode($js_globalData);{/code};
globalData.auditValue = {$audit_value};
globalData.backValue = {$back_value};
</script>
<script>
function changeStatusLabel(status, record) {
	var id = record.id;
	if (status == {$audit_value}) {
		label = '{$audit_label}';
		color = '{$list_setting['status_color'][$audit_value]}';
	} else {
		label = '{$back_label}';
		color = '{$list_setting['status_color'][$back_value]}';
	}
	$("#statusLabelOf" + id).text(label).css('color', color).attr('_state', status);
}
function hg_change_status(obj) {
	var obj = obj[0],
		status = obj.status || obj.state,
		ids = obj.id, color, label;
    hg_close_opration_info();
	if (obj.errmsg) {
		top.jAlert(obj.errmsg, '失败提醒');
		return;
	}
	$.each(ids, function (i, id) {
		recordCollection.get(id).set('state', status);
	});
}
$(function ($) {
	var loading = '<img src="{$RESOURCE_URL}loading2.gif" style="width:25px;position:absolute;left:7px;top:-2px;" />';
    $(".common-list").on("click", '.common-switch-status span', function() {
    	if( $(this).data('noclick') ) return;
        if($(this).data('ajax')) return;
        var state = +$(this).attr('_state'),
            id = $(this).attr('_id');
        var me = $(this), url, load;
        $(this).data('ajax', true);
        load = $(loading).appendTo( $(this).parent() );

        url = './run.php?mid=' + gMid + '&a=audit&audit='+
        	(state == {$audit_value} ? 0 : 1) + '&id=' + id + '&ajax=1';
        hg_ajax_post(url, '', 0, function (data) {
        	setTimeout(function () {
                me.data('ajax', false);
                hg_change_status(data);
                load.remove();
            }, 200);
        }, false);
    });
});
</script>

{template:unit/weight_box}

<!-- 签发框 -->
<div id="vodpub" class="common-list-ajax-pub">
	<div class="common-list-pub-title">
		<p>正在发布</p>
		<div>
			<p class="overflow">标题</p><span>共1条</span>
			<div>
				<p>标题</p>
			</div>
		</div>
	</div>
	<div id="vodpub_body" class="common-list-pub-body">
		<form name="recommendform" id="recommendform" action="run.php" method="post" class="form" onsubmit="return hg_ajax_submit('recommendform');">
			{template:unit/publish}
			<input type="hidden" name="a" value="publish">
			<input type="hidden" name="ajax" value="1">
			<input type="hidden" name="mid" value="{$_INPUT['mid']}">
			<input type="hidden" name="id" value="${id}">
			<div><span class="publish-box-save">保存</span></div>
		</form>
	</div>
	<span onclick="hg_vodpub_hide();"></span>
</div>

<!-- 专题框 --> 
<div id="special_publish">
	<div class="common-list-pub-title">
		<p>正在进行专题发布</p>
		<div>
			<p class="overflow">标题</p><span>共1条</span>
			<div>
				<p>标题</p>
			</div>
		</div>
	</div>
	<form action="run.php?mid={$_INPUT['mid']}&a=push_special" method="post">
	{template:unit/special_push}
	<input type="hidden" name="id" value="" />
	<div><span class="publish-box-save">保存</span></div>
	<span class="common-list-pub-close"></span>
	</form>
</div>

<!-- 区块框 -->
<div id="block_publish">
	<div class="common-list-pub-title">
		<p>正在进行区块发布</p>
		<div>
			<p style="max-width:250px;" class="overflow">标题</p><span>共1条</span>
			<div>
				<p>标题</p>
			</div>
		</div>
	</div>
	<form>
	{template:unit/block}
	<input type="hidden" name="id" value="" />
	<input type="hidden" name="a" value="push_block" />
	<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	<div><span class="publish-box-save">保存</span></div>
	</form>
	<span class="common-list-pub-close"></span>
</div>
