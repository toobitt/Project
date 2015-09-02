<?php 
?>
{css:vod_style}
{css:template_list}
{code}
$upcell = serialize($formdata['upcell']);
if($formdata['updata'])
{
	$updata = serialize($formdata['updata']);
}
$formdata = $formdata['table'];
$celladding = $formdata['celladding'];
$celldeling = $formdata['celldeling'];
$content = $formdata['content'];
{/code}
<style>
.line{height:22px;font-size:12px;}
.cover{padding-bottom:30px;}
.cover .comp-area{margin-top:10px;}
#compare-ok{background:-webkit-linear-gradient(#6ea5e8,#5192e2);background:-moz-linear-gradient(#6ea5e8,#5192e2);background:-o-linear-gradient(#6ea5e8,#5192e2);background:linear-gradient(#6ea5e8,#5192e2);}
</style>
<h2 class="template-title">模板更新确认</h2>
<div class="comp-area">
	<div class="comp-code">
		{$formdata[0]}
		{$formdata[1]}
	</div>
	<div class="change-list">
    	{if !$celladding&&!$celldeling}
      		<p>模板中没有更改的单元</p>
    	{else}
			<div>
      		{if $celladding}
   	   			<p>以下单元是模板中新增的单元</p>
	      		<span>{$celladding}</span>
		    {else}
	        {/if}
	    	</div>
	      	<div style="clear:both;">
	   	  	{if $celldeling}
		      	<p>以下单元是模板中删除的单元</p>
	      		<span>{$celldeling}</span>
		    {else}
			{/if}
	    	</div>
	    {/if}
	</div>	
</div>
	<input type="hidden" name="upcell" value='{$upcell}' />
	<input type="hidden" name="updata" value='{$updata}' />
	<textarea style="display:none" name="new_content">{$content}</textarea>
	<br /><br />
	<div class="temp-edit-buttons">
		<input type="button" name="sub" value="保存模板" class="edit-button" id="compare-ok" />
		<input type="button" value="取消" class="edit-button cancel" id="compare-cancel" />
	</div>

