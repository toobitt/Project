<?php ?>
{template:head}
{js:jqueryfn/jquery.tmpl.min}
{js:common/ajax_upload}
{css:2013/form}
{css:common/common}
{css:seekhelp_form}
{css:jquery.lightbox-0.5}
{css:hg_sort_box}
{js:2013/ajaxload_new}
{js:hg_sort_box}
{js:jquery.lightbox-0.5}
{js:seek_help/seek_form}
{js:common/common_form}
{code}
$reply_pics = $formdata['reply_pic'];
$reply_vods = $formdata['reply_video'];
$reply_vods_audio = $formdata['reply_video_audio'];
//print_r($reply_vods);
//hg_pre($_user);
//hg_pre($formdata);
$currentSort[$sort_id] = $formdata['sort_id'] ? $formdata['sort_name'] : '选择分类';
$personal_auth = $personal_auth[0];
//hg_pre($personal_auth);
if($formdata['id'])
{
	$optext="更新";
	$a="update";
}
else
{
	$optext="添加";
	$a="create";
}
{/code}
<body>
<style>
#sort-box,.hg-sort-box ul{width:191px;}
</style>
<form class="m2o-form" name="editform" enctype="multipart/form-data" action="" method="post"  id="seek_form" data-id="{$formdata['id']}">
    <header class="m2o-header">
    <div class="m2o-inner">
        <div class="m2o-title m2o-flex m2o-flex-center">
            <h1 class="m2o-l">{$optext}求助</h1>
            <div class="m2o-m m2o-flex-one">
                <!--  <input placeholder="填写求助"  class="m2o-m-title"  />-->
            </div>
            <div class="m2o-btn m2o-r">
                <input type="submit" value="保存求助" class="m2o-save" name="sub" id="sub" />
                <span class="m2o-close option-iframe-back"></span>
                <em class="prevent-do"></em>
            </div>
        </div>
      </div>  
    </header>
    <div class="m2o-inner">
    <div class="m2o-main m2o-flex">
        <aside class="m2o-l">
			<div class="m2o-item choose-area">
				<div   class="{if $personal_auth['is_complete'] || $formdata['show_other_data'] || $a == 'create'}choose{/if}"  title="选择求助对象">
					<a class="head">
						{if $formdata['account_avatar']}
						{code}
							$account_avatar = $formdata['account_avatar']['host'].$formdata['account_avatar']['dir'].'80x70/'.$formdata['account_avatar']['filepath'].$formdata['account_avatar']['filename'];
						{/code}
						<img src="{$account_avatar}">
						{else}
						<img>
						{/if}
					</a>
					<p class="info">{if $formdata['account_name']}{$formdata['account_name']}{else}选择求助对象{/if}</p>
				</div>
				<div class="source-img-box" style="height: 530px;overflow:auto">
					<span class="arrow"></span>
					<ul id="add-img" class="clear">
					
					</ul>
				</div>
				<input type="hidden" name="account_id" value="{$formdata['account_id']}">
			</div>
			
			<div class="m2o-item {if $personal_auth['is_complete'] || $formdata['show_other_data']}show-list{/if} comment-list"  data-name="pl" data-id="{$formdata['id']}">
				<span class="title">评论：</span><span>{$formdata['comment_num']}</span>
			</div>
			<div class="m2o-item {if $personal_auth['is_complete'] || $formdata['show_other_data']}show-list{/if} user-list" data-name="lm" data-id="{$formdata['id']}">
				<span class="title">联名：</span><span>{$formdata['joint_num']}</span>
			</div>
			<div class="m2o-item">
				<span class="title">关注：</span><span>{$formdata['attention_num']}</span>
			</div>
			<div class="m2o-item" id="sort-box">
                <label style="color:#9f9f9f;{if !$formdata['sort_id']}display:none;{/if}">分类： </label><p style="display:inline-block;"  class=" {if $personal_auth['is_complete'] || $formdata['show_other_data'] || $a == 'create'} sort-label {/if}"  _multi="seekhelp_node">{$currentSort[$sort_id]}<img class="common-head-drop" src="{$RESOURCE_URL}tuji/drop.png" style="position: relative;left:10px;bottom:2px;" /></p>
				<div class="sort-box-outer"><div class="sort-box-inner"></div></div>
                <input name="sort_id" type="hidden" value="{$formdata['sort_id']}" id="sort_id" />
			</div>
        </aside>
        <section class="m2o-m m2o-flex-one">
        	<div class="help-desc">
				<div class="m2o-item describe">
					<div class="form-item">
						<p class="item-name">求助内容</p>
						<textarea placeholder="求助内容" name="title" id="titles" {if $a == 'update' && !$formdata['show_other_data'] && !$personal_auth['is_complete']} readonly="readonly" {/if}>{$formdata['title']}</textarea>
						<div><font color="red">{if $formdata['banword'] && $formdata['banword']['title']} 屏蔽字 {$formdata['banword']['title']} {/if}</font></div>
					</div>
					<div class="form-item">
						<p class="item-name">描述</p>
						<textarea rows="5" cols="128" placeholder="描述" name="content" {if $a == 'update' && !$formdata['show_other_data'] && !$personal_auth['is_complete']} readonly="readonly" {/if}>{$formdata['content']}</textarea>
						<div><font color="red">{if $formdata['banword'] && $formdata['banword']['content']} 屏蔽字 {$formdata['banword']['content']} {/if}</font></div>
					</div>
					<ul class="media-list clear">
						{if $formdata['pic']}
						{foreach $formdata['pic'] as $pinfor}
						{code}
							$pic = '';
							$org_pic = $pinfor['host'].$pinfor['dir'].$pinfor['filepath'].$pinfor['filename'];
							$pic = $pinfor['host'].$pinfor['dir'].'100x80/'.$pinfor['filepath'].$pinfor['filename'];
						{/code}
						<li class="pic">
							<div class="clear " id="seekhelp-pic">
								
									<a href="{$org_pic}">
										<img src="{$pic}"/>
									</a>	
							</div>
						</li>
						{/foreach}
						{/if}
						<li class="video">
						{code}
						//print_r($formdata['video']);
						{/code}
							<div class="clear" id="seekhelp-video">
								{if $formdata['video']}
								{foreach $formdata['video'] as $video}
								{if $video['img']}
								{code}
									$video_img = '';
									$video_img = $video['img']['host'].$video['img']['dir'].'100x80/'.$video['img']['filepath'].$video['img']['filename'];
									$video_url = $video['url'];
								{/code}
								<div class="video-item" data-url="{$video_url}">
									<img alt="" src="{$video_img}">
									<span class="play"></span>
								</div>
								{/if}
								{/foreach}
								{/if}
							</div>
						</li>
						<li >
							<div class="clear" id="seekhelp-audio">
								{if $formdata['vodeo_audio']}
								{foreach $formdata['vodeo_audio'] as $audio}
								{code}
									$audio_img = '';
									if ($audio['img']['host'])
									{
										$audio_img = $audio['img']['host'].$audio['img']['dir'].'80x70/'.$audio['img']['filepath'].$audio['img']['filename'];
									}
									$audio_url = $audio['url'];
								{/code} 
								<div class="video-item audio" data-url="{$audio_url}" style="width:100px;height:80px;">
									<img alt="" src="{$audio_img}">
								</div>
								{/foreach}
								{/if}
							</div>
						</li>
					</ul>
				</div>
				{if $a == 'update'}
				<div class="m2o-item" {if !$formdata['show_other_data'] && !$personal_auth['is_complete']} style="display:none" {/if} >
					<h4>推荐答案<span class="rec-del">删除</span></h4>
					<textarea readonly="readonly" name="recommend_answer" class="recommend-answer show-list" data-name="pl" data-id="{$formdata['id']}"  placeholder="推荐答案">{$formdata['recommend_answer']}</textarea>
					<input type="hidden" name="comment_id" value="{$formdata['comment_id']}"/>
				</div>
				<div class="m2o-item best-answer">
					<h4>金牌回复</h4>
					<textarea name="gold_reply">{$formdata['reply']}</textarea>
					<div><font color="red">{if $formdata['banword'] && $formdata['banword']['reply']} 屏蔽字 {$formdata['banword']['reply']} {/if}</font></div>
					<input type="hidden" name="reply_id" value="{$formdata['reply_id']}"/>
					<div class="pic-list">
					
					</div>
					<div class="vod-list">
					
					</div>
					<div class="vod-audio-list">
					
					</div>
					<div class="loading">
						<img alt="" src="{$RESOURCE_URL}/loading2.gif">
						<p>正在上传...</p>
					</div>
					<div class="add-buttons clear">
						<a class="pic-upload btn-num4">添加图片</a>
						<a class="video-upload btn-num4">添加视频</a>
						<input type="file" style="display:none;" id="pic-file">
						<input type="file" style="display:none;" id="video-file" >
					</div>
				</div>
				{else}
				<div class="material-lists">
					<div class="material-list m2o-flex" _type="pic"></div>
					<div class="material-list m2o-flex"_type="vod"></div>
					<div class="handle-btns">
						<a class="trigger-btn btn-num4" _type="pic">添加图片</a>
						<a class="trigger-btn btn-num4" _type="vod">添加视频</a>
					</div>
				</div>
				{/if}
			</div>
			<div class="seekhelp-list" _name="pl">
				<div class="list-title">
					<p></p>
					<span class="close">x</span>
				</div>
				<div class="list">
					<!-- ------------------------ -->
				</div>
			</div>
        </section>
        <aside class="m2o-l">
			<div class="m2o-item">
				<a class="head">
					{if $formdata['member_avatar']}
					{code}
						$member_avatar = $formdata['member_avatar']['host'].$formdata['member_avatar']['dir'].'80x70/'.$formdata['member_avatar']['filepath'].$formdata['member_avatar']['filename'];
					{/code}
					<img src= "{$member_avatar}">
					{else}
					<img >
					{/if}
				</a>
			</div>
			<div class="m2o-item">
				<span class="title">用户名：</span><span class="user-name">{$formdata['member_name']}</span>
			</div>
			<div class="m2o-item">
				<span class="title">求助时间：</span><span class="time">{$formdata['format_create_time']}</span>
			</div>
			<div class="m2o-item">
				<span class="title">联系电话：</span><span class="tel">{$formdata['tel']}</span>
			</div>
        </aside>
    </div>
    <div class="media-box"></div>
    </div>
    <input name="a" value="{$a}" type="hidden" />
