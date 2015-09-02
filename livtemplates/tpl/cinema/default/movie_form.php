{template:head}
{code}
//print_r( $formdata );
{/code}
{if is_array($formdata) && $a == 'update'}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
{code}
if($id)
{
	$optext="更新";
	$ac="update";
}
else
{
	$optext="添加";
	$ac="create";
}
{/code}
{css:common/common}
{css:2013/form}
{css:movie_form}
{css:2013/button}
{css:fancybox/jquery.fancybox}
{js:ajax_upload}
{js:fancybox/jquery.fancybox}
{js:cinema/movie_form}
<form class="m2o-form" action="run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data" method="post" data-id="{$id}" id="movie-form">
    <header class="m2o-header">
      <div class="m2o-inner">
        <div class="m2o-title m2o-flex m2o-flex-center">
            <h1 class="m2o-l">{$optext}影片</h1>
            <div class="m2o-m m2o-flex-one">
                <input class="m2o-m-title {if $title}input-hide{/if}" _value="{if $title}{$title}{else}添加电影名称{/if}" name="title" id="title" required placeholder="影片名称" value="{$title}"/>
            </div>
	        <div class="m2o-btn m2o-r">
                <input type="submit" value="{$optext}" class="m2o-save" name="sub" id="sub" />
                <span class="m2o-close option-iframe-back"></span>
                <input type="hidden" name="a" value="{$ac}" />
				<input type="hidden" name="{$primary_key}" value="{$id}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
            </div>
        </div>
      </div>
    </header>
    <div class="m2o-inner">
     <div class="m2o-main m2o-flex">
     	{template:unit/publish_for_form, 1, $formdata['column_id']}
        <aside class="m2o-l">
        	<div class="m2o-item">
        		<div class="indexpic">
        			<img style="width:176px; height:176px;" src="{if $formdata['index_pic']}{$formdata['index_pic']}{/if}" />
                    <span class="{if $title}indexpic-suoyin-current{else}indexpic-suoyin{/if}"></span>
                 </div>
                 <input type="file" name="img" id="photo-file" style="display:none; "/>
        	</div>
        	<div class="m2o-item">
        		<span style="color:#9f9f9f;">添加剧照</span>
        		<ul class="file-list">
        			{foreach $formdata['still'] as $k => $v}
        			<li class="image-list" _id="{$v['still_id']}">
        				{code}
        					$ori_img = $v['img_info']['host'].$v['img_info']['dir'].$v['img_info']['filepath'].$v['img_info']['filename'];
        					$img = $v['img_info']['host'].$v['img_info']['dir'].'55x55/'.$v['img_info']['filepath'].$v['img_info']['filename'];
        				{/code}
        				<a class="fancybox" href="{$ori_img}" data-fancybox-group="gallery" title="{$v['img_info'][filename]}">
        					<img src="{$img}" />
        				</a>
        			</li>
        			{/foreach}
        			<li class="add-file add-still"></li>
        		</ul>
        		<input type="file" name="still" style="display:none;">
        	</div>
        	<div class="m2o-item">
        		<span style="color:#9f9f9f;">添加预告片，花絮</span>
        		<ul class="file-list video-list">
        			{foreach $formdata['prevue'] as $k => $v}
        				{code}
        					$video_img_url = $v['prevue_info']['img']['host'].$v['prevue_info']['img']['dir'].'55x55/'.$v['prevue_info']['img']['filepath'].$v['prevue_info']['img']['filename'] ;
        					$video_play_url = $v['prevue_info']['protocol'].$v['prevue_info']['host'].'/'.$v['prevue_info']['dir'].$v['prevue_info']['file_name'].'.'.$v['prevue_info']['type'] ;
        				{/code}
        			<li class="video-list-item" _id="{$v['prevue_id']}">
        				<img src="{$video_img_url}" />
        				<span class="play-video" _url="{$video_play_url}"></span>
        			</li>
        			{/foreach}
        			<li class="add-file add-video"></li>
        		</ul>
        		<input type="file" name="prevue" style="display:none;">
        	</div>
        	<div class="m2o-item">
        		<span style="color:#9f9f9f;">预告片地址</span>
        		<ul class="file-list video-list">
        			<input type="text" style="width:184px;" name="prevue_url" value="{$prevue_url}">
        		</ul>
        		<input type="file" name="prevue" style="display:none;">
        	</div>
        </aside>
         <section class="m2o-m m2o-flex-one">
	            <div class="m2o-item movie-info">
	            	<a class="movie-title active" _id="t_1">基本信息</a>
	            </div>
	        	<div class="m2o-item">
	        		<label class="title">导演: </label>
	        		<input type="text" name="director"  class="w350 input-control" value="{$director}"/>
	        	</div>
	        	<div class="m2o-item">
	        		<label class="title">主演: </label>
	        		<!--  <input type="text" name="main_performer"  class="input-control" value="{$main_performer}" />-->
	        		<textarea name="main_performer" class="w350" placeholder="影片简介"> {$main_performer}</textarea>
	        	</div>
	            <div class="m2o-item movie-select">
	        		<label class="title">地区: </label>
	        		<ul class="area-list">
	        		{foreach $_configs['area'] as $k => $v}
	        			<li>
	        				<input type="radio" name="area" {if $area == $k} checked {/if} value="{$k}">
	        				<span>{$v}</span>
	        			</li>
	        		{/foreach}
	        		</ul>
	        	</div>
	        		<div class="m2o-item">
	        		<label class="title">语言: </label>
	        		<input type="text" name="language" class="input-control" value="{$language}" />
	        	</div>
	        	<div class="m2o-item movie-select">
	        		<label class="title">类型: </label>
	        		<ul class="type-list">
	        		{foreach $_configs['movie_type'] as $k => $v}
	        			<li>
	        				<input type="checkbox" {if in_array($k,explode(',',$type)) } checked {/if} name="type[]" value="{$k}">
	        				<span>{$v}</span>
	        			</li>
	        		{/foreach}
	        		</ul>
	        	</div>
	        		<div class="m2o-item movie-select">
	        		<label class="title">片长: </label>
	        		<input type="number" min='0' name="duration"  class="w50 input-control" value="{$duration}"/> 分钟
	        	</div>
	            <div class="m2o-item movie-select">
	        		<label class="title">上映时间: </label>
	        		<input type="text" name="release_time"  class="input-control date-picker" required value="{$release_time}"/>
	        	</div>
	        	<div class="m2o-item">
	        		<label class="title">影片简介: </label>
	        		<textarea name="brief" class="w350" placeholder="影片简介"> {$brief}</textarea>
	        	</div>
            </div>
            <input type="hidden" name="still_id"  value="{foreach $formdata['still'] as $k => $v}{$v['still_id']},{/foreach}" />
            <input type="hidden" name="prevue_id"  value="{foreach $formdata['prevue'] as $k => $v}{$v['prevue_id']},{/foreach}" />
            <div class="cover"></div> 
         </section>
      </div>
     </div>
     <div class="media_box"></div>
</form>
<script type="text/x-jquery-tmpl" id="image-add-tpl">
<li class="image-list" _id="{{= id}}">
    <a class="fancybox" href="{{= img_info}}" data-fancybox-group="gallery">
       <img src="{{= img_info}}" />
    </a>
</li>
</script>

<script type="text/x-jquery-tmpl" id="video-add-tpl">
<li class="video-list-item" _id="{{= id}}">
    <img src="{{= vod_img_info}}" />
    <span class="play-video" _url="{$RESOURCE_URL}测试小视频.mp4"></span>
</li>
</script>

<script type="text/x-jquery-tmpl" id="video-tpl">
	<div class="video-box">
		<video class="video-js" controls width="465" height="350">
			<source src="${url}" type="video/mp4">
		</video>
		<span class="close-video">x</span>
	</div>
</script>
