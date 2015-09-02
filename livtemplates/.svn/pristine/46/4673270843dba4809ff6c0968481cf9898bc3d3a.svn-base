{template:head}
{css:2013/iframe}
{css:common/common_category}
{css:2013/m2o}
{css:video/video}
{css:vod_split}
{css:hg_sort_box}
{js:2013/ajaxload_new}
{js:hg_sort_box}
{js:2013/keywords}
{js:video/jquery.video.new}
{js:video/video_file_new}
{js:video/video_canvas}
{js:video/split/split_pian}
{js:video/split/split_tiao}
{js:video/split/split}
{js:video/publish_video}
{js:video/sort_video}
{js:live/my-ohms}
{js:video/split/split_live}
{code}
$formdata = $vod_show_list;
$list = $vod_show_list[0];
$curVideo = json_encode($list['cur_video']);
$currentSort[$sort_id] = ($sort_id ? $sort_name : '选择分类');
$liveSet = $live_configs[live_time_shift_open] ;
{/code}
<script>
var today = '{$list["date_time"]}';
var curVideo = {$curVideo};
</script>
<style>

.split-video-live .split-right{padding: 25px 25px 35px 0px;}
.split-video-live .file-list-li{width: 160px;height:auto;}
.split-video-live .file-list-li .pic{height:50px;max-width:160px;}
.split-video-live .file-list-li img{max-width:160px;width: 160px;height: 50px;}
</style>
<div class="wrap main-box">
	{if $liveSet}
	<div class="split-tab">
		<span class="local-video current">视频</span>
		<span class="live-video">直播</span>
	</div>
	{/if}


    <div id="file-box" class="split-main clearfix" style="{if $liveSet}top:100px;{/if}">
        <div class="tip-title">
            <a class="icon-a"></a>
            <h3>点击下面的视频或者频道进行拆条</h3>
        </div>
        <div id="split-video-local" class="split-video-box split-video-local">
	       <div class="file-cat common_category">
	            <div class="file-cat-inner menu-inner"></div>
	       </div>
	       <div class="split-right">
	           <div class="top" style="margin-left:23px;">
	               <div class="common-search-area fr" style="float:left;">
	                    <div class="select-area"></div>
	                    <div class="search">
	                         <input type="text" class="key-who" placeholder="添加人"/>
	                         <input type="text" class="key-word" placeholder="标题关键字"/>
	                         <span class="btn"></span>
	                    </div>
	                </div>
	
	                <div class="common-page-link fr"></div>
	           </div>
	           <ul class="file-list common-vod-area clearfix"></ul>
	       </div>
       </div>
       {if $liveSet}
       <div id="split-video-live" class="split-video-box split-video-live">
	       <div class="file-cat common_category">
	            <div class="file-cat-inner menu-inner"></div>
	       </div>
	       <div class="split-right">
	           <ul class="file-list common-vod-area clearfix">
	           
	           </ul>
	         <!--   --> 
	       </div>
	       
       </div>
       {/if}
    </div>
    <div class="vod-split clearfix">
       <div class="tip-title">
            <h3>当前拆条：<span id="title-box"></span></h3>
            <span class="right">此视频已有拆条：<em class="number"></em></span>
            <a class="icon-a"></a>
       </div> 
       <div class="content">
           <div class="current-vod">
                <div id="video-box" class="video" style="padding:0;"><video id="video"></video></div>
           </div>
           <div class="vod-tag" style="float:left;width: 600px;">
           	{template:unit/publish_for_form, 1}
           		<div class="vod-nav">
           			<div class="prevent-event"></div>
           			<ul class="spit-nav clear">
           				<li class="new-add select">新增</li>
           				<li class="vod-spit">已拆条<?php echo '(';?><em class="number"></em><?php echo ')';?></li>
           			</ul>
           		</div>
           		 <div id="video-pian" class="spit-list">
           		 	<div class="vod-spit-start m2o-flex m2o-flex-center">
           		 		<div class="spit-start m2o-flex-one">
           		 			<p class="start img"></p>
           		 			<span class="start-point point">入点</span>
           		 		</div>
           		 		<div class="time-area">
           		 			 <span class="duration"></span>
           		 			 <span class="yulan"></span>
           		 		</div>
           		 		<div class="spit-end m2o-flex-one">
           		 			<p class="end img"></p>
           		 			<span class="end-point point">出点</span>
           		 		</div> 
           		 	</div>
                    <div class="video-info video-pian-info m2o-flex">
                    	<div class="spit-camera-btn">
                        	<a class="camera-btn"></a>
                     	</div>  
                     	<div class="spit-item m2o-flex-one">
                     		<div class="spit-info">
	                            <label>标题:</label><input type="text" name="title" class="vod-title vod-input" placeholder="输入标题" />
	                        </div>
	                       	<div class="spit-info">
	                            <label>描述:</label><textarea class="comment" name="comment" placeholder="拆条描述"></textarea>
	                        </div>
	                        <div class="form-dioption-fabu spit-info">
				                <a class="publish-button overflow" href="javascript:;" _default="发布至" _type="publish" _prev="发布至">发布至：<em>暂未设置</em></a>
				        	</div>
				        	 <div class="form-dioption-keyword spit-info clearfix" style="position:relative;">
				        	 	<label>关键字:</label>
				                <span class="keywords-del"></span>
				                <span class="form-item" _value="添加关键字" id="keywords-box" data-title="提取文章内容与标题为关键字">
				                    <span class="keywords-start">添加关键字</span>
				                    <span class="keywords-add">+</span>
				                </span>
				                <input name="keywords" type="hidden" value="{$keywords}" id="keywords"/>
				            </div>
				        	<div class="form-dioption-sort spit-info"  id="sort-box">
				                <label>分类:</label>
				                <p style="display:inline-block;" class="sort-label" _multi="vod_media_node"> {$currentSort["$sort_id"]}<img class="common-head-drop" src="{$RESOURCE_URL}tuji/drop.png" style="position: relative;left:10px;bottom:2px;" /></p>
								<div class="sort-box-outer"><div class="sort-box-inner"></div></div>
				                <input name="sort_id" type="hidden" value="{$sort_id}" id="sort_id" />
				            </div>
	                        <div class="spit-info">
	                        	<label>&nbsp;</label>
	                        	<span class="common-button-group">
	                            <a class="save blue">保存</a>
	                            <a class="cancel gray">取消</a>
	                            </span>
	                        </div>
                     	</div> 
                    </div>
                    <div class="video-pian-tip"></div>
                </div>
                <div class="vod-list spit-list" style="display:none">
                	<ul id="tiao-box"></ul>
           		</div>
           </div>
       </div>
  </div>
  <div id="ohms-instance" style="z-index: 999999999;position:absolute;display:none;"></div>
