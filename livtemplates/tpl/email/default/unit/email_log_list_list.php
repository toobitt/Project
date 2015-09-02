<li class="clear"  id="r_{$v['id']}" style="margin-top:0;height:36px;padding:1px 0;" onmouseover="hg_row_interactive(this, 'on');hg_manuallySendShow({$v['id']}, 'on');" onmouseout="hg_row_interactive(this, 'out');hg_manuallySendShow({$v['id']}, 'out');" onclick="hg_row_interactive(this, 'click', 'cur');">
	<span class="left">		
		<a class="lb" name="alist[]"><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}" /></a>	
	</span>
	<span style="width:680px;height:36px;" class="right">
		<a class="fl" style="width: 190px;margin-left: 35px;">{$v['emailsend']}</a>
		<a class="fl" style="width: 160px;margin-left: -5px;">{$v['toemail']}</a>
		<a class="zt" style="margin-left: 40px;">
			<em><span id="audit_{$v['id']}">{if $v['ret_send_mail'] == 1 || $v['ret_manually_send'] == 1}<font {if $v['ret_manually_send'] == 1} style="color:#3EC100;" {else if $v['ret_send_mail'] == 1} style="color:#94C100;" {/if}>发送成功</font>{else if !$v['ret_send_mail']}<font>等待发送</font>{else}<font style="color:red;">发送失败</font>{/if}</span></em> 
		</a>
		<a class="zt" style="margin-left: -4px;">
			<em><span style="display: none;" id="manually_send_{$v['id']}" {if $v['ret_send_mail'] != 1 && $v['ret_manually_send'] != 1} onclick="hg_email_manually_send({$v['id']})" {/if}>{if $v['ret_send_mail'] != 1 && $v['ret_manually_send'] != 1}<font style="color:#20BD59;">手动发送</font>{/if}</span></em> 
		</a>
		<a class="tjr" style="">
			<em>{$v['user_name']}</em>
			<span>{$v['create_time']}</span>
		</a>
	</span>
	<span class="title overflow" style="cursor:pointer;font-size:14px;line-height:1.8;">		
		<a href="###">
			<span id="sort_name_86" class="m2o-common-title" style="color:#333;padding-right:10px;">{$v['subject']}</span>
		</a>
	</span>
</li>