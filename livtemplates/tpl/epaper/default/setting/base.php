<style type="text/css">
.button {width:20px; height:20px; border:1px solid #CCC; display:inline-block; text-align:center; margin-left:10px;}
</style>
<script type="text/javascript">
jQuery(function() {
	$('#addType').click(function() {
		var con = '<p><input type="text" name="base[type_name][]" placeholder="类型名" /> : <input type="text" name="base[type_id][]" placeholder="类型标识" /> : <input type="text" name="base[type_value][]" placeholder="类型值" /><a class="button removeType" title="删除">-</a></p>';
		$('#type_list').append(con);
	});
	$('.removeType').live('click', function() {
		$(this).parent().remove();
	});
});
</script>
<div id="type_list">
</div>