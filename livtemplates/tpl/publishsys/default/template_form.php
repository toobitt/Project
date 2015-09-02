{template:head}
{css:hg_sort_box}
{js:common/auto_textarea}
{js:hg_sort_box}
{js:common/common_form}
{css:ad_style}
{css:common/common_form}
{css:2013/form}
{css:teditor}
{js:teditor}
{code}
$list = $formdata[0];
$site_id= $_INPUT['site_id'];
$client = $clients[0];

if(count($client))
{
	$flag = 1;
}
$template_styles = $template_style[0];
$all_content_type = $content_type[0];
$css_attr['style'] = 'style="width:100px"';
$re = $list['sort_id']?$list['sort_name']:'请选择分类';
{/code}
{if is_array($formdata)}
	{foreach $formdata[0] as $key => $value}
		{code}
			$$key = $value;	 
		{/code}
	{/foreach}
{/if}
{code}
    $_INPUT['id'] < 0 && ($_INPUT['id'] = $id = 0);   //id小于0 新增模板
    if($id)
    {
        $a="update";
    }
    else
    {
        $a="create";
    }
{/code}
<script type="text/javascript">
function isvalidatefile(obj){
    var style = $("#file_data").val().substring($("#file_data").val().lastIndexOf(".")+1);
	if(style=="jpeg"||style=="jpg"){
		document.getElementById("pic_data").style.display="block";
	}
	else
	{	
		document.getElementById("pic_data").style.display="none";
	}	
}
</script>
{js:swf_upload}
<script type="text/javascript">
var swfu;
window.onload = function() {
	var settings = {
		flash_url : RESOURCE_URL+"swfupload/swfupload.swf",
		upload_url: "./run.php?mid=" + gMid + "&a=upload&admin_id=" + gAdmin.admin_id + "&admin_pass=" + gAdmin.admin_pass,	
		post_params: {"access_token": gToken},
		file_size_limit : "100 MB",
		file_types : "*.jpg;*.gif;*.png;*.jpeg;*.bmp;",
		file_types_description : "选择图标",
		file_upload_limit : 0,  //配置上传个数
		file_queue_limit : 0,
		custom_settings : {
			progressTarget : "",
			cancelButtonId : ""
		},
		debug: false,

		// Button settings
		button_image_url: RESOURCE_URL+"news_from_cpu.png",
		button_width: "100",
		button_height: "75",
		button_placeholder_id: "circle_upload",
		button_text: '',
		button_text_style: ".theFont { font-size: 12px;color:#FFFFFF;line-height:24px;display:inline-block;text-align:center;height:24px; }",
		button_text_left_padding: 0,
		button_text_top_padding: 4,
		
		//file_queued_handler : fileQueued,
		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		upload_start_handler : uploadStart,
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccessTemplateStyle,
		upload_complete_handler : uploadComplete,
		//queue_complete_handler : queueComplete,	
	};
	swfu = new SWFUpload(settings);
 };
