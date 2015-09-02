<?php 
/* $Id: message_send_form.php 17834 2013-03-22 03:25:33Z jeffrey $ */
?>
{template:head}
{css:ad_style}

{if $a}
	{code}
		$action = $a;
	{/code}
{/if}

{if is_array($formdata)}
	{foreach $formdata AS $key => $value}
		{code}
			$$key = $value;
		{/code}
	{/foreach}
	{code}
	$update_time = date("Y-m-d H:i:s",$update_time);
	{/code}
{/if}

<script type="text/javascript">
function checknum()
{
	var phones = $.trim($("#received_phone").attr("value"));
	var contents = $.trim($("#content").attr("value"));
	var order_id = $.trim($("#order_id").attr("value"));

	if (phones=="" || contents=="" || order_id==""){
	  alert("请完整输入信息！");
	  return false
	}
	if(isNaN(parseInt(order_id))){
		alert("排序为数字值！");
		return false
	}
}
</script>
<div class="ad_middle">
	{if !empty($formdata['id'])}
	<form name="editform" id="editform"  onsubmit="return checknum()" action="./run.php?mid={$_INPUT['mid']}" method="post"  class="ad_form h_l">
		<h2>{$optext}发送信息</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">接收号码：</span>
					<input type="text" id="received_phone" name="received_phone" value="{$received_phone}" style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">状态时间：</span>
					<input type="text" name="send_time" readonly value="{$update_time}" style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">信息状态：</span>
					<label>
						<input name="backstatus" type="radio" value=1 class="n-h"
							{if $backstatus==1 && $id}
								checked="checked"
							{/if}
						/>{$back_status[1]}&nbsp;
					</label>
					<label>
						<input name="backstatus" type="radio" value=2 class="n-h"
							{if $backstatus==2 && $id}
								checked="checked"
							{/if}
						/>{$back_status[2]}&nbsp;
					</label>
					<label>
						<input name="backstatus" type="radio" value=3 class="n-h"
							{if $backstatus==3 && $id}
								checked="checked"
							{/if}
						/>{$back_status[3]}&nbsp;
					</label>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">审核状态：</span>
					<label>
						<input name="status" type="radio" value=0 class="n-h"
							{if $status==0 && $id}
								checked="checked"
							{/if}
						/>{$audio_status[0]}&nbsp;
					</label>
					<label>
						<input name="status" type="radio" value=1 class="n-h"
							{if $status==1 && $id}
								checked="checked"
							{/if}
						/>{$audio_status[1]}&nbsp;
					</label>
					<label>
						<input name="status" type="radio" value=2 class="n-h"
							{if $status==2 && $id}
								checked="checked"
							{/if}
						/>{$audio_status[2]}&nbsp;
					</label>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">IP：</span>
					<input type="text" name="ip" value="{$ip}" readonly style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">信息内容：</span>
					<textarea id="content" name="content">{$content}</textarea>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">排序：</span>
					<input type="text" id="order_id" name="order_id" value="{$order_id}" style="width:40px"/>
					<font class="important"></font>
				</div>
			</li>
		</ul>
		</br>
		<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14"/>
		<input type="hidden" name="id" value="{$id}" />
		<input type="hidden" name="a" value="{$action}" id="action" />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	</form>
	{else}
	<form name="editform" onsubmit="return checknum()" id="editform" action="./run.php?mid={$_INPUT['mid']}" method="post"  class="ad_form h_l">
		<h2>{$optext}发送信息</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">接收号码：</span>
					<input type="text" id="received_phone" name="received_phone" value="" style="width:380px"/>
					<font class="important">多个号码请用,隔开</font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">信息内容：</span>
					<textarea id="content" name="content"></textarea>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">排序：</span>
					<input type="text" id="order_id" name="order_id" value="9999" style="width:80px"/>
					<font class="important"></font>
				</div>
			</li>
		</ul>
		</br>
		<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14"/>
		<input type="hidden" name="id" value="{$id}" />
		<input type="hidden" name="a" value="{$action}" id="action" />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	</form>
	{/if}
</div>
<div class="right_version">
	<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
</div>
{template:foot}