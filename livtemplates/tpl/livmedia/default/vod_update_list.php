{template:head}
{css:common/common_form}
{css:upload_vod}
{css:vod_update_list}
{js:upload_vod}
{js:vod}
{js:column_node}
{js:jscroll}
{css:column_node}
{js:vod_upload_pic_handler}
{template:form/common_form}
{css:hg_sort_box}
{js:hg_sort_box}
{js:common/auto_textarea}
{js:common/common_form}
{js:vod/vod_form}
{js:common/ajax_upload}
{css:catalog}
{js:catalog}
{css:video/video}
{js:video/jquery.video.new}
{js:video/video_canvas}
{code}
  $weight = $formdata['weight'];
  $markswf_url = RESOURCE_URL.'swf/';
  $image_resource = RESOURCE_URL;
  $levelLabel = array(0, 1, 2, 3, 10, 20, 30, 40, 50, 60, 70, 80, 90);
{/code}
<!-- 来源控件的数据 -->
{code}
	$item_up_source = array(
		'class' => 'down_list',
		'show' => 'source_show',
		'width' => 130,/*列表宽度*/		
		'state' => 0, /*0--正常数据选择列表，1--日期选择*/
		'is_sub'=>1,
	);
	
	/*
	if($formdata['source'])
	{
	   $default = $formdata['source'];
	}
	else
	{
	   $default = -1;
	}
	$sources[-1] = '自动';
	foreach($source as $k =>$v)
	{
		$sources[$v['id']] = $v['name'];
	}
	
	$source_id = 'update_source_id';
	*/
{/code} 
<!-- 分类控件的数据 -->	               	  
{code}
	$item_up_sort = array(
		'class' => 'down_list',
		'show' => 'up_sort_show',
		'width' => 90,/*列表宽度*/		
		'state' => 0, /*0--正常数据选择列表，1--日期选择*/
		'is_sub'=>1,
	);
	
	
	foreach($formdata['sort_name'] as $k =>$v)
	{
		$sorts[$v['id']] = $v['name'];
	}
	
	$vod_sort_id = 'update_sort_id';
	$sort_default = $formdata['vod_sort_id'];
	$sort_default_name = ($formdata['vod_sort_name'] != '无' ? $formdata['vod_sort_name'] : '选择分类');
	//hg_pre($formdata);
{/code} 