</div>
<!-- 全局数据 -->
<script type="text/javascript">
	$.globalsite =  {code} echo $sitesdata['site'] ? json_encode( $sitesdata['site'] ) : '[]';{/code};
	$.globaldefault =  {code} echo $sitesdata['default_site'] ? json_encode( $sitesdata['default_site'] ) : '[]';{/code};
	$.liveSet =  {code} echo $live_configs['live_time_shift_open'] ? $live_configs['live_time_shift_open'] : '[]';{/code};
    $.time =  {code} echo $live_configs['date_time'] ? $live_configs['date_time'] : '[]';{/code};
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
<script type="text/x-jquery-tmpl" id="live-video-tpl">
<div class="modal-live-box">
	<div class="modal-inner">
		<div class="modal-live-title">
			<span>频道：{{= title}}</span>
			<span class="modal-live-close"></span>
		</div>
		<span class="modal-slide-out" title="点击伸开"></span>
		<div class="modal-content  m2o-flex">
			<object id="vodPlayer" type="application/x-shockwave-flash" data="{code}echo RESOURCE_URL{/code}swf/Main.swf?11122713">
				<param name="movie" value="{code}echo RESOURCE_URL{/code}swf/Main.swf?11122713">
				<param name="allowscriptaccess" value="always">
				<param name="allowFullScreen" value="true">
				<param name="wmode" value="transparent">
				<param name="flashvars" value="url={{= vod}}&autoPlay=true&aspect=${aspect}">
  			</object>
			<div class="time-box">
				<span class="modal-slide-up">收起</span>
				<div class="time-box-item">
					<span class="time-box-item-title">开始时间:</span>
					<input type="text" name="start_time" class="time-picker" />
					<div class="quick-time">
						<span class="quick-time-item">一键选取</span>
						<ul class="quick-time-list">
							<li _value="5">5分钟</li>
							<li _value="10">10分钟</li>
							<li _value="15">15分钟</li>
							<li _value="20">20分钟</li>
							<li _value="30">30分钟</li>
						</ul>
					</div>
				</div>
				<div class="time-box-item">
					<span class="time-box-item-title">结束时间:</span>
					<input type="text" name="end_time" class="time-picker" />
				</div>
				<input type="hidden" name="channel_id" value="{{= id}}" />
				<input type="button" name="sub" value="提交" class="save-button modal-live-save">
				<!--<span class="check-spilt">查看拆条</span>-->
				<div class="modal-tips">
					<h3>直播拆条步骤:</h3>
					<p>1.首先选取拆条视频时间段(不能超过当前时间);</p>
					<p>2.对选取的时间段视频进行拆条;</p>
					<h3>友情提醒:</h3>
					<p>1.时间选取均不能超过当前时间;</p>
					<p>2.一键选取时间是当前时间往前减相应时间;</p>
					<p>3.时间间隔不能超过30分钟;</p>
				</div>
			</div>
		</div>
	</div>
