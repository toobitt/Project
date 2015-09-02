{template:./head}
{css:load}
{js:qingao/base}
{js:swfupload/swfupload}
{js:swfupload/swfupload.queue}
{js:swfupload/fileprogress}
{js:swfupload/handlers}
{js:qingao/addgroup}
<style type="text/css">
#add_thread_title {width:370px; margin-right:10px; float:left;}
.cke_skin_kama:focus{outline:none;}
.cke_browser_webkit:focus{outline:none;}
textarea:focus{none;}
.cke_show_borders{color:#bab9b9}
</style>
	</section><!--展示区完-->
	<section class="wrap clearfix">
		<article class="gmain">
			<div class="gmain_top"></div>
			<div class="cmain add_theads">				
					<div class="group_title"><h1 class="gt1">{if $action_id > 0}行动{else}圈子{/if}新帖子</h1></div>
					<div class="add_thead" style="margin-top:15px;">						
						<form action="thread.php" method="post" enctype="multipart/form-data" id="addthread">
							<input type="text" value="在这里填写标题" class="add_thread_title" id="add_thread_title" name="thread_title" />
							<label style="float: right; width:200px;"><span>所属类型:</span><select name="thread_type">
							{foreach $thread_type as $v}
							<option value="{$v['t_typeid']}">{$v['type_name']}</option>
							{/foreach}
							</select></label>						
							<div style="clear: both;">{$editor}</div>
							<div class="add_attach">
								<ul class="tabs_nav"><li class="active">附件</li><!--<li>相册：</li><li>视频</li>--></ul>
								<div class="tabs_panel">
									<div class="add_thread_file">
										<div class="fieldset flash" id="fsUploadProgress">
											<span class="legend">快速上传</span>
									  	</div>
										<span id="divStatus">0 个文件已上传</span>
										<div>
											<span id="spanButtonPlaceHolder"></span>
											<input id="btnCancel" type="button" value="取消所有上传" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 24px;" />
										</div>
										<span>最大2M,最多可以选中20文件同时上传</span>
									</div>
									<!--
									<div class="add_thread_file"><input type="file" name="photo_file" value="浏览" ><span>最大2M,最多可以选中20文件同时上传</span></div>
									<div class="add_thread_file"><input type="file" name="video_file" value="浏览" ><span>最大2M,最多可以选中20文件同时上传</span></div>
									-->
								</div>
							</div>
							<input type="hidden" name="group_id" value="{$group_id}" />
							<input type="hidden" name="action_id" value="{$action_id}" />
							<input type="hidden" name="a" value="add" />
							<div id="img_info" style="display:none;"></div>
							<div class="add_thread_btn"><input type="submit" name="add_group_btn" value="发布帖子" /></div>
						</form>
					</div>								
			</div><!--end for cmain-->
			<div class="gmain_bottom"></div>
		</article>
		<aside class="gaside hid">
			<div class="gaside_top"></div>
			<div class="gaside_m">
				{template:./join}
			</div>
			<div class="gaside_bottom"></div>
		</aside>
	</section>
	<script type="text/javascript">
	//<![CDATA[
		$(document).ready(function(){
			$(".add_attach").tabs({"tabPanel": ".add_thread_file"});
		});
	//]]
	</script>
{template:./footer}