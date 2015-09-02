<input type="hidden" name="status_id" value="{$status_id}" id="mainStatus" />
<input type="hidden" name="status_user_id" value="{$status_id}" id="mainUser_{$status_id}_{$user_info['id']}" />

<div class="comment-content clear" >
<span class="triangle">&nbsp;</span>
<div class="top"></div>
<div class="middle clear">
<dl id="status_item_{$status_id}">
<dt><a><span onClick="closeComm({$status_id});"></span></a></dt>
<dd class="text" id="text_{$status_id}">
	<input type="hidden" name="num" id="num_status_{$status_id}" value="{code} echo intval($comments_arr[0]);{/code}" />
	<a  onclick="global_face('comm_text_{$status_id}','com_face_{$status_id}');" href="javascript:void(0);" class="choiceface">
		<img alt="" src="<?php echo RESOURCE_DIR;?>img/smiles/17.gif">
	</a>
	<div id="com_face_{$status_id}" class="face_content" style="position: absolute; visibility: visible; top: 55px; left: 11px;display:none;"></div>
	<input class="txt" name="comm_text_{$status_id}" type="text" id="comm_text_{$status_id}" onkeyup="check_opt({$status_id});" />
	<input type="hidden" name="push_flag" value="0" id="push_flag" />
	<input type="button" id = "commBtn{$status_id}" name="comm_sub" value="{$_lang['let_me_comm']}" onClick="pushAction({$status_id})"/>
	<div style="font-size: 12px; padding-right: 60px; text-align: left;" onclick="changevalue({$status_id})" >
	{code}
		$checked = $_settings['default_sync']['comm_list']?' checked':'';
	{/code}
	<input type="checkbox" style="color:#444;height:auto;width:auto;font-size:12px;" onclick="changevalue({$status_id})" value="0" id="transmit_to_mt{$status_id}" name="transmit_to_mt" {$checked}>同时转发到我的点滴</div>
</dd>

{if intval($comments_arr[0]) != 0}
{code}
$itemnum = 1;
$num = intval($comments_arr[0]); 
unset($comments_arr[0]);
{/code}

{foreach $comments_arr  as $key => $value}
<dd id="co_{$value['id']}_{$value['user']['id']}" onmouseover="report_show({$value['id']},{$value['user']['id']});" onmouseout="report_hide({$value['id']},{$value['user']['id']});">
	<a name="{$value['id']}" id="{$value['id']}"></a>
	<div style="display:none;" id="cons_{$value['id']}_{$value['user']['id']}"><?php echo hg_verify($value['content']);?></div>
	<div style="display:none;" id="ava_{$value['id']}_{$value['user']['id']}">{$value['user']['small_avatar']}</div>
	<div style="display:none;" id="user_{$value['id']}_{$value['user']['id']}">{$value['user']['username']}</div>
	<div style="display:none;" id="url_{$value['id']}_{$value['user']['id']}"><?php echo SNS_MBLOG;?>show.php?id={$value['status']['id']}#{$value['id']}</div>
	<div style="display:none;" id="type_{$value['id']}_{$value['user']['id']}">8</div>
	
	<span style="float:right;">
		{if $_user['id']}
		<a onclick="report_play({$value['id']},{$value['user']['id']});" href="javascript:void(0);" style="display:none;" id="re_{$value['id']}_{$value['user']['id']}">{$_lang['report']}</a>
		&nbsp;
		{/if}
		<a href="javascript:void(0);" onClick = "replyC({$value['id']},{$value['member_id']},{$value['status']['id']})">{$_lang['reply']}</a>&nbsp;
		{if $_user['id'] == $value['member_id'] || $_user['id'] == $value['status']['member_id']}
		<a href="javascript:void(0)" onClick="deleteC({$value['id']},{$value['status']['id']})" >{$_lang['del_comment']}</a>&nbsp;
		{/if}
	</span>
	<div id="tips_{$value['id']}"><input type="hidden" name="hhid" value="" /></div>
		
	<a href="<?php echo hg_build_link('user.php' , array('user_id' => $value['user']['id'])); ?>"><img src="{$value['user']['small_avatar']}" align="middle"/></a>

	<a href="<?php echo hg_build_link('user.php' , array('user_id' => $value['user']['id'])); ?>">
	{$value['user']['username']}
	</a>：<?php echo hg_verify($value['content']);?>
	<span>
		<?php echo hg_get_date($value['create_at']);?>
		<input type="hidden" id="user_{$value['member_id']}_{$value['id']}" name="user_{$value['member_id']}" value="{$value['user']['username']}" />
	</span> 
</dd>

		{if $_input['ajax']}
		{code}
			$itemnum++;
		{/code}
			{if $itemnum>10}
			{code}
				break;
			{/code}
			{/if}
		    {/code}
		{/if}
{/foreach}
{/if}
{if $num > 10}
<dd class="all" id="tips_{$status_id}"><a href="<?php echo hg_build_link('show.php' , array('id' => $status_id)); ?>">查看全部<strong id="numTips_{$status_id}">
{if !$num}
{code}
  echo 0;
{/code}
{else}
{$num}
{/if}

</strong>次评论</a>
{/if}
<input type="hidden" name="blogger" value="{$_user['id']}" />
</dd>

</dl>
</div>
<div class="bottom"></div> 
</div>  