</script>
<style type="text/css">
body{overflow-x:hidden;}
.m2o-main{background:#fff;}
.submit{right:44px;}
.material_log{float:left;position:relative;margin:0 5px 5px 0;}
.material_log span{position:absolute;top:0px;right:0px;background:black;width:15px;height:15px;color:#FFFFFF;text-align:center;display:none;cursor:pointer;}
.down_list ul, .down_list .ul{background:white;border-bottom: 0;display: block;width:100%;clear: both;top: 24px;left: 0;position: absolute;z-index:110;max-height: 200px;overflow-y:auto;}
.transcoding{z-index:1!important;}
.option-iframe-back{margin-top:0;padding-left:0;}
.m2o-inner .edit-area{padding-top:10px;width:320px;}
.m2o-inner .editor_container{box-sizing:border-box;border-right:0;}
.m2o-inner .code-edit{background:transparent!important;height:550px;}
.m2o-inner .editor_container textarea{height:530px;box-sizing:border-box;border-right:1px solid #E9F2FF!important;padding-top:10px;}
.no-template{border:1px dashed #ccc;text-align:center;font-size:20px;color:#7d7d7d;line-height:500px;border-top:0;}
.ad_form .form_ul li:last-child{background:none!important;}
.template-tip-area{display:none;}
.no-template .template-edit-area{display:none;}
.no-template .template-tip-area{display:block;}

</style>
{css:common}
{css:column_node}
{css:template_list}

<div id="channel_form"></div>
	<div>
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l {if $_INPUT['id']}form-compare{/if}" id="form-template">
				<!--head 开始-->
				<header class="m2o-header">
				  <div class="m2o-inner">
			        <div class="m2o-title m2o-flex m2o-flex-center">
			            <h1 class="m2o-l">{if $_INPUT['id']}编辑模板{else}新增模板{/if}</h1>
			            <div class="m2o-m m2o-flex-one">
			                <input placeholder="模板文件重命名" name="title"  class="m2o-m-title" value="{$list['title']}" />
			            </div>
			            <div class="m2o-btn m2o-r temp-edit-buttons">
			                	<input type="submit" value="保存模板" class="m2o-save edit-button submit" name="sub" id="sub" />
			                <span class="m2o-close option-iframe-back"></span>
			            </div>
			        </div>
			       </div>
			    </header>
				<!--head 结束-->
				<div class="m2o-inner">
    			 <div class="m2o-main">
					<div class="template-edit">
						<div class="edit-area">
						<ul class="form_ul">
                        {if !$_INPUT['id']}
                        <li class="i">
                            <div class="form_ul_div clear">
                                <span class="title">创建类型: </span>
                                <input type="radio" name="direct_create" class="switch_create_type" checked="checked" value="0" />文件上传
                                <input type="radio" name="direct_create" class="switch_create_type" value="1" />直接创建
                            </div>
                        </li>
                        {/if}
						<li class="i file_upload_li">
							<div class="form_ul_div">
								<span class="title important">模板文件</span>
								<div class="select-file">
									<input type="file" name="Fileda" id="Filedata1"  value="submit" class="select" style="visibility:hidden;top:-25px;position:absolute;">
									<input class="file-long" disabled="disabled">
									{if $_INPUT['id']}
									<p>上传文件为html、htm、js、css、swf以及图片</p>
									{else}  
									<p>上传文件为html、html、zip压缩包</p>
									{/if}
									<a class="select-file-button">选择文件</a>
								</div>
								  
							</div>
						</li>
                        <!--       只有系统维护账号可以设置全局模板  -->
                        {if $_user['group_type'] == 1}
                        <li class="i">
                             <div class="form_ul_div clear">
                                 <span class="title">全局模板: </span>
                                 <input type="radio" name="global_template" class="switch_global_template" value="1" {if $site_id==0}checked="checked"{/if} {if $id}disabled="disabled"{/if}/>是
                                 <input type="radio" name="global_template" class="switch_global_template" value="0" {if $site_id!=0}checked="checked"{/if} {if $id}disabled="disabled"{/if}/>否
                             </div>
                         </li>
                         {/if}

                         <!--       只有系统维护账号可以设置全局模板 模板类型  -->
                        {if $_user['group_type'] == 1}
                        <div class="global_template_1" {if $site_id!=0}style="display:none"{/if}>
                             <li class="i">
                                <div class="form_ul_div clear">
                                    <span class="title">模板类型: </span>
                                    {template:form/select,content_type,$content_type,$all_content_type,$css_attr}
                                </div>
                             </li>
                        </div>
                        {/if}

                        <div class="global_template_0" {if $site_id==0}style="display:none"{/if}>
                            <li class="i">
                                <div class="form_ul_div clear">
                                    <span class="title">模板套系</span>
                                    {template:form/select,template_style,$list['template_style'],$template_styles,$css_attr}
                                </div>
                            </li>
                            {if $flag}
                            <li class="i">
                                <div class="form_ul_div clear" >
                                    <span class="title">所属终端</span>
                                    {template:form/select,client,$list['client'],$clients[0],$css_attr}
                                </div>
                            </li>
                            {/if}
                        </div>

                        <li class="i">
                            <div class="form-dioption-sort form-dioption-item"  id="sort-box">
                                <label style="color:#9f9f9f;margin-right:15px;{if !$list['sort_id']}display:none;{/if}">模板分类</span></label><p class="sort-label" _multi="template_classify" _site="{$site_id}">{$re}<img class="common-head-drop" src="{$RESOURCE_URL}tuji/drop.png" style="position: relative;left:10px;bottom:2px;" /></p>
                                <div class="sort-box-outer"><div class="sort-box-inner"></div></div>
                                <input name="sort_id" type="hidden" value="{$list['sort_id']}" id="sort_id" />
                            </div>
                        </li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">文件路径: </span>
								{code}
								$folder[0][-1] = "请选择";
								{/code}
								{template:form/select,fodder,-1,$folder[0],$css_attr}
							</div>
						</li>
						
                        <!--<li class="i">
                            <div class="form_ul_div clear">
                                <span class="title">专题模板: </span>
                                <input type="radio" name="is_special_tem" value="1" {if $app_uniqueid=='special'}checked="checked"{/if}/>是
                                <input type="radio" name="is_special_tem" value="0" {if !($app_uniqueid=='special')}checked="checked"{/if}/>否
                            </div>
                        </li>	-->
						{if $debug_mode[0]}
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">所属模块: </span>
								{code}
									$attr_pro = array(
										'class' => 'transcoding down_list',
										'show'  => 'select_app',
										'width' => 120,/*列表宽度*/
										'state' => 0,/*0--正常数据选择列表，1--日期选择*/
									);
									$list['app_uniqueid'] =  $list['app_uniqueid']	? $list['app_uniqueid'] : 'publishsys';
								{/code}
								{template:form/search_source,app_uniqueid,$list['app_uniqueid'],$apps[0],$attr_pro}
							</div>
						</li>
						{/if}
						{if $list['app_uniqueid'] == 'special'}
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">标签: </span>
								{code}
									$attr_tag = array(
										'class' => 'transcoding down_list',
										'show'  => 'select_tag',
										'width' => 120,/*列表宽度*/
										'state' => 0,/*0--正常数据选择列表，1--日期选择*/
									);
									$tags[0]['-1'] = '全部标签';
									$list['tag'] =  $list['tag'] ? $list['tag'] : '-1';
								{/code}
								{template:form/search_source,tag,$list['tag'],$tags[0],$attr_tag}
							</div>
						</li>
						{/if}
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">示意图</span>
								<div id="log_box" style="float:left;margin-top:10px;">
								{if is_array($list['pic']) && count($list['pic']) > 0}
									{foreach $list['pic'] as $k => $v}
										{code}
											$img='';
											if($v)
												$img = $v['host'] . $v['dir'] . $v['filepath'] . $v['filename'];
										{/code}	
										{if $img}
											<div id="mateiral_{$v['id']}" class="material_log">
												<img src="{$img}" alt="" width="100" height="75" />
												<span class="material_del">X</span>
												<input type="hidden" name="log[]" value="{$pic_json[$k]}"/>
											</div>
										{/if}
									{/foreach}
								{/if}
								</div>
								<div id="circle_upload" style="float: left;"></div>
							</div>
						</li>
					</ul>
					</div>

                        <div class="code-edit {if !$_INPUT['id']}no-template{/if}" style="background: #eee;">
                          <div class="template-edit-area">{$list['html']}</div>
                          {if !$_INPUT['id']}<div class="template-tip-area">模板富文本编辑区</div>{/if}
                        </div>

					</div>
				<div class="record">
					<p >查看使用记录</p>
					<ul class="record-info clear">
						<li>纪录1</li>
						<li>纪录1</li>
						<li>纪录1</li>
						<li>纪录1</li>
					</ul>
				</div>
				{if $_INPUT['id']}<input type="hidden" name="a" value="edit_c" />{else}<input type="hidden" id="a_action" name="a" value="{$a}" />{/if}
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="./run.php?a=frame&mid={$_INPUT['mid']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<input type="hidden" name="site_id" value="{$_INPUT['site_id']}" />
				<input type="hidden" name="html" value="true" />
				<!--<input type="hidden" name="flag" value="flag" onclick="isvalidatefile('file_data');"/>
				-->
			 </div>
			</div>
			<div class="cover"></div>
			</form>
	</div>
	<img src="{$RESOURCE_URL}loading2.gif" id="top-loading" />
	<script type="text/javascript">
$(document).ready(function(){
	add_close_hover = function (els) {
		els.each(function () {
			$(this).hover(
				function(){
					$(this).find("span").fadeIn(100);
				},
				function(){
					$(this).find("span").fadeOut(100);
				}
			);
		});
	}
	add_close_hover($(".material_log"));	
	$('body').on('click', ".material_del", function(){
		$(this).parent().remove();
	});
});

$(function ($) {
	$("#Filedata1").on('change', function (e) {
		var file = this.files[0];
		var type = file.type;
		var is_img = /image/.test(type);
		
		if ( $('#a_action').val() == 'create' ) {
			if ( !/.+\.(html|htm|zip)$/.exec(file.name) ) {
				alert('文件格式不对');
				this.value = '';
				return;
			}else{
				$('.file-long').val(file.name);
				$('.long').val(file.name);
			}
		} else {
			if ( !/.+\.(html|htm|css|js|swf)$/.exec(file.name) && !is_img ) {
				alert('文件格式不对');
				this.value = '';
			} else {
				if ( /.+\.(html|htm|)$/.exec(file.name) ) {
					$('#a_action').val('update_tem');
					$('.long').val(file.name);
				} else {
					$('#a_action').val('update');
				}
				$('.file-long').val(file.name);
			}
		}
		
	});

	$('.select-file-button').on('click',function(){
		$("#Filedata1").trigger('click');
	});

	$('#form-template').submit(function(){
		if($(this).hasClass('form-compare')){
			$(this).ajaxSubmit({
				dataType: 'html',
				beforeSubmit :function(){
					$('#top-loading').show();
				},
				success :function(data){
					var cover_area = $('.cover');
					cover_area.html(data).addClass('cover-show');
					$('#top-loading').hide();
					
					setTimeout( function(){
						top.$('body').scrollTop( 0 );
						var first_diff = cover_area.find('.span_diff:first'),
							first_notsame = cover_area.find('.notsame:first'),
							diff_top = 0,
							notsame_top = 0,
							animate_top = 0;
						first_diff.length && ( diff_top = first_diff.offset().top -150 );
						first_notsame.length && ( notsame_top = first_notsame.offset().top -150);
						diff_top >0 || ( diff_top = 0 );
						notsame_top >0 || ( notsame_top = 0 );
						if( +diff_top && +notsame_top ){
							animate_top = ( diff_top >= notsame_top ) ? notsame_top : diff_top;
						}else{
							animate_top = ( diff_top >= notsame_top ) ? diff_top : notsame_top;
						}
						cover_area.find('.compare').animate({  scrollTop :animate_top },300);
					},  600 );
				}
			});
			return false;
		}
		var sort_id = $('#sort_id').val();
		if(!sort_id)
		{
			alert('请选择模板分类');
			return false;;
		}
	});

	$('.cover').on('click','#compare-cancel',function(){
		$(this).closest('.cover').removeClass('cover-show');
	});

	$('.cover').on('click','#compare-ok',function(){
		$('input[name="a"]').val('update');
		$('input[name="upcell"]').appendTo('#form-template');
		$('input[name="updata"]').appendTo('#form-template');
		$('#form-template').removeClass('form-compare').submit();
	});

    $('.switch_global_template').on(
        'click', function() {
            var isGlobalTemplate = $(this).val();
            if (isGlobalTemplate == 1)
            {
                $('.global_template_1').show();
                $('.global_template_0').hide();
            }
            else if (isGlobalTemplate == 0)
            {
                $('.global_template_1').hide();
                $('.global_template_0').show();
            }
        }

    );

    $('.switch_create_type').click(function(){
    	var code_edit_box = $('.code-edit');
        var create_type = $(this).val();
        if (create_type == 1)   //直接创建
        {
            $('.file_upload_li').hide();
            code_edit_box.removeClass('no-template').find('.template-edit-area').removeClass('hide');
            $('input[name=title]').attr('placeholder','模版名称');
        }
        else if (create_type == 0)   //文件上传
        {
            $('.file_upload_li').show();
            //code_edit_box.removeClass('no-template').find('.template-edit-area').removeClass('hide');
            $('input[name=title]').attr('placeholder','重命名模版名称');
        }
    })
});
</script>
{template:foot}