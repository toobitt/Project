{template:head}
{css:video_kuaibian}
{css:jquery.jscrollpane}
{js:common/number}
{js:video/jquery.jscrollpane}
{js:video/jquery.mousewheel}
{js:video/video_canvas}
{js:video/video_kuaibian_video}
{js:video/video_kuaibian_pians}
{js:video/video_kuaibian_tpian}
{js:video/video_kuaibian_list}
{js:video/video_kuaibian_yulan}
{js:video/video_kuaibian_select}
{js:video/video_kuaibian}
{code}
$video_infos = array();
$src = './vod/'.$formdata['video_path'].$formdata['video_filename'];
$video_info = array(
    'id' => $formdata['id'],
    'src' => $src,
    'fen' => array($formdata['width'], $formdata['height']),
    'zhen' => round($formdata['frame_rate']),
    'time' => $formdata['duration'],
    'img' => $formdata['source_img'],
    'title' => $formdata['title']
);
$video_ids = $formdata['id'];
$video_infos[] = $video_info;
//print_r($formdata);
if($formdata['added_videos']){
    foreach($formdata['added_videos'] as $kk => $vv){
        if(!in_array($vv['vodinfo_id'], $video_ids)){
            $video_infos[] = array(
                'id' => $vv['vodinfo_id'],
                'src' => './vod/'.$vv['video_path'].$vv['video_filename'],
                'fen' => array($vv['width'], $vv['height']),
                'zhen' => round($vv['frame_rate']),
                'time' => $vv['duration'],
                'img' => $vv['source_img'],
                'title' => $vv['title']
            );
        }
    }
}
//print_r($video_infos);
$video_infos = json_encode($video_infos);

$vcr_data = array();
foreach($formdata['vcr_data'] as $kk => $vv){
    $vcr_data[] = array_merge($vv, array(
        'id' => $vv['vodinfo_id'],
        'src' => './vod/'.$vv['video_path'].$vv['video_filename'],
        'fen' => array($vv['width'], $vv['height']),
        'zhen' => round($vv['frame_rate']),
        'time' => $vv['duration'],
        'img' => $vv['source_img'],
        'title' => $vv['title'],

        'hash' => $vv['hash_id'],
        'start' => $vv['input_point'],
        'end' => $vv['output_point'],
        'order_id' => $vv['order_id'],
        'vcr_type' => $vv['vcr_type'],
        'startImg' => $vv['start_imgdata'],
        'endImg' => $vv['end_imgdata']
    ));
}
//print_r($vcr_data);
$vcr_data = json_encode($vcr_data);

{/code}
<script>
$('.heard_menu').hide();
var mainVideoId = {$formdata['id']};
//var videoInfos = $.parseJSON('{$video_infos}');
//var vcrData = $.parseJSON('{$vcr_data}');
var videoInfos = {$video_infos};
var vcrData = {$vcr_data};
</script>
<div class="option-iframe-back-box">
    <a class="option-iframe-back" href="javascript:;">返回视频库</a>
</div>

<div id="video-box"><video id="video" width="500" height="375"></video></div>
<div class="video-slice-info">正在快编&nbsp;&nbsp;时长：<span></span></div>
<div id="slice-slider-help">
    <span class="ssh-left">向左</span>
    <span class="ssh-right">向右</span>
    <span class="ssh-time"></span>
</div>
<div id="video-slice-outer">
    <div id="video-slice"></div>
</div>
<div class="clearfix">
    <span class="yulan btn">预览</span><span class="baocun btn">保存</span>
</div>
<div id="video-list">
    <div id="video-sbox">
        <div class="vl-title">已选视频</div>
        <div id="video-select" class="clearfix">
            <div id="video-add">添加视频</div>
        </div>
    </div>
    <div id="video-search" class="vb-search">
        <div class="vb-top">
            <span>添加视频</span>
            <span class="vb-close">关闭</span>
        </div>
        <div class="vb-bottom">
            <div id="video-leixing">
                <div class="leixing-inner"></div>
            </div>
            <div class="vb-content">
                <div id="video-condition">
                    <select class="vb-date">
                        {foreach $formdata['date_search'] as $k => $v}
                            <option value="{$k}">{$v}</option>
                        {/foreach}
                    </select>

                    <span class="vb-title-box"><input type="text" class="vb-title" placeholder="标题搜索"/></span>
                </div>
                <div id="video-ajax" class="vlist clearfix"></div>
            </div>
        </div>
    </div>
</div>

<div id="pian-option">
    <div class="pian-option-items"><span class="pian-option-edit">编辑</span><span class="pian-step"></span><span class="pian-option-del">删除</span></div>
</div>
<div id="pian-other-option">
    <div class="poo-jian"></div>
    <span class="poo-tou poo-type" type="tou">插入片头</span>
    <span class="poo-hua poo-type" type="hua">插入片花</span>
    <span class="poo-wei poo-type" type="wei">插入片尾</span>
    <div class="poo-option"><span class="poo-change">重选</span><span class="pian-step"></span><span class="poo-del">删除</span></div>
</div>

<div id="select-box" class="ts-box">
    <div class="ts-jian"></div>
    <img class="ts-loading" src=""/>
    <div class="ts-inner">
        <div class="ts-content"></div>
    </div>
</div>

<div class="yulan-box" style="display:none;">
    <div class="yulan-close">关闭</div>
    <div class="yulan-video">
        <video width="500" height="375" autobuffer></video>
        <div class="yulan-control">
            <div class="yulan-slider"></div>
            <div class="yulan-play">播放</div>
            <div class="yulan-kj">
                <span class="yulan-kj-prev"></span>
                <span class="yulan-kj-next"></span>
            </div>
            <div class="yulan-time-box">
                <span class="yulan-time"></span>/<span class="yulan-total-time"></span>
            </div>
        </div>
    </div>
    <div class="yulan-tab"><div class="yulan-tab-inner clearfix"></div></div>
</div>

<textarea id="select-tpl" style="display:none;">
<div class="ts-each" _id="{{id}}">
    <div class="ts-each-img"><img src="{{img}}"/></div>
    <div class="ts-each-title">{{title}}</div>
</div>
</textarea>

<textarea id="pian-tou-tpl" style="display:none;">
<div class="pian pian-other pian-tou" type="tou"></div>
</textarea>

<textarea id="pian-hua-tpl" style="display:none;">
<div class="pian pian-other pian-hua" type="hua"></div>
</textarea>

<textarea id="pian-wei-tpl" style="display:none;">
<div class="pian pian-other pian-wei" type="wei"></div>
</textarea>

<textarea id="pian-place-tpl" style="display:none;">
<div class="pian pian-place">新增片段</div>
</textarea>

<textarea id="pian-tpl" style="display:none;">
<div class="pian pian-duan">
    <div class="pian-img-mask"></div>
    <div class="pian-img-start"><img src=""/></div>
    <div class="pian-img-end"><img src=""/></div>
    <div class="pian-time-duration"></div>
</div>
</textarea>

<textarea id="each-tpl" style="display:none;">
<div class="vb-each" _id="{{id}}">
    <div class="vb-each-close">x</div>
    <div class="vb-each-item"><img class="vb-each-img" src="{{img}}"/></div>
    <div class="vb-each-title">{{title}}</div>
</div>
</textarea>

<textarea id="ajax-tpl" style="display:none;">
<div _id="{{id}}" class="vb-item">
    <div class="vb-item-img"><img src="{{img}}"/></div>
    <div class="vb-item-title">{{title}}</div>
</div>
</textarea>

{template:foot}