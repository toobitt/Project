{code}
	//print_r($formdata);
{/code}
<style>
/*.weather-usr-box{overflow:hidden;width:530px;}*/
/*.weather-usr-box-inner{width:10000px;overflow:auto;}*/
.weather-usr, .add{float:left;border:1px solid pink;margin:10px;}
.weather-usr p{margin:10px;}
.weather-usr select{margin-right:10px;}
.weather-usr input[type=radio]{height:auto;margin-left:10px;}
</style>


	<div class="weather-usr">
		<p><label>字段名称</label></p>
		<p><label>字段标识</label></p>
		{foreach $formdata['source'] as $key=>$val}
		<p><label>{$val}：</label><p>
		{/foreach}
		<p><label>用户自定义：</label></p>
		<p><label>生效日期：</label></p>
	</div>
	{foreach $formdata['field'] as $key=>$val}
	<div class="weather-usr">
		<p><input type="text" name="user_desc_{$key}"  value="{$val['user_desc']}"/></p>
		<p><input type="text" name="user_field_{$key}" value="{$key}"/></p>
		{foreach $formdata['dict'] as $k=>$v}
		<p><select>
			{foreach $v as $field=>$desc}
				<option val="{$field}"  
				{code}
					if($k==$val['source_id'] && $field==$val['source_field'])
					{
						echo 'selected ="selected"';
					}
				{/code}
				>{$desc}</option>
			{/foreach}
		</select><span>333</span><input type="radio" name="source_{$key}[]" value={$k} /><p>
		{/foreach}
		<p><input type="text"  name="user_data_{$key}[]" /></p>
	
	</div>
	{/foreach}
	<div id="add" class="add">+</div>


