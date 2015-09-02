{if $formdata['id']}
{code}
 $v = $formdata;
{/code}
{template:unit/transcode_centerlist}
{else}{code}echo $formdata['ErrorCode'];{/code}{/if} 