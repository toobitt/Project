{template:head}
{code}
//print_r($list);
//hg_pre($vod_config);
$image_resource = RESOURCE_URL;
$vodPlayerSwf = RESOURCE_URL.'swf/';

if(!isset($_INPUT['trans_status']))
{
    $_INPUT['trans_status'] = -2;
}

if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}
if(isset($_INPUT['id']))
{
   $id = $_INPUT['id'];
}
else
{
   $id = '';
}
$hg_vod_list_mode = hg_get_cookie('hg_vod_list_mode');
if ($hg_vod_list_mode)
{
	$mode_show_text = '切换至列表';
	$vod_mode_class = 'list_img';
}
else
{
	$mode_show_text = '切换至列表';
	$vod_mode_class = 'list';
}
{/code}
{css:vod_style}
{css:edit_video_list}
{css:mark_style}
{js:vod_opration}
{js:vod_upload_pic_handler}
{js:vod_video_edit}
{js:vod_add_to_collect}
{js:technical_review}
{js:column_node}
{css:column_node}
{js:tree/animate}
{js:xml/vod_list}
{code}
$status_key = 'status_display';
$audit_value = 2;
$back_value = 3;
$back_label = '被打回';
$attrs_for_edit = array(
	'frame_rate', 
	'bitrate',
	'download',
	'retranscode_url',
	'vod_leixing', 
	'aspect',
	'format_duration',
	'video_duration',
	'video_totalsize',
	'video_resolution',
	'aspect',
	'audio',
	'sampling_rate',
	'video_audio_channels',
	'video',
	'isfile_name',
	'is_allow',
	'pub_url',
	'is_do_morebit',
	'is_morebitrate_ok',
	'is_forcecode_ok',
	'is_forcecode',
	'app_uniqueid',
	'object_id',
	'video_m3u8',
	'export_dir',
	'need_file'
);
{/code}
{template:list/common_list}
<style type="text/css">
.vod-quanzhong{ width:60px; }
#vedio-player{position:absolute;right:380px;top:-120%;z-index:1;transition:top .3s;-webkit-transition:top .3s;width:346px;height:264px;background:#000;}
.show-transcode-box{cursor:pointer;}
.transcode-box{display:none;position:absolute;z-index:10;width:200px;padding:10px 20px;background:#4c4c4c;top:0;}
.transcode-box .transcode-info{color:#eee;padding:10px 0;}
.transcode-box p{line-height:2;}
.transcode-box .title{color:#eee;width:60px;}
.transcode-box .handler-btns{border-top:1px solid #555;padding:10px 0;}
.transcode-box .handler-btn{display:inline-block;background: #414141;height: 28px;line-height: 28px;color: #fff;padding:0 15px;}
.transcode-box .handler-btn:hover{background-color: #393738;}
.transcode-box .close-btn{position:absolute;width: 22px;height: 28px;top: 0;right: -23px;border-left: 1px solid #3e3e3e;box-shadow: 0 0 3px 0 rgba(0, 0, 0, 0.6);cursor: pointer;background: url("{$RESOURCE_URL}common/icon_close.png") no-repeat center center #4c4c4c;}
.force_recodec{width: 50px;height: 16px;background: #5C99CF;display: block;line-height: 18px;color: #fff;border-radius: 2px;padding: 2px;position: absolute;left: 160px;bottom: 25px;}
</style>
{template:list/ajax_pub}
<div class="common-list-content" style="min-height:auto;min-width:auto;">
			{if !$list}
				<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
				<script>hg_error_html('p',1);</script>
			{else}
                {css:vod_list}
                {code}
                $columnData = array(
					array(
						'class' => 'vod-fabu',
						'innerHtml' => '发布至'
					),
					
					array(
						'class' => 'vod-maliu',
						'innerHtml' => '码流'
					),
					array(
						'class' => 'vod-fenlei',
						'innerHtml' => '分类'
					),
					array(
						'class' => 'vod-quanzhong',
						'innerHtml' => '权重'
					),
					array(
						'class' => 'vod-zhuangtai',
						'innerHtml' => '状态'
					),
					array(
						'class' => 'vod-ren',
						'innerHtml' => '添加人/时间'
					)
				);
                {/code}
               <!-- {template:list/list_column} --> 
                <form method="post" action="" name="listform" style="display:block;position:relative;">
                    <ul class="vod-list common-list">
                        <li class="common-list-head public-list-head clear">
                            <div class="common-list-left">
                                <div class="common-list-item paixu">
                                   <a class="common-list-paixu" onclick="hg_switch_order('vodlist');"  title="排序模式切换/ALT+R"></a>
                                </div>
                                <div class="common-list-item wd60">缩略图</div>
                            </div>
                            <div class="common-list-right">
                                <div class="vod-fabu common-list-item open-close common-list-pub-overflow" which="vod-fabu">发布至</div>
                                <div class="vod-maliu common-list-item open-close wd70" which="vod-maliu">码流</div>
                                <div class="vod-fenlei common-list-item open-close wd80" which="vod-fenlei">分类</div>
                                <div class="vod-quanzhong common-list-item open-close wd60" which="vod-quanzhong">权重</div>
                                <div class="vod-zhuangtai common-list-item open-close wd60" which="vod-zhuangtai">导出</div>
                                <div class="vod-ren common-list-item open-close wd100" which="vod-ren">添加人/时间</div>
                            </div>
                            <div class="common-list-biaoti">
						        <div class="common-list-item">标题</div>
					        </div>
                        </li>
                    </ul>
                    <ul class="vod-list common-list public-list hg_sortable_list" data-order_name="video_order_id" data-table_name="vodinfo" id="vodlist">
					{foreach $list as $k => $v}
						{template:unit/vod_list}
					{/foreach}             
                    </ul>
                    <ul class="common-list public-list">
						<li class="common-list-bottom clear">
							<div class="common-list-left">
								<input type="checkbox"  name="checkall" value="infolist" title="全选" rowtag="LI" />
								<a class="export-file" style="cursor:pointer;" _type="1">导出</a> 
                       		</div>
                       		{$pagelink}
                    	</li>
                    </ul>
                    <div class="edit_show">
						<span class="edit_m" id="arrow_show" style="position:absolute;"></span>
						<div id="edit_show"></div>
					</div>
    			</form>
				
			{/if}
	{template:unit/export_xml}
	</div>
    <div id="infotip"  class="ordertip"></div>
    <div id="getimgtip"  class="ordertip"></div>
    <div id="add_share" style="box-shadow:0 0 3px #555;padding:0 12px 12px 12px;background:#f0f0f0;display:none;position:fixed;top:50px;left:150px;z-index:100000;border:1px solid #f5f5f5;border-radius:5px;width:500px;min-height:300px;overflow:auto;"></div> 
	{template:unit/record_edit}

{code}
foreach (array('start_time', 'end_time', 'date_search', 'start_weight', 'end_weight', 'k', 'trans_status','user_name','pub_column_id') as $v) 
{
	$conditions[$v] = $_INPUT[$v];
}
$conditions['vod_leixing'] = $_INPUT['_type'];
$conditions['vod_sort_id'] = $_INPUT['_id'];
{/code}
{template:foot}