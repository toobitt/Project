<li class="i">
	<div class="form_ul_div">
		<span  class="title">配送状态是否可逆向修改：</span>
		<label><input type="radio" name="base[is_back]" value="0"{if $settings['base']['is_back'] == 0} checked="checked"{/if} />否</label>&nbsp&nbsp<label><input type="radio" name="base[is_back]" value="1" {if $settings['base']['is_back'] == 1} checked="checked"{/if} />是</label>
	</div>
</li>