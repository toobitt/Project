<object id="vodPlayer" type="application/x-shockwave-flash" data="{$formdata['addSwf']}vodPlayer.swf?{$formdata['time']}" width="640" height="510">
	<param name="movie" value="{$formdata['addSwf']}vodPlayer.swf?{$formdata['time']}">
	<param name="allowscriptaccess" value="always">
	<param name="allowFullScreen" value="true">
	<param name="flashvars" value="startTime={$formdata['start']}&duration={$formdata['duration']}&videoUrl={$formdata['video_url']}&videoId={$formdata['vodid']}&snap=false">
</object>