</div>
</script>
<script type="text/x-jquery-tmpl" id="file-cat-place-tpl">
    <ul class="file-cat-item">
        <li _fid="${fid}" class="file-cat-title"><a class="title">${title}</a></li>
        <li class="cat-loading"><a class="title">{{html img}}</a></li>
    </ul>
</script>
<script type="text/x-jquery-tmpl" id="file-list-li-tpl">
{{if list.length}}
    {{each list}}
    <li _id="{{= $value.id}}" class="file-list-li" {{if video_src}}data-url="{{= $value.video_src}}"{{/if}}>
        <a class="pic">
             <img src="{{= $value.src}}" />
             {{if $value.starttime}}
              <span class="date">{{= $value.starttime}}</span>
              {{/if}}
             <span class="time">{{= $value.duration}}</span>
        </a>
        <a class="name">{{= $value.title || $value.name}}</a>
		{{if !$value.type}}
        {{if $value.mark_count>0}}<span class="number"><em>{{= $value.mark_count}}</em>拆条</span>{{/if}}
		{{/if}}
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
<script type="text/x-jquery-tmpl" id="split-tpl">
{{if list.length}}
    {{each list}}
    <li _id="{{= $value.id}}">
        <a class="pic"  href="run.php?a=relate_module_show&app_uniq=livmedia&mod_uniq=livmedia&mod_a=form&id={{= $value.id}}&infrm=1" target="formwin">{{if $value.img}}<img src="{{= $value.img}}"/>{{/if}}</a>
         <a class="name"  href="run.php?a=relate_module_show&app_uniq=livmedia&mod_uniq=livmedia&mod_a=form&id={{= $value.id}}&infrm=1" target="formwin">{{= $value.name}}</a>
         <div><span class="time">{{= $value.duration}}</span><span class="zhuan"></span></div>
         {{if !$value.type}}<span class="edit">重拆</span>{{/if}}
    </li>
    {{/each}}
{{else}}
    <li>还没有拆条.</li>
{{/if}}
</script>
{template:foot}