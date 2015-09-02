{js:live_interactive/program_edit}
<style>
.programeItem .current{background:green;}
</style>
<div class="live-program-area" >
		<form action="./run.php?mid={$_INPUT['mid']}" method="post" id="programForm">
			<h2 class="s_title">本期节目环节</h2>
			<!--
<div class="_title_">
				<p>主持人：</p>
				{if $presenter}
					{code}
						$i = 1;
					{/code}
					{foreach $presenter AS $k=>$v}
					<label>
						<input 
						{if $first_data['presenter_id']}
							{foreach $first_data['presenter_id'] AS $kk=>$vv}
								{if $k == $vv}
								checked="checked"
								{/if}
							{/foreach}
						{elseif $i == 1}
							checked="checked"
						{/if}
						{code}
							$i ++;
						{/code}
						type="checkbox" name="presenter_id[]" value="{$k}" />{$v}
					</label>
					{/foreach}
				{/if}
			</div>
			<div class="_title_">
				<p>微博账号：</p>
				{if $member_info}
					{code}
						$j = 1;
					{/code}
					{foreach $member_info AS $k=>$v}
					<label>
						<input 
						{if $first_data['member_id']}
							{foreach $first_data['member_id'] AS $kk=>$vv}
								{if $v['id'] == $vv}
								checked="checked"
								{/if}
							{/foreach}
						{elseif $j == 1}
							checked="checked"
						{/if}
						{code}
							$j ++;
						{/code}
						type="checkbox" name="member_id[]" value="{$v['id']}" />{$v['member_name']}
					</label>
					{/foreach}
				{/if}
			</div>
-->
			
			<div class="_title_">
				<div class="interactive_program">
					{code}
					$interactive_program = $interactive_program ? $interactive_program : array(array());
					{/code}
					{foreach $interactive_program AS $k => $v}
					<div class="programeItem">
						<p title="点击发布" {if $v['status']}class="current"{/if}></p>
						<input name="theme[]" value="{$v['theme']}" /> 
						<span class="delBtn"></span>
						<input type="hidden" name="ids[]" value="{$v['id']}" />
						<input type="hidden" name="status[]" value="{$v['status']}" />
						<!-- <input class="flag" name="flag[]" type="hidden" /> -->
					</div>
					{/foreach}
				</div>
				<span class="addProgram">+ 添加节目环节</span> 
			</div>
			<div class="_title_">
				<input type="submit" value="保存" style="height:22px;" />
				<span></span>
				<input type="hidden" name="a" value="program_edit" />
				<input type="hidden" name="dates" value="{$dates}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="channel_id" value="{$channel_id}" />
				<input type="hidden" name="in_program_id" value="{$in_program_id}" />
				<input type="hidden" name="start_end" value="{$start_end}" />
				<input type="hidden" value="{if $program_id}{$program_id}{else}{$program[$current_programe]['id']}{/if}" name="program_id" />
			</div>
		</form>
	</div>