{if $hg_data['mtype'] == 'image'}
		<img src="{$hg_data['murl']}"  align="center"/>
{else if $hg_data['mtype'] == 'video'}
		{if $hg_attr['video']=='video'}
		<object id="vodPlayer" type="application/x-shockwave-flash" data="{code}echo RESOURCE_URL{/code}swf/vodPlayer.swf?11122713" style="margin-left:-20px;" width="380" height="330">
		<param name="movie" value="{code}echo RESOURCE_URL{/code}swf/vodPlayer.swf?11122713">
		<param name="allowscriptaccess" value="always">
		<param name="allowFullScreen" value="true">
		<param name="wmode" value="transparent">
		<param name="flashvars" value="videoUrl={$hg_data['vurl']}&snapUrl=&autoPlay=false">
		</object>
		{else}
		<img src="{$hg_data['murl']}"  align="center"/>	
		{/if}
{else if $hg_data['mtype'] == 'flash'}
		{if $hg_attr['list']}
			<object type="application/x-shockwave-flash" data="{$hg_data['murl']}" width="40px" height="30px;"><param name="movie" value="{$formdata['murl']}"><param value="transparent" name="wmode" align="center"></object>
		{else}
			<object type="application/x-shockwave-flash" data="{$hg_data['murl']}"><param name="movie" value="{$formdata['murl']}"><param value="transparent" name="wmode" align="center"></object>
		{/if}
{else if $hg_data['mtype'] == 'javascript'}
		<div style="overflow: hidden;">
		{$hg_data['murl']}
		</div>
{else if $hg_data['mtype'] == 'text'}
		{$hg_data['murl']}
{/if}