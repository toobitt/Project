{code}
if ( !isset($js_list_data) && isset($hg_value) ) {
	$js_list_data = $hg_value;
} else {
	$js_list_data = array();
} 
{/code}
<script>
globalData = window.globalData || {};
globalData.list = {code}echo json_encode($js_list_data);{/code};
</script>
{css:common/common_list}
{js:underscore}
{js:Backbone}
{js:member/record}
{js:member/record_view}