<script>
jQuery(function($){
	var is_link = {code}echo $formdata['is_link'] ? $formdata['is_link'] : 0 {/code};
	if( is_link ){
		return;
	}
    $('.video-btn').on({
        click : function(event){
            var video = $('#video');
            if(!video.is(':ui-video')){
                video.video({
                    slider : false,
                    bj : true,
                    kz : true,
                    autoPlay : true,
                    autoBuffer : true,
                    customEvents : {
                        '_change.video' : function(event, info){
                            $(this).video('option', 'zhen', info['zhen']);
                            var fen = info['fen'];
                            var canvas = $.createCanvas({
                                width : fen[0],
                                height : fen[1]
                            });
                            $(this).data('canvas', canvas);
                            $(this).attr({
                                src : info['src'],
                                poster : info['img']
                            });
                            this.load();
                        },

                        'play.video' : function(){
                            $.Timer.start();
                        },

                        'pause.video' : function(event, needPause){
                            needPause && this.pause();
                            $.Timer.stop();
                        },

                        'timeupdate.video' : function(){
                        },

                        'emptied.video' : function(){
                        },

                        'seeked.video' : function(){
                        },

                        'error.video' : function(){
                        }
                    },

                    clickKZ : function(){
                        var $this = $(this);
                        var imgData = $this.data('canvas').getImgFromVideo();
                        var startP = $this.offset();
                        var startWH = [$this.width(), $this.height()];
                        var source = $('.source-img');
                        var endP = source.offset();
                        var endWH = [source.width(), source.height()];
                        var url ="run.php?mid=" + gMid + "&a=preview_pic&admin_id=" + gAdmin['admin_id'] + "&admin_pass=" + gAdmin.admin_pass;
                        var base64img = encodeURIComponent( imgData );
                        $.post( url, { Filedata : base64img, base64 : true},function( data ){
                        	if( $.isArray( data ) && data.length ){
                        		imgData = data[0];
                        	}else{
                        		return;
                        	}
                        	$('<img/>').attr({
	                            src : imgData,
	                            style : 'position:absolute;left:' + startP.left + 'px;top:' + startP.top + 'px;z-index:100000;width:' + startWH[0] + 'px;height:' + startWH[1] + 'px;'
	                        }).appendTo('body').animate({
	                            left : endP.left + 'px',
	                            top : endP.top + 'px',
	                            width : endWH[0] + 'px',
	                            height : endWH[1] + 'px',
	                            opacity : 0
	                        }, 500, function(){
	                            source.find('img').attr('src', imgData);
	                            $(this).remove();
	                        });
	                        $('#source_img_pic').val(imgData);
	                        indexPicEdit();
                        },'json' );
                    }
                });
                var fen = "{$formdata['video_resolution']}";
                fen = fen ? fen.split('*') : [640, 480];
                video.video('changeVideo', {
                    zhen : parseInt("{$formdata['frame_rate']}"),
                    src : "./vod{code}echo $formdata['dir_index'] && $formdata['dir_index'] > 0 ? $formdata['dir_index'] : '';{/code}/" + "{$formdata['video_path']}" + "{$formdata['video_filename']}",
                    poster : "{$formdata['source_img']}",
                    fen : fen
                });

                setTimeout(function(){
                    $('.drag-tip').removeClass('on');
                }, 2000);
            }

            var isOpen = $(this).data('open');
            $('#video-box').triggerHandler(isOpen ? 'hide' : 'show');
            if(isOpen){
                video.trigger('pause', [true]);
            }
            $(this).data('open', !isOpen);
        }
    });

    $('#video-box').on({
        show : function(){
            var vb = $('.video-btn');
            var vbp = vb.offset();
            $(this).css({
                left : vbp.left + vb.outerWidth() + 10 + 'px',
                top : vbp.top + 'px'
            }).show();
        },

        hide : function(){
            $(this).hide();
        }
    }).draggable();

    $('.drag-close').on({
        click : function(){
            $('.video-btn').trigger('click');
        }
    });
})
</script>

