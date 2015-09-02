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
</script><p style="margin-bottom:10px;">类型设置：参数值为2的幂次方<a class="button" id="addType" title="添加">+</a></p>
<div id="type_list">{if $settings['base']['type']}	{foreach $settings['base']['type'] as $k => $v}	<p><input type="text" name="base[type_name][]" value="{$v['name']}" /> : <input type="text" name="base[type_id][]" value="{$k}" /> : <input type="text" name="base[type_value][]" value="{$v['value']}" /><a class="button removeType" title="删除">-</a></p>	{/foreach}{/if}
</div>