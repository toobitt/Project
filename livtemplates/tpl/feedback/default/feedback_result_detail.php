{template:head}
{css:2013/form}
{css:2013/button}
{css:feedback_form}
{js:feedback/video_play}
{if is_array($formdata)}
	{foreach $formdata AS $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
{code}
//print_r($formdata);
{/code}
<style>
.invitationCode {
	border-radius:5px;
	/*position:absolute;*/
	display:inline-block;
	padding:10px 20px;
	background:#669bec;
	color:#FFF;
	cursor:pointer;
}
.invitationCode:hover {
	background:#4382e4;
}
</style>
<script>
jQuery(function() {
	$('.invitationCode').live('click', function() {
		var url = $(this).attr('data');
		$.getJSON(url, function(data) {
			if (typeof data[0] == 'undefined') {
				alert('发送失败');
			}else if (data[0].code == 0) {
				alert(data[0].msg);
			}
		});
	});
});
</script>
     <header class="m2o-header">
      <div class="m2o-inner">
        <div class="m2o-title m2o-flex m2o-flex-center">
            <h1>详细结果</h1>
            <div class="m2o-m m2o-flex-one m2o-m-title">
               {$title}
            </div>
            <div class="m2o-btn">
                <span class="m2o-close option-iframe-back"></span>
            </div>
        </div>
      </div>
    </header>
    <div class="m2o-inner">
     <div class="m2o-main m2o-flex">
         <section class="m2o-m m2o-flex-one feedback-attach">
         	<div  class="userinfo">
         	    <span class="user">{$recycle_name}</span><span class="source"></span> <span class="source">回收自：{if $column}{$column}{else}未知栏目{/if}</span><span class="source"></span><span class="source">昵称：{if $user_name}{$user_name}{else}匿名用户{/if}</span><span class="source"></span><span class="create_time">{$create_time}</span>
         		<div class="page">
         			{if $formdata['last_id']}
         			<a href="./run.php?mid={$_INPUT['mid']}&a=form&id={$formdata['last_id']}&fid={$formdata['feedback_id']}&infrm=1" need-back >上一页</a>
         			{/if}
         			{if $formdata['next_id']}
         			<a href="./run.php?mid={$_INPUT['mid']}&a=form&id={$formdata['next_id']}&fid={$formdata['feedback_id']}&infrm=1" need-back >下一页</a>
         			{/if}
         		</div>
         	</div>
         	<div class="info-box">
         	{foreach $answer as $v}
         	<div class="m2o-item info">
         		<label>{$v['name']}：</label>
         		{if !is_array($v['value'])}
         			<div class="info-msg">{$v['value']}</div>
         		{else}
         			
         			<div class="attach" style="display: -webkit-box;">
         			{foreach $v['value'] as $kk => $vv}
         					<div class="attachinfo">
         						{if $vv['tp'] == 1}
         							{code} $img_type = array('jpg','jepg','gif','png');
         								   $other_type = array('swf','txt','zip','docx','tif','doc','pdf','xls');
         							{/code}
         							{if in_array($vv['mtype'] , $img_type)}
		         					{code}
		         						$da = $vv;
		         						$da['filepath'] = $vv['material_path'];
		         						$da['filename'] = $vv['pic_name'];
		         						$pic = hg_fetchimgurl($da,150,150);
		         					{/code}
		         					{else if in_array($vv['mtype'] , $other_type)}
		         						{code}$pic = RESOURCE_URL .'/feedback/'.$vv['mtype'].'.jpg';{/code}
		         					{else}
		         						{code}$pic = RESOURCE_URL .'/feedback/unknown.jpg';{/code}
		         					{/if}
		         					{code} $link = $vv['host'].$vv['dir'].$vv['material_path'].$vv['pic_name'];{/code}
	         					{else}
		         					{code}
		         						$pic = $vv['index_img']['host'].$vv['index_img']['dir'].$vv['index_img']['material_path'].$vv['index_img']['pic_name'];
		         					{/code}
		         					<span class="play-button" data-url="{$vv['m3u8']}"></span>
	         					{/if}
	         					<a target="_blank" href="{$link}"><img src="{$pic}"/></a>
         					</div>
         			{/foreach}
         			</div>
         		{/if}
         	</div>
         	{/foreach}
         	{if $_INPUT['fid'] == 1}
         	<div class="invitationCode" data="./run.php?mid={$_INPUT['mid']}&a=send&id={$_INPUT['id']}&ajax=1">发送邀请码</div>
         	{/if}
         	</div>
         	<div class="video-box"></div>
         </section>
        </div>
     </div>
{template:foot}
<!-- 播放器 -->
<script type="text/x-jquery-tmpl" id="vedio-tpl">
<div style="width:480px;height:480px;">
  <object id="vodPlayer" type="application/x-shockwave-flash" data="{code}echo RESOURCE_URL{/code}swf/vodPlayer.swf?11122713" width="360" height="300">
	<param name="movie" value="{code}echo RESOURCE_URL{/code}swf/vodPlayer.swf?11122713">
	<param name="allowscriptaccess" value="always">
	<param name="allowFullScreen" value="true">
	<param name="wmode" value="transparent">
	<param name="flashvars" value="videoUrl=${video_url}&autoPlay=true&aspect=${aspect}">
  </object>
</div>
  <span class="vedio-back-close"></span>
</script>