</form>
</body>
<script type="text/x-jquery-tmpl" id="vedio-tpl">
<div style="width:240px;height:240px;">
  <object id="vodPlayer" type="application/x-shockwave-flash" data="{code}echo RESOURCE_URL{/code}swf/vodPlayer.swf?11122713" width="240" height="240">
	<param name="movie" value="{code}echo RESOURCE_URL{/code}swf/vodPlayer.swf?11122713">
	<param name="allowscriptaccess" value="always">
	<param name="allowFullScreen" value="true">
	<param name="wmode" value="transparent">
	<param name="flashvars" value="videoUrl=${video_url}&autoPlay=true&aspect=${aspect}">
  </object>
</div>
  <span class="vedio-back-close"></span>
</script>
<script type="text/x-jquery-tmpl" id="addform-pic-tpl">
<div class="material-item">
	<img src=""/>
	<span class="del"></span>
	<input type="file" name="photos[]" class="pic-upload pic-file" accept="image/*">
</div>
</script>
<script type="text/x-jquery-tmpl" id="addform-vod-tpl">
<div class="material-item" title="">
	<p></p>
	<span class="del"></span>
	<input type="file" name="video[]" class="vod-file">
</div>
</script>
<script type="text/x-jquery-tmpl" id="add-pic-tpl">
	<div class="pic-item">
        <img src="${pic_src}" />
		<span class="pic-del"></span>
		<input type="hidden"  name="reply_pic[]" value="${id}" />
	</div>
