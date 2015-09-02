{code}
	$action = $a;
{/code}
{if is_array($formdata)}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
{code}
	if($action == 'create')
	{
		$start_time = $formdata['date'];
	}
{/code}
	<div id="program_item" class="hid">
	<form id="program_form" name="program_forms" action="run.php?mid={$_INPUT['mid']}" method="POST" onsubmit="return hg_ajax_submit('program_form', 'hg_valid_program_data');">
		<div id="left_arrow"></div>
		<div id="forms" style="width:230px;">
			<span>主题</span>
			<input id="theme" type="text" name="theme" value="{$theme}"/> :&nbsp;&nbsp;<input id="subtopic" type="text" name="subtopic" value="{$subtopic}"/>
			<p style="height:2px;"></p>
			<span>时间</span>
			<input type="hidden" id="stime" name="stime" value="{$dates}"/>
			{if $action == 'create'}
				<input id="start_time" type="text" name="start_time" value=""/> - &nbsp;<input onblur="toff(), isTime({$_INPUT['end_time']});" id="end_time" type="text" name="end_time" value=""/>
			{else}
				<input onblur="is_start_time({$_INPUT['start_time']}), hg_start_time();" id="start_time" type="text" name="start_time" value="{code} echo date('H:i:s', $start_time);{/code}"/> - &nbsp;<input onblur="toff(),hg_end_time(), isTime({$_INPUT['end_time']});" id="end_time" type="text" name="end_time" value="{code} echo date('H:i:s', $start_time+$toff);{/code}"/>
				<input id="next_start_time"  name="next_start_time"  type="hidden" value="{code} echo date('H:i:s', $next_start_time);{/code}" />
				<input id="next_toff"  name="next_toff"  type="hidden" value="{code} echo date('H:i:s', $next_toff);{/code}" />
			{/if}
			<p style="height:2px;"></p>
			<div style="height:22px;line-height: 22px;">
				<span class="f_l">时长</span><label id="toff" class="f_l" name="toff">{code} echo date("H小时i分",($toff-28800));{/code}</label>
				<input type="hidden" id="hid_toff" value="{$toff}"/>
			<span id="type"  class="f_l" style="position:relative;top:1px;left:10px;">类型</span>
			<select name="type_id"  class="f_l" style="position:relative;top:1px;left:12px;width:90px;">
			{foreach $program_type as $k => $v}
				<option {if $v['id'] == $type_id} selected{/if} value="{$v['id']}">{$v['name']}</option>	
			{/foreach}
			</select>
			</div>
			
			<span style="display:block;margin-top:15px;">描述</span><textarea style="width:188px;margin-left:30px;height:40px;margin-top:-20px;" name="describes" cols=21 rows=2>{$describes}</textarea>
			<p style="height:2px;"></p>
			<div class="dotted">
				<div class="div_h5">
				{if $formdata['program_id']}
				<input class="m_l" type="checkbox" name="auto_record" value='1' checked  class="n-h"/>自动录播
				{else}
				<input class="m_l" type="checkbox" name="auto_record" value='1'  class="n-h" />自动录播
				{/if}
				<select name="taped" id="taped">
					{foreach $program_item as $k => $v}
						<option{if $k == $item} selected{/if} value="{$k}">{$v}</option>	
					{/foreach}
				</select>
				</div>
				<div class="div_h5">
				{if $formdata['program_id_screen']}
				<input class="m_l" type="checkbox" value='1' name="auto_screen" checked   class="n-h"/>屏蔽
				{else}
				<input class="m_l" type="checkbox" value='1' name="auto_screen"  class="n-h" />屏蔽
				{/if}
				<select name="backup_id" id="screen">
					{foreach $program_screen as $k => $v}
						<option{if $k == $backup_id} selected{/if} value="{$k}">{$v}</option>	
					{/foreach}
				</select>
				</div>
			</div>
			<input id="sub" type="submit" name="sub" value="{$optext}" class="button_2" style="float: right;"/>
			<input type="hidden" name="a" value="{$action}" />
			<input type="hidden" name="channel_id" value="{$channel_id}" />
			<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
			<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
			<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
			<input type="hidden" name="i" id="i" value="{$i}" />
		</div>
	</form>
	</div>
