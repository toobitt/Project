{template:head}
{css:2013/iframe}
{css:common/common_category}
{css:video/video}
{css:vod_fast}
{css:hg_sort_box}
{js:hg_sort_box}
{js:video/jquery.video.new}
{js:video/video_file}
{js:video/video_yulan}
{js:video/video_canvas}
{js:video/fast/fast_dui}
{js:video/fast/fast}
{js:video/publish_video}
{js:video/sort_video}
{code}
//hg_pre($vod_fast_editor);
$formdata = $vod_fast_editor;
$vod_fast_editor = $vod_fast_editor[0];
$mainId = $vod_fast_editor['main_id'];
$today = $vod_fast_editor['date_time'];
$videos = $vod_fast_editor['videos'];
!$videos && ($videos = array());
$videos = json_encode($videos);
$currentSort[$sort_id] = ($sort_id ? $sort_name : '选择分类');
{/code}
<script>
var today = '{$today}';
var mainId = parseInt({$mainId});
var videos = {$videos};
</script>

<div class="wrap main-box" style="min-height:auto;">

<div id="fast-dui" class="fast-editor clearfix">
      <div class="fast-editor-head">
           <span class="time">全部时长:<em class="total-duration"></em></span>
           <span class="common-button-group">
                 <a class="view blue">预览</a>
                 <a class="save-fugai blue" style="display:none;">保存</a>
                 <a class="save blue">另存为</a>
                 <a class="reset gray">清空</a>
                 <input type="hidden" name="a" value="create" />
           </span>
      </div>
      <div class="fast-editor-nav">
            <div class="drop-tip">从下面的视频中拖动选择</div>
           <ul id="drop-box"></ul>
           <div class="recycle-area"></div>
      </div>
      <div class="save-pop video-box">
	      	<div class="pop-item"><label>标题：</label><input type="text" name="title" placeholder="视频标题" value="" /></div>
	      	<div class="pop-item pop-texterea"><label>描述：</label><textarea placeholder="视频描述" name="comment" cols="100" rows="3"></textarea></div>
      		<div class="form-dioption-fabu pop-item">
                <a class="publish-button overflow" href="javascript:;" _default="发布至" _type="publish" _prev="发布至">发布至：<em>暂未设置</em></a>
        	</div>
      		<div class="form-dioption-sort pop-item"  id="sort-box">
                <label>分类：</label>
                <p style="display:inline-block;" class="sort-label" _multi="get_vod_node"> {$currentSort["$sort_id"]}<img class="common-head-drop" src="{$RESOURCE_URL}tuji/drop.png" style="position: relative;left:10px;bottom:2px;" /></p>
				<div class="sort-box-outer"><div class="sort-box-inner"></div></div>
                <input name="vod_sort_id" type="hidden" value="{$sort_id}" id="sort_id" />
            </div>
            <div class="pop-item">
            	<label>&nbsp; </label>
            	<span class="common-button-group">
            		<a class="sure blue">确定</a>
            		<a class="cancel gray">取消</a>
            	</span>
            </div>
      </div>
      {template:unit/publish_for_form, 1}
    <div id="file-box" class="fast-editor-main clearfix">
       <div class="file-cat common_category">
            <div class="file-cat-inner menu-inner"></div>
       </div>
       <div class="fast-editor-right">
           <div class="top">
               <div class="common-search-area fl">
                    <div class="select-area"></div>
                    <div class="search">
                         <input type="text" class="key-who" placeholder="指定人"/>
                         <input type="text" class="key-word" placeholder="标题关键字"/>
                         <span class="btn"></span>
                    </div>
                </div>
                <div class="common-page-link fr"></div>
           </div>
           <ul class="file-list common-vod-area clear"></ul>
       </div>
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
        <div class="yulan-tab fast-editor-nav"><ul class="yulan-tab-inner clearfix"></ul></div>
    </div>
</div>
<div id="video-box"><video id="video"></video></div>



<!-- 全局数据 -->
<script type="text/javascript">
	$.globalsite =  {code} echo $sitesdata['site'] ? json_encode( $sitesdata['site'] ) : '[]';{/code};
	$.globaldefault =  {code} echo $sitesdata['default_site'] ? json_encode( $sitesdata['default_site'] ) : '[]';{/code};
</script>

<!--file模板-->
<script type="text/x-jquery-tmpl" id="file-cat-tpl">
    <ul class="file-cat-item">

        <li _fid="${fid}" class="{{if fid==0}}file-cat-li{{else}}file-cat-title{{/if}}"><a class="title">${title}<a/></li>
        {{each list}}
        <li _fid="{{= $value.fid}}" _name="{{= $value.name}}" class="file-cat-li">
            <a class="title">{{= $value.name}}</a>
            {{if $value.child}}
            <a class="file-cat-child arrow"></a>
            {{/if}}
        </li>
        {{/each}}
    </ul>
</script>
<script type="text/x-jquery-tmpl" id="file-cat-place-tpl">
    <ul class="file-cat-item">
        <li _fid="${fid}" class="file-cat-title"><a class="title">${title}</a></li>
        <li class="cat-loading"><a class="title">{{html img}}</a></li>
    </ul>
</script>
<script type="text/x-jquery-tmpl" id="file-list-li-tpl">
{{if list}}
    {{each list}}
    <li _id="{{= $value.id}}" class="file-list-li">

        <a class="pic">
             <img src="{{= $value.src}}" />
             {{if $value.starttime}}
             <span class="date">{{= $value.starttime}}</span>
             {{/if}}
             <span class="time">{{= $value.duration}}</span>
        </a>
        <a class="name">{{= $value.title}}</a>
    </li>
    {{/each}}
{{else}}
    <li class="list-wu">无</li>
{{/if}}
</script>


<!--dui模板-->
<script type="text/x-jquery-tmpl" id="dui-tpl">
<li class="one-point drop-item" _hash="${hash}">
    <span class="start"><img src="${img}" /></span>
    <span class="end"><img src="${img_end}" /></span>
    <span class="time">${duration}</span>
</li>
</script>



{template:foot}