</script>
<script type="text/x-jquery-tmpl" id="add-vod-tpl">
	<div class="vod-item video-item" data-url=${vod_url}>
        <img src="${vod_src}" />
		<span class="play"></span>
		<span class="vod-del"></span>
		<input type="hidden"  name="reply_vod[]" value="${id}" />
	</div>
</script>
<script type="text/x-jquery-tmpl" id="add-vod-audio-tpl">
	<div class="vod-item video-item pic-item audio" data-url=${audio_url} style="width:100px;height:80px;">
        <img src="${audio_src}"/>
		<span class="pic-del"></span>
		<input type="hidden"  name="reply_vod[]" value="${id}" />
	</div>
</script>
<script>
$(function($){
	var data = $.globalpicData = {code} echo $reply_pics ? json_encode($reply_pics) : '{}'  {/code};
	var video_data = $.globalvodData = {code} echo $reply_vods ? json_encode($reply_vods) : '{}'{/code};
	var video_audio_data = $.globalvodaudioData = {code} echo $reply_vods_audio ? json_encode($reply_vods_audio) : '{}'{/code};
			$('body').on({
			seekforminit : function(event,_this){
				var op = _this.options,
					pic_data = [],
					vod_data = [],
					vod_audio_data = [];
			$.each(data,function(key,value){
				var imgInfo = {},
					img_id = value['id'],
					img = value['host'] + value['dir'] + '100x100/' + value['filepath'] + value['filename'];
				imgInfo.pic_src = img;
				imgInfo.id = img_id;
				pic_data.push( imgInfo );
			});
			$.each(video_data,function(key,value){
				var vodInfo = {},
					vod_id = value['id'],
					vod_src = value['img']['host'] + value['img']['dir'] + '100x100/' + value['img']['filepath'] + value['img']['filename'];
				vodInfo.vod_url = value['url'];
				vodInfo.id = vod_id;
				vodInfo.vod_src = vod_src;
				vod_data.push( vodInfo );
			});
			
			$.each(video_audio_data,function(key,value){
				var vodAudioInfo = {},
					audio_id = value['id'],
					audio_src = '';
				vodAudioInfo.audio_url = value['url'];
				vodAudioInfo.id = audio_id;
				vodAudioInfo.audio_src = audio_src;
				vod_audio_data.push( vodAudioInfo );
			});
			$( op['add-pic-tpl'] ).tmpl( pic_data ).appendTo( op['pic_list'] );
			$( op['add-vod-tpl'] ).tmpl( vod_data ).appendTo( op['vod-list'] );
			$( op['add-vod-audio-tpl'] ).tmpl( vod_audio_data ).appendTo( op['vod-audio-list'] );
			}
		},'#seek_form');
		$('#seek_form').seekform();
});
</script>