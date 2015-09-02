{template:head}
{css:ad_style}
{js:common/ajax_upload}
{js:hg_sort_box}
{js:common/auto_textarea}
{css:common/common_form}
{css:news_form}
{js:common/common_form}
{css:column_node}
{js:column_node}
{css:2013/iframe_form}
{css:vod_style}
{code}
    $formdata = $$return_var;
    //print_r( $formdata );
{/code}
<script type="text/javascript">
    $(function(){
        $('.form-add').on('click', function(){
            $('.form-file').trigger('click');
        });
        $(".form-file").ajaxUpload({
            url : 'run.php?mid='+gMid+'&a=upload_indexpic',
            phpkey : 'indexpic',
            filter : function(data){
                data.append('title', $('#title').val());
            },
            before : function(data){
                var src = data.data.result;
                var title = data.file.name;
                if(src){
                    $('.indexpic-box').find('img').attr('src', src).attr('title', title).end().find('#suoyin').addClass('indexpic-suoyin-current');
                }
            },
            after : function(data){
                var json = data.data;
                if(json.success)
                {
                   //var src =  json.host+json.dir+json.filepath+json.filename;
                   $('.indexpic-box').attr('title', json.filename);
                   $('#indexpic_value').val(json.id);
                    $('#material_id').val(json.id);
                   return;
                }
                alert('上传失败！')
            }
        });
    })
    $(function ($) {
        var $title = $('input[name=title]'),
        	$link = $('input[name=outlink]');
    	function checkEmpty(el, msg) {
			if ( el.val() === '' ) {
				jAlert( msg + '不能为空!', '提示' );
				return false;
			}
			return true;
        }
        $('#vod_media_node_form').submit(function () {
        	var bValid = true;
        	bValid = bValid && checkEmpty($title, '标题');
        	bValid = bValid && checkEmpty($link, '链接');
        	return bValid;
        });

        var form = $('#vod_media_node_form').append('<div style="height:200px;"></div>');
        $(window).resize(function(){
            form.height(function(){
                return $(window).height();
            });
        }).resize();

    });

    $(function($){
    return;
        $.includeUEditor(function(){
            $.m2oEditor.get($('#test')[0], {
                initialFrameHeight : 500,
                initialFrameWidth : 800,
                autoHeightEnabled : true,
                autoFloatEnabled : false
            });
        }, 'all');
    });

