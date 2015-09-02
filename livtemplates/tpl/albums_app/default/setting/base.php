<style type="text/css">
.button {width:20px; height:20px; border:1px solid #CCC; display:inline-block; text-align:center; margin-left:10px;}
.init_audit h2 {font-size:14px; padding:0; margin-bottom:10px;}
.init_audit label {margin-right:10px;}
.init_audit input {vertical-align:middle; margin-right:5px;}
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
<div class="init_audit">
	<h2>默认是否通过审核</h2>
	<p>含图 : <label><input type="radio" name="define[HAS_PIC]" value="1"{if $settings['define']['HAS_PIC'] == 1} checked="checked"{/if} />是</label><label><input type="radio" name="define[HAS_PIC]" value="0"{if $settings['define']['HAS_PIC'] == 0} checked="checked"{/if} />否</label></p>
	<p>不含图 : <label><input type="radio" name="define[INIT_AUDIT]" value="1"{if $settings['define']['INIT_AUDIT'] == 1} checked="checked"{/if} />是</label><label><input type="radio" name="define[INIT_AUDIT]" value="0"{if $settings['define']['INIT_AUDIT'] == 0} checked="checked"{/if} />否</label></p>
</div>