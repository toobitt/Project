{template:head}
{css:2013/iframe}
{css:common/common_category}
{css:video/video}
{css:vod_split}
{js:jqueryfn/jquery.tmpl.min}
{js:video/jquery.video.new}
{js:video/video_file}
{js:video/video_canvas}
{js:video/point/point.video}
{js:video/point/point_dian}
{js:video/point/point}

{code}
$list = $vod_show_list[0];
$curVideo = json_encode($list['cur_video']);
{/code}
<style>
#video-box:hover .point-set{display:block!important;cursor:pointer;}
</style>
<script>
var today = '{$list["date_time"]}';
var curVideo = {$curVideo};
</script>
<style>
.ui-widget-header{background:none;}
.ui-slider-handle-first{display:none;}
</style>
<div class="wrap main-box" style="min-height:auto;">
    <div id="file-box" class="split-main clearfix">
        <div class="tip-title">
            <a class="icon-a"></a>
            <h3>点击下面的视频进行打点</h3>
        </div>
       <div class="file-cat common_category">
            <div class="file-cat-inner menu-inner"></div>
       </div>

       <div class="split-right">
           <div class="top" style="margin-left:23px;">
               <div class="common-search-area fr" style="float:left;">
                    <div class="select-area"></div>
                    <div class="search">
                         <input type="text" class="key-word"/>
                         <span class="btn"></span>
                    </div>
                </div>

                <div class="common-page-link fr"></div>
           </div>
           <ul class="file-list common-vod-area clearfix"></ul>
       </div>
    </div>

    <div class="vod-split clearfix">
       <div class="tip-title">
            <h3>当前打点：<span id="title-box"></span></h3>
            <span class="right">此视频已有打点：<em class="number"></em></span>
            <a class="icon-a"></a>
       </div>
       <div class="content">
           <div class="current-vod">
                <div id="video-box" class="video" style="padding:0;">
                <div class="point-set" style="width: 60px;display:none;height: 60px;background:#68b5fb;color:#fff;border-radius:50%;line-height: 60px;text-align:center;position: absolute;bottom: 50px;z-index: 10000;left: 50%;margin-left: -30px;">打点</div>
                <video id="video"></video>
                <div class="video-tips"> 
                	
                	 
                </div>
                </div>


           </div>
           <div class="vod-list">
           
              <div id="video-pian">
                    <div class="video-info video-pian-info">
                       <a class="camera-btn"></a>
                        <div class="detail">
                            <input type="text"  class="vod-title" placeholder="输入标题"  style="width:300px;margin-left:5px;margin-top: 28px;"/>
                            <span class="upload-pic two-point" style="margin-top:0;">
                                
                                <span class="start"></span>
                            </span>
                            <span class="duration"></span>
                            <span class="yulan"></span>
                            <span class="common-button-group" style="float: none;">
                                 <a class="save blue">保存</a>
                                 <a class="cancel gray">取消</a>
                            </span>
                        </div>
                    </div>
                    <div class="video-pian-tip"></div>
                </div>
           
                <ul  class="point-list">
  
				</ul>
           </div>
       </div>
  </div>
</div>


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
             <span class="time">{{= $value.duration}}</span>
        </a>
        <a class="name">{{= $value.title}}</a>
        
    </li>
    {{/each}}
{{else}}
    <li class="list-wu">无</li>
{{/if}}
</script>
<script type="text/x-jquery-tmpl" id="file-list-more-tpl">
    <li class="file-list-more">更多</li>
</script>

<!--split模板-->
<script type="text/x-jquery-tmpl" id="point-tpl">

    <li _id="${id}" _time="${time}">
         <a class="name" href="#">${point}</a>
		<input class="text" type="text"  value="${brief}"/>
         <span class="edit update">修改</span>
		  <span class="edit delete">删除</span>
    </li>	
</script>
<!--diancount模板-->
<script type="text/x-jquery-tmpl" id="point-count">
       <a class="count" style="color:#fff">${total}</a>
</script>

<!--showdian模板-->
<script type="text/x-jquery-tmpl" id="point-show">
    <div class="video-point" _id="${id}"  style="left:${precent}">  
	  <a class="point-precent"></a>
	  <div class="video-title">
                		<div class="video-content">时间戳：${point}<br/>${brief}</div>
                		<em></em>
                	</div>
   </div>
</script>
{template:foot}