<style>
.form-dioption-keyword .color,.form-dioption-fabu .color{color: #A5A5A5;}
.form-dioption-item, .form-cioption-item{margin:0;}
.form-dioption-keyword label input{vertical-align:middle;margin-right:5px;}
.form-dioption-keyword label{width:100px;line-height:22px;display:inline-block;}
#keywords-box .a{position:relative;}
.b{width:70px;height:18px;position:absolute;top:-3px;left:0;}
#hoge_edit_play{-moz-transition:all 0.3s ease-in;}
.form-middle-left{overflow:visible;}
.form-title-option span{margin-top:2px;}
</style>
{if $formdata['id']}
{css:2013/iframe_form}
{css:2013/list}
<form  action="./run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" name="vodform"  id="vodform"  onsubmit="return  hg_toSubmit();" >
<div class="common-form-head vedio-head">
     <div class="common-form-title">
          <h2>编辑视频</h2>
          <div class="form-dioption-title form-dioption-item">
                <!-- <textarea type="text" id="vod-title" name="title"  onfocus="text_value_onfocus(this,'请输入标题');" onblur="text_value_onblur(this,'请输入标题');" >{if $formdata['title']}{$formdata['title']}{else}请输入标题{/if}</textarea> -->
                <input type="text" id="vod-title" name="title" class="title need-word-count"  onfocus="text_value_onfocus(this,'请输入标题');" onblur="text_value_onblur(this,'请输入标题');" value="{if $formdata['title']}{$formdata['title']}{else}请输入标题{/if}" />
                <div class="color-selector clearfix">
                    <span class="form-title-color"></span>
                    <span class="form-title-weight"></span>
                    <span class="form-title-italic"></span>
                </div>
                        <input name="tcolor" type="hidden" value="{$formdata['tcolor']}" id="tcolor" />
		                <input name="isbold" type="hidden" value="{if $formdata['isbold']}1{else}0{/if}" id="isbold" />
		                <input name="isitalic" type="hidden" value="{if $formdata['isitalic']}1{else}0{/if}" id="isitalic" />
                      <input name="weight" value="{$weight}" id="weight" type="hidden" />
          </div>
          <input type="hidden" name="submit_type" id="submit_type"/>
		  <div class="form-dioption-submit">
		      <input type="submit" value="保存视频" class="common-form-save" />
		      <span class="option-iframe-back">关闭</span>
		  </div>
		  <div id="weightPicker" style="right:246px;">
	                    {template:list/list_weight,agd,$weight}
          </div>   
    </div>
</div>
	
<div class="common-form-main vedio-area">
    {template:unit/publish_for_form, 1, $formdata['column_id']}
	<div class="form-left">
        <div class="form-dioption">
		
		<div class="form-dioption-inner">
		<div class="form-edit-img">
			{code}
			if( $formdata['starttime'] ){
			$starttime = date('Y-m-d',$formdata['starttime'] );
			}
			{/code}
			{if $starttime}<span class="zhibo-date">{$starttime}</span>{/if}
			<div class="form-dioption-source-img" style="position:relative;">
				<a class="source-img">
					<img _src="{$formdata['source_img']}" id="pic_face" title="点击图片更换截图" _state="{if $formdata['source_img']}1{else}0{/if}"/>
				</a>
				<p {if $formdata['is_link']}onclick="video_show();"{/if} title="显示、关闭视频/ALT+W" class="video-btn">
					视频预览/截屏<!-- <img style="margin: 0 0 2px 5px;" src="{$RESOURCE_URL}tuji/drop.png" />  -->
				</p>
				<div class="source-img-box">
					<span></span>
					<ul id="add-img">
					{foreach $formdata['snap_img'] as $k => $v}
						<li class="snap-img"><div class="middle-img-wrap"><img src="{$v}" /></div></li>
					{/foreach}
						<li class="add-img-button">从电脑添加</li>	
						<div class="clear"></div>
					</ul>
				</div>
			</div>
			<div class="form-dioption-sort form-dioption-item"  id="sort-box">
				<label style="color:#9f9f9f;{if $sort_default_name == '选择分类'}display:none;{/if}">分类： </label><p style="display:inline-block;" class="sort-label" _multi="vod_media_node">{$sort_default_name}<img class="common-head-drop" src="{$RESOURCE_URL}tuji/drop.png" style="position: relative;left:10px;bottom:2px;" /></p>
						<div class="sort-box-outer"><div class="sort-box-inner"></div></div>
				<input type="hidden" value="{$sort_default}" name="update_sort_id" id="sort_id" />
			</div>
			    
		
			<div class="form-dioption-keyword form-dioption-item clearfix">
				<span class="keywords-del"></span>
				<span class="form-item" _value="添加关键字" id="keywords-box">
					<span class="keywords-start color">添加关键字</span>
					<span class="keywords-add">+</span>
				</span>
				<input name="keywords" value="{$formdata['keywords']}" id="keywords" style="display:none;"/>
			</div>
			<div class="form-dioption-fabu form-dioption-item">
				<a class="common-publish-button color overflow" href="javascript:;" _default="发布至" _prev="发布至：">发布至</a>
			</div>
			<div id="lumin"></div>
		</div>
		
		</div>
            
		</div>
	</div>
	<div class="form-middle">
        <div class="form-middle-left">
			<div class="vod-info-box clear vod-info-box-with-bottom">
				<div class="vod-info-item">
					<textarea rows="5" name="comment"  id="comment" {if $formdata['comment']}{$formdata['comment']}{else}class="t_c_b"{/if}  onfocus="text_value_onfocus(this,'这里输入描述');" onblur="text_value_onblur(this,'这里输入描述');">{if $formdata['comment']}{$formdata['comment']}{else}这里输入描述{/if}</textarea>
				</div>
			</div>
			<div class="vod-info-box clear vod-info-box-with-bottom m2o-flex">
				<div class="vod-info-item">
					<label class="input-label" for="subtitle">副题</label>
					<input type="text" name="subtitle" id="subtitle"  value="{$formdata['subtitle']}" />
				</div>
				<div class="vod-info-item laiyuan" style="margin-right:10px;">
					<label class="input-label" >来源</label>
					<input type="text" name="source" value="{$formdata['source']}" style="width:128px;height:20px;" />
				</div>
				<div class="vod-info-item zz" style="float:left;">
					<label class="input-label">作者</label>
					<input type="text" name="author" id="author" value="{$formdata['author']}" />
				</div>
			</div>
			<div class="vod-info-box clear vod-info-box-with-bottom" style="display: none">
				<div class="vod-info-item vod-info-subtitle">
					<label class="input-label" for="subtitle">选项</label>
					<!--<input type="text" name="keywords" id="keywords" value="{$formdata['keywords']}" />-->
					<div class="form-dioption-keyword clearfix">
						<label><input type="checkbox" value="name" />开发评论</label>
						<label><input type="checkbox" value="name" />自动台标</label>
						<label><input type="checkbox" value="name" />附加广告</label>
						<label><input type="checkbox" value="name" />允许打分</label>
						<label><input type="checkbox" value="name" />观看心情</label>
					</div>
				</div>
				
			</div>
			
			<input type="hidden" name="submit_type" id="submit_type"/>
     
        	
        </div>
        
        <div class="form-middle-right">
        	<div class="vod-details">
        			<a class="content_vodinfo_text" id="video-mark-btn" style="cursor:default;">源视频信息</a>
        			<!--{if $formdata['is_fast_edit']}
						<a class="content_vodinfo_text" data-isneed="need" _href="./run.php?mid={$_INPUT['mid']}&a=video_mark&id={$formdata['id']}&fast_edit=1{$_pp}" target="mainwin" id="video-mark-btn">源视频快编</a>
					{else}
						<a class="content_vodinfo_text"  href="javascript:void(0);"  onclick="return false;alert('此视频已被标注，不可快编');" id="video-mark-btn">源视频快编</a>
					{/if}-->
        		<ul>
        			<li>时长:<span>{$formdata['video_duration']}</span></li>
        			<li>文件大小:<span>{$formdata['video_totalsize']}</span></li>
        			<li>视频编码:<span>{$formdata['video']}</span></li>
        			<li>平均码流:<span>{$formdata['bitrate']}</span></li>
        			<li>视频帧率:<span>{$formdata['frame_rate']}</span></li>
        			<li>分辨率:<span>{$formdata['video_resolution']}</span></li>
        			<li>宽高比:<span>{$formdata['aspect']}</span></li>
        			<li>音频编码:<span>{$formdata['audio']}</span></li>
        			<li>音频采样率:<span>{$formdata['sampling_rate']}</span></li>
        			<li>声道:<span>{$formdata['video_audio_channels']}</span></li>
        			
        		</ul>
        	</div>
        	<div class="vod-option" data-tip="暂时隐藏" style="display:none;">
        		<p>选项:</p>
        		<ul>
                	<li><label><input type="checkbox" value="1" name="comment2out">开放评论</label></li>
                	<li><label><input type="checkbox" value="2" name="taibiao">自动台标</label></li>
                	<li><label><input type="checkbox" value="3" name="guanggao">附加广告</label></li>
                	<li><label><input type="checkbox" value="4" name="dafen">允许打分</label></li>
                	<li><label><input type="checkbox" value="5" name="xinqing">观看心情</label></li>
                </ul>
        	</div>
        </div>
	</div>
	<input type="file" id="file" style="display: none;" />
	<input type="hidden"   id="source_img_pic"  name="source_img_pic"  value="{$formdata['source_img']}" />
	<input type="hidden"   id="vod_sort_id"  name="vod_sort_id"  value="" />
	<input type="hidden" name="img_src_cpu"  id="img_src_cpu"  value="" />
	<input type="hidden" name="img_src"  id="img_src"  value=""   />
	<input type="hidden" value="{$a}" name="a" />
	<input type="hidden" value="{$_INPUT['mid']}" name="module_id" />
	<input type="hidden" value="{$formdata['total_num']}"  id="total_num" />
	<input type="hidden" value="{$formdata['page_num']}"  id="page_num" />
	<input type="hidden" value="{$formdata['id']}" name="id" id="edit_id" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	
	<div class="right_version"  style="overflow:hidden;display:none;">
		<h2><a href="{$_INPUT['referto']}">返回上页</a></h2>
		<h2 class="b">历史版本</h2>
			<ul class="u" id="copyright_list">
			{if $formdata['update_copyright']}
			{foreach $formdata['update_copyright'] as $k => $v}
				<li  onclick="hg_get_copyright(this,{$v['id']},{$_INPUT['mid']});"  style="cursor:pointer;"  name="copyright[]" >
					<span class="time">{$v['update_time']}</span>
					<span>{$v['update_man']}编辑</span>
				</li>
			{/foreach}
			{/if}	
			</ul>
			{if $formdata['total_num'] > $formdata['page_num']}
			<div class="more"  id="haveMore" onclick="hg_getMoreCopyright({$$primary_key},{$_INPUT['mid']});">更多<span></span></div>
			{/if}
	</div>
</div>
</form>
<div id="hoge_edit_play" style="top:-378px;left:273px;">
<img class="move_img_a" src="" id="img_move" style="width:320px;" />
<object id="1video" type="application/x-shockwave-flash" data="{if $formdata['is_link']}{$formdata['swf']}{else}{$markswf_url}vodPlayer.swf?{$formdata['time']}{/if}" width="320" height="270">
	<param name="movie" value="{$markswf_url}vodPlayer.swf?{$formdata['time']}">
	<param name="allowscriptaccess" value="always">
	<param name="wmode" value="transparent">
	<param name="allowFullScreen" value="true">
	<param name="flashvars" value="jsNameSpace=adminDemandPlayer&startTime={$formdata['start']}&duration={$formdata['duration']}&videoUrl={$formdata['video_url']}&videoId={$formdata['vodid']}&snap=true&autoPlay=true&snapUrl={$formdata['snapUrl']}&aspect={$formdata['aspect']}">
</object>
</div>

<style>
#video-box{position:absolute;left:0;top:0;z-index:10000;display:none;}
.drag-tip{1display:none;position:absolute;left:-2px;top:-30px;height:30px;line-height:30px;width:100%;background:#5C99CF;cursor:move;text-indent:1em;color:#fff;font-weight:bold;border:2px solid #5C99CF;border-bottom:none;}
#video-box:hover .drag-tip, .drag-tip.on{display:block;}
.drag-close{z-index:10;position:absolute;right:-2px;top:-2px;height:30px;width:30px;line-height:30px;text-align:center;background:red;cursor:pointer;color:#fff;text-indent:0;}
</style>
<div id="video-box">
    <div class="drag-tip on">按住此处可以拖动视频<span class="drag-close">x</span></div>
    <video id="video" width="500" height="400"></video>
</div>
{else}
此视频不存在,<a href="./run.php?mid={$_INPUT['mid']}&infrm=1">请返回</a>
{/if}
{template:foot}