</script>
<style>
.common-publish-button{display:inline;padding-left:24px;margin-right:5px;text-align:right;color:#7d7d7d;}
</style>

<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l" id="vod_media_node_form" name="vod_media_node_form" lonsubmit="return hg_ajax_submit('vod_media_node_form');" style="display:block;overflow:auto;">
<div class="common-form-head news-outlink-head">
     <div class="common-form-title">
          <h2>{$optext}外链</h2>
          <div class="form-dioption-title form-dioption-item" style="width:710px;">
                <input type="text" name="title" id="title"  placeholder="添加外链标题" class="title need-word-count" value="{$formdata['title']}" />
                <div class="color-selector clearfix" style="right:0;">
                    <span class="form-title-color"></span>
                    <span class="form-title-weight"></span>
                    <span class="form-title-italic"></span>
                </div>
                   <input name="tcolor" type="hidden" value="{$formdata['tcolor']}" id="tcolor" />
                   <input name="isbold" type="hidden" value="{if $formdata['isbold']}1{else}0{/if}" id="isbold" />
                   <input name="isitalic" type="hidden" value="{if $formdata['isitalic']}1{else}0{/if}" id="isitalic" />
          </div>
          <input type="hidden" name="submit_type" id="submit_type"/>
		  <div class="form-dioption-submit">
		      <input type="submit" name="sub" value="{$optext}外链" class="common-form-save" />
		      <span class="option-iframe-back">关闭</span>
		  </div>   
    </div>
</div>
<script id="test" type="text/ueditor" style="margin:0 auto;width:800px;"></script>
<div class="common-form-main news-outlink-main">
   <div class="outlink-area">
    <ul class="form_ul">
        <li class="i">
            <div class="form_ul_div">
                <span class="title">描述：</span>

                <textarea rows="2" style="width:600px;display:inline-block;" class="info-description info-input-left t_c_b" name="brief" onfocus="text_value_onfocus(this,'这里输入描述');" onblur="text_value_onblur(this,'这里输入描述');">{code}echo $formdata['brief'] ? $formdata['brief'] : '这里输入描述';{/code}</textarea>
            </div>
        </li>
        <li class="i">
            <div class="form_ul_div clear">
                <span class="title">外部链接：</span><input  type="text" name="outlink" style="width:460px;height:30px;" value="{$formdata['outlink']}"/>
            </div>
        </li>
        <li class="i">
            <div class="form_ul_div clear">
                <span class="title">索引图片：</span>
                <div class="form-add" style="float:left;">
                     {code}
                            
                            $default_indexpic_url = RESOURCE_URL.'news/suoyin-default.png';
							
							$indexpic_url = $formdata['indexpic_url'];
                            if($indexpic_url){
                                $indexpicsrc = $indexpic_url['host'].$indexpic_url['dir'].$indexpic_url['filepath'].$indexpic_url['filename'];
                            }else{
                                $indexpicsrc = '';
                            }
                    {/code}
                     <div class="indexpic-box">
                         <div class="indexpic">
                           <script>
                            $(function(){
                                if($.pixelRatio > 1){
                                    var index = $('#indexpic_url');
                                    if(index.attr('_state') < 1){
                                        index.attr('src', index.attr('_default').replace('.png', '-2x.png')).css('width', '49px');
                                    }
                                }
                            });
                          </script>
                            <img style="max-width:200px;max-height:140px;" title="索引图" id="indexpic_url" _state="{if $indexpicsrc}1{else}0{/if}" _default="{$default_indexpic_url}"  src="{if !$indexpicsrc}{$default_indexpic_url}{else}{$indexpicsrc}{/if}">
                        </div>
                        <span class="{if $indexpicsrc}indexpic-suoyin-current{else}indexpic-suoyin{/if}" id="suoyin"></span>
                     </div>
                </div>
                <input name="indexpic" value="{$formdata['indexpic']}" id="indexpic_value" type="hidden"/>
                <input name="material_id[]" value="{$formdata['indexpic']}" id="material_id" type="hidden"/>
            </div>
        </li>
        <li class="i">
            <div class="form_ul_div">       
              <span class="title">分类：</span>
			{code}
				$hg_attr['node_en'] = 'news_node';
			{/code}
			{template:unit/class,sort_id,$formdata['sort_id'], $node_data}      
            </div>
        </li>
        <!--  
        <li class="i" {if !$formdata['indexpic_url']}style="display: none"{/if} id="indexpic_preview">
            <div class="form_ul_div clear">
                <span class="title">预览：</span>
                <div style="height:160px;width:600px;overflow: scroll;">
                    <img src="{$formdata['indexpic_url']['host']}{$formdata['indexpic_url']['dir']}{$formdata['indexpic_url']['filepath']}{$formdata['indexpic_url']['filename']}"/>
                </div>
            </div>
        </li>-->
        <li class="i" style="background:none;">
            <div class="form_ul_div clear">
                   <a class="common-publish-button overflow" href="javascript:;" _default="发布至：无" _prev="发布至："></a>
                </div>
            </div>
        </li>
        <input type="file" class="form-file" style="display: none"/>
    </ul>
   </div>
    <input type="hidden" name="a" value="{$a}" />
    <input type="hidden" name="{$primary_key}" value="{$formdata['id']}" />
    <input type="hidden" name="mid" value="{$_INPUT['mid']}" />
    <input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
    <input type="hidden" name="submit_type" value="1" />
</div>  
{template:unit/publish_for_form, 1, $formdata['column_id']} 
</form>