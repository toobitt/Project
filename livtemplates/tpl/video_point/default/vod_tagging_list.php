{template:head}
{js:common/number}
{js:video/jquery.video}
{js:video/video_chaitiao}
{css:video_chaitiao}
{code}
$video_infos = array();
$video_info = array(
    'id' => $formdata['id'],
    'src' => './vod/'.$formdata['video_path'].$formdata['video_filename'],
    'fen' => array($formdata['width'], $formdata['height']),
    'zhen' => round($formdata['frame_rate']),
    'time' => $formdata['duration'],
    'img' => $formdata['source_img'],
    'title' => $formdata['title']
);
$video_infos[$formdata['id']] = $video_info;

if($formdata['vcr_data']){
    foreach($formdata['vcr_data'] as $kk => $vv){
        if(!array_key_exists($vv['vodinfo_id'], $video_infos)){
            $video_infos[$vv['vodinfo_id']] = array(
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
$video_infos = json_encode($video_infos);
$vcr_data = json_encode($formdata['vcr_data']);
{/code}
<script>
$('.heard_menu').hide();
var videoInfos = $.parseJSON('{$video_infos}');
var vcrData = $.parseJSON('{$vcr_data}');
</script>
<div id="video-main">
    <div class="option-iframe-back-box">
        <a class="option-iframe-back" href="javascript:;">返回视频库</a>
    </div>
    <div class="video clearfix">
        <div class="v-left">
            <div id="video-box"></div>
            <div id="video-mask"></div>
            <div id="video-bujin"></div>
            <div id="video-dian">
                <div id="video-btn"></div>
            </div>
        </div>
        <div class="v-right">
            <div class="v-r-top">
                <span class="v-save"></span>
                <span class="v-title" style="font-size:14px;">正在对视频<span style="font-size:22px;margin:0 10px;">{code} echo mb_substr($video_info['title'], 0, 20, 'utf-8');{/code}</span>进行拆条</span>
            </div>
            <ul id="video-slice">
                <li id="vs-add" class="v-ext"><span>添加片段</span><div class="v-ext-bg"></div></li>
            </ul>
        </div>
        <div id="zhou-type">
            <ul>
                <li _type=".5">半秒</li>
                <li _type="1">1秒</li>
                <li _type="5">5秒</li>
                <li _type="10">10秒</li>
                <li _type="30">30秒</li>
                <li _type="60">60秒</li>
            </ul>
        </div>
    </div>
    <div class="zhou-box">
        <div id="zhou" class="z-box">

        </div>
    </div>
</div>

<textarea id="line-tpl" style="display:none;">
<div class="z-part">
    <div class="z-img-box"><div class="z-img"></div></div>
    <div class="z-border"></div>
    <div class="z-slider"></div>
</div>
</textarea>
<textarea id="slice-tpl" style="display:none;">
<li>
    <div class="s-left s-img s-each"><div class="s-t-l"></div><div class="s-top"></div><div class="s-bottom-outer"><div class="s-bottom"></div></div></div>
    <div class="s-right s-img s-each"><div class="s-t-r"></div><div class="s-top"></div><div class="s-bottom-outer"><div class="s-bottom"></div></div></div>

    <div class="s-duration s-each"></div>
    <div class="s-kuai s-each"><span class="s-kuai-btn"></span><span class="s-kuai-outer"><span class="s-kuai-inner"></span></span></div>
    <div class="s-del s-each"></div>
    <div class="s-sort s-each"></div>

    <div class="s-title" contenteditable="true" _default="输入标题...">输入标题...</div>
</li>
</textarea>

{template:foot}