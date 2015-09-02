{template:head}
{css:common/common_form}
{css:hg_sort_box}
{js:jqueryfn/uploadify/jquery.uploadify}
{js:2013/ajaxload_new}
{js:hg_sort_box}
{js:common/common_form}
{css:video_yun}
{css:video/video}
{js:video/jquery.video.new}
{js:video/video_canvas}
{js:template_store/upYun}
{css:2013/iframe_form}
{css:2013/button}
{css:2013/form}
{css:template_form}
{code}
  $image_resource = RESOURCE_URL;
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
	
	$formdata['sort_name'] = $formdata['sort_name'] ? $formdata['sort_name'] : '选择分类:';
	
	//hg_pre($formdata);
{/code} 
<script type="text/javascript">
var gUploadApi = {code} echo $_configs['upload_settings'] ? json_encode($_configs['upload_settings']) : '{}';{/code};
</script>
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

<form  action="./run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" name="vodform"  id="vodform"  onsubmit="return  hg_toSubmit();" >
<div class="common-form-head vedio-head">
     <div class="common-form-title">
          <h2>{if $formdata['id']}编辑{else}新增{/if}模板</h2>
          <div class="form-dioption-title form-dioption-item">
                <input type="text" id="vod-title" name="title" class="title need-word-count" placeholder="请输入模板标题"  value="{if $formdata['title']}{$formdata['title']}{/if}" />
          </div>
          <input type="hidden" name="submit_type" id="submit_type"/>
		  <div class="form-dioption-submit">
		      <input type="submit" value="保存模板" class="common-form-save" />
		      <span class="option-iframe-back">关闭</span>
		  </div>
    </div>
</div>

	
<div class="common-form-main m2o-inner">
	<div class="m2o-main m2o-flex">
	<div class="m2o-l form-left">
        <div class="form-dioption">
		
		<div class="form-dioption-inner">
		<div class="form-edit-img">
			<div class="form-cioption-indexpic form-cioption-item">
                {code}
	                $indexpic_url = $formdata['index_pic'];
	                if($indexpic_url){
	                    $indexpicsrc = $indexpic_url['host'].$indexpic_url['dir'].'160x160/'.$indexpic_url['filepath'].$indexpic_url['filename'];
	                }else{
	                    $indexpicsrc = '';
	                }
                {/code}
                <div class="indexpic-box {if $indexpic_url}hasimg{/if}">
                    <div class="indexpic" style="font-size:0;">
                        <img style="max-width:160px;max-height:160px;{if !$indexpicsrc}display:none;{/if}" src="{$indexpicsrc}" title="索引图" id="indexpic_url" />
                    </div>
                    <span class="indexpic-suoyin {if $indexpicsrc}indexpic-suoyin-current{/if}"></span>
                    <input type="file" name="indexpic" style="display:none;" />
                </div>
            </div>
			<div class="form-dioption-sort form-dioption-item"  id="sort-box">
				
				<label style="color:#9f9f9f;{if !$formdata['sort_id']}display:none;{/if}">分类： </label><p style="display:inline-block;" class="sort-label" _multi="template_sort"> {$formdata['sort_name']}<img class="common-head-drop" src="{$RESOURCE_URL}tuji/drop.png" style="position: relative;left:10px;bottom:2px;" /></p>
					<div class="sort-box-outer"><div class="sort-box-inner"></div></div>
	               <input type="hidden" value="{$formdata['sort_id']}" name="sort_id" id="sort_id" />
			</div>
			    
			<div class="form-dioption-keyword form-dioption-item clearfix">
				<span class="keywords-del"></span>
				<span class="form-item" _value="添加标签" id="keywords-box">
					<span class="keywords-start color">添加标签</span>
					<span class="keywords-add">+</span>
				</span>
				<input name="keywords" value="{$formdata['keywords']}" id="keywords" style="display:none;"/>
			</div>
			
			<div class="form-dioption-item">
        	        <span class="title">色系：</span>
					{code}
						$item_source = array(
							'class' => 'down_list',
							'show' => 'color_show',
							'width' => 125,/*列表宽度*/		
							'state' => 0, /*0--正常数据选择列表，1--日期选择*/
							'is_sub'=>1,
						);
						$default = $formdata['color'] ? $formdata['color'] : 0;
						$template_color[0] = '--选择色系--';

					{/code}
					{template:form/search_source,template_color,$default,$template_color,$item_source}
   			   </div>
   			   <div class="form-dioption-item">
        	        <span class="title">风格：</span>
					{code}
						$item_source = array(
							'class' => 'down_list',
							'show' => 'style_show',
							'width' => 125,/*列表宽度*/		
							'state' => 0, /*0--正常数据选择列表，1--日期选择*/
							'is_sub'=>1,
						);
						$default = $formdata['style'] ? $formdata['style'] : -1;
						$template_style[-1] = '--选择风格--';

					{/code}
					{template:form/search_source,template_style,$default,$template_style,$item_source}
   			   </div>
   			   <div class="form-dioption-item">
        	        <span class="title">用途：</span>
					{code}
						$item_source = array(
							'class' => 'down_list',
							'show' => 'use_show',
							'width' => 125,/*列表宽度*/		
							'state' => 0, /*0--正常数据选择列表，1--日期选择*/
							'is_sub'=>1,
						);
						$default = $formdata['use'] ? $formdata['use'] : -1;
						$template_use[-1] = '--选择用途--';
						

					{/code}
					{template:form/search_source,template_use,$default,$template_use,$item_source}
   			   </div>
   			   <div class="form-dioption-item">
        	        <span class="title">版本：</span>
					{code}
						$item_source = array(
							'class' => 'down_list',
							'show' => 'version_show',
							'width' => 125,/*列表宽度*/		
							'state' => 0, /*0--正常数据选择列表，1--日期选择*/
							'is_sub'=>1,
						);
						$default = $formdata['version'] ? $formdata['version'] : -1;
						$template_version[-1] = '--选择版本--';

					{/code}
					{template:form/search_source,template_version,$default,$template_version,$item_source}
   			   </div>
   			   <!--
   			   <div class="form-dioption-item">
        	        <span class="title">价格：</span>
					{code}
						$item_source = array(
							'class' => 'down_list',
							'show' => 'price_show',
							'width' => 125,/*列表宽度*/		
							'state' => 0, /*0--正常数据选择列表，1--日期选择*/
							'is_sub'=>1,
						);
						$default = $item ? $item : -1;
						$price = array(
							0 => '蓝色系',
							1 => '紫色系',
							2 => '黑色系'
						);
						$price[$default] = '--选择价格--';

					{/code}
					{template:form/search_source,price,$default,$price,$item_source}
   			   </div>
   			-->
		</div>
		
		</div>
            
		</div>
	</div>
	<div class="m2o-m m2o-flex-one">
        <div class="vod-info-box clear vod-info-box-with-bottom">
			<div class="vod-info-item">
				<textarea rows="5" name="brief"  id="common" {if $formdata['brief']}{$formdata['brief']}{else}class="t_c_b"{/if}  onfocus="text_value_onfocus(this,'请添加模板描述');" onblur="text_value_onblur(this,'请添加模板描述');">{if $formdata['brief']}{$formdata['brief']}{else}请添加模板描述{/if}</textarea>
			</div>
			<div class="m2o-flex vod-info-item">
				<div class="template-item">
        	        <span class="title">大小：</span>
					<input class="txt-input" type="text" name="size" placeholder="素材大小" value="{$formdata['size']}"/>
   			   </div>
   			   <div class="template-item">
        	        <span class="title">尺寸：</span>
					<input class="txt-input" type="text" name="resolution" placeholder="素材分辨率" value="{$formdata['resolution']}"/>
   			   </div>
   			   <div class="template-item">
        	        <span class="title">时长：</span>
					<input class="txt-input" type="text" name="duration" placeholder="素材时长" value="{$formdata['duration']}"/>
   			   </div>
   			   <div class="template-item">
        	        <span class="title">格式：</span>
					<input class="txt-input" type="text" name="format" placeholder="素材格式" value="{$formdata['format']}"/>
   			   </div>
			</div>
			<div class="control-box">
				<div class="m2o-flex m2o-flex-center template-material-box">
					<div class="explain-button material-btn">上传模板素材</div>
					<div class="template-material-name">
					</div>
					{if $formdata['material']}
						<a href="{code} echo hg_bulid_img($formdata['material']);{/code}" style="text-decoration:underline">下载</a>
					{/if}
					<input type="file" name="template_material" style="display:none;" />
				</div>
				<div class="m2o-flex">
					<a class="explain-button open-video-btn">上传预览视频</a>
					{if $formdata['vodinfo']['video_m3u8']}
					<div style="width:360px;height:300px;margin-left:30px;background:#2e2e2e;">
					  <object id="vodPlayer" type="application/x-shockwave-flash" data="{code}echo RESOURCE_URL{/code}swf/vodPlayer.swf?11122713" width="360px" height="300">
						<param name="movie" value="{code}echo RESOURCE_URL{/code}swf/vodPlayer.swf?11122713">
						<param name="allowscriptaccess" value="always">
						<param name="allowFullScreen" value="true">
						<param name="wmode" value="transparent">
						<param name="flashvars" value="videoUrl={$formdata['vodinfo']['video_m3u8']}&autoPlay=false&aspect={$aspect}">
					  </object>
					</div>
					{/if}
					{if isset($formdata['status']) && $formdata['status'] ==0}
					<div class="m2o-flex-center" style="margin-left:20px;line-height:40px;">视频转码中...</div>
					{/if}
				</div>
			</div>
		</div>
	</div>
	<div class="m2o-r">
        	<div class="vod-details">
        			<a class="content_vodinfo_text" id="video-mark-btn" style="cursor:default;">源视频信息</a>
        		<ul>
        			{code}$vodinfo = $formdata['vodinfo'];{/code}
        			<li>时长:<span>{$vodinfo['video_duration']}</span></li>
        			<li>文件大小:<span>{$vodinfo['video_totalsize']}</span></li>
        			<li>视频编码:<span>{$vodinfo['video']}</span></li>
        			<li>平均码流:<span>{$vodinfo['bitrate']}</span></li>
        			<li>视频帧率:<span>{$vodinfo['frame_rate']}</span></li>
        			<li>分辨率:<span>{$vodinfo['video_resolution']}</span></li>
        			<li>宽高比:<span>{$vodinfo['aspect']}</span></li>
        			<li>音频编码:<span>{$vodinfo['audio']}</span></li>
        			<li>音频采样率:<span>{$vodinfo['sampling_rate']}</span></li>
        			<li>声道:<span>{$vodinfo['video_audio_channels']}</span></li>
        			
        		</ul>
        	</div>
    </div>
	</div>
	<input type="file" id="file" style="display: none;" />
	<input type="hidden"   id="source_img_pic"  name="source_img_pic"  value="{$formdata['source_img']}" />
	<input type="hidden" name="video" value="{$formdata['video']}" />
	<input type="hidden" name="host" />
	<input type="hidden" name="port" />
	<input type="hidden" value="{$a}" name="a" />
	<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
	<input type="hidden" value="{$_INPUT['mid']}" name="module_id" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
</div>
</form>

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

<script>
	$(function(){
		
		/*上传索引图*/
		var indexpic_box = $('.indexpic-box'),
			indexpic_input_file = indexpic_box.find('input[type="file"]');
		indexpic_box.find('.indexpic').on('click',function(event){
			indexpic_input_file.click();
		});
		
		indexpic_input_file.on('change',function(){
			var file = this.files[0],
				reader = new FileReader();
			reader.onloadend = function(event){
				var result = event.target.result;
				indexpic_box.find('img').attr('src',result).show();
				indexpic_box.find('.indexpic-suoyin').addClass('indexpic-suoyin-current');
				indexpic_box.addClass('hasimg');
			};
			reader.readAsDataURL(file);
		})
		
		/*上传模版素材*/
		var material_box = $('.template-material-box'),
			material_input_file = material_box.find('input[type="file"]');
		material_box.on('click','.material-btn',function(){
			material_input_file.click();
		});
		
		material_input_file.on('change',function(event){
			var file = this.files[0],
				name = file.name;
			material_box.find('.template-material-name').text( name );
		});
		
	});
</script>

{template:foot}