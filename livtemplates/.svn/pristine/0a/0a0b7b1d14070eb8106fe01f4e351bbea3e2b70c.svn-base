{if $v['program']}
					{code}
						$program_id = $v['id'];
					{/code}
				<ul style="border-top: 0px;">
					{foreach $v['program'] AS $kk => $vv}
						{template:unit/interactive_program_list_list}
					{/foreach}
				</ul>
				{/if}
{code}
	$color = $_configs['program_color'][4];
	$pcolor = $color[0];
	$bcolor = $color[1];
{/code}
<li class="day_default" id="li_{$vv['id']}">
	<div class="single_item" id="update_item_{$vv['id']}" style="background:{$pcolor};border-left-color: {$bcolor};border-left-width: 2px;">
		<span class="box" style="margin-left: -5px;background: {$bcolor}"></span>
		<div class="input">
			<span class="input_left"></span>
			<span class="input_right"></span>
			<span class="input_middle">
				<input type="text" name="start_time[]" id="start_{$vv['id']}" class="single_input" value="{$vv['start']}" />
			</span>
		</div>
		<div class="input text">
			<span class="input_left"></span>
			<span class="input_right"></span>
			<span class="input_middle">
				<input type="text" name="theme[]" id="theme_{$vv['id']}" value="{$vv['theme']}" />
			</span>
		</div>
		<div class="input">
			<span class="input_left"></span>
			<span class="input_right"></span>
			<span class="input_middle">
				<input type="text" name="end_time[]" id="end_{$vv['id']}" class="single_input" value="{$vv['end']}" />
			</span>
		</div>
		<span class="focus_move">
			<!--
{code}
				$pid = $vv['id'];
				$member_source = array(
					'class' => 'down_list i',
					'show' => 'item_shows_' . $vv['id'],
					'width' => 80,/*列表宽度*/		
					'state' => 0, /*0--正常数据选择列表，1--日期选择*/
					'is_sub'=>1,
					'onclick'=>'',
				);
				$default_member = $vv['member_id'] ? $vv['member_id'] : 0;
				$member[$default_member] = '用户选择';
				foreach($member_info AS $kkk =>$vvv)
				{
					$member[$vvv['id']] = $vvv['member_name'];
				}
			{/code}
			{template:form/search_source,member_id_$pid,$default_member,$member,$member_source}
-->
			<select name="member_id[]">
			{foreach $member_info AS $kkk =>$vvv}
				<option value="{$vvv['id']}" {if $vv['member_id'] == $vvv['id']}selected="selected"{/if}>
					{$vvv['member_name']}
				</option>
			{/foreach}
			</select>
		</span>
		<input id="id_{$vv['id']}" value="{$vv['id']}" type="hidden" name="ids[]" />
		<input type="hidden" name="program_id[]" value="{$program_id}"  />
	</div>
</li>