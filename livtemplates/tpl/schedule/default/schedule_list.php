{template:head}
{css:2013/iframe}
{css:common/common_category}
{css:plan_list}
{js:jqueryfn/jquery.tmpl.min}
{js:2013/ajaxload_new}
{js:live/my-rhms}
{js:live/my-ohms}
{js:live/plan_list_plans}
{js:live/plan_list_catlist}
{js:live/plan_list_file}
{js:live/plan_list_stream}
{js:live/plan_list_channel}
{js:live/plan_list_program}
{code}
$schedule_info = $list['schedule_info'];
!$schedule_info && ($schedule_info = array());
$schedule_info = json_encode($schedule_info);
{/code}
<script>
function stringToTime(string, ishms){
    var hms = string;
    var h, m, s;
    if(!ishms){
        hms = hms.split(':');
        h = hms[0];
        m = hms[1];
        s = hms[2];
    }else{
        h = hms.h;
        m = hms.m;
        s = hms.s;
    }
    return (parseInt(h) * 60 * 60 + parseInt(m) * 60 + parseInt(s));
}

function dateDiff(date1, date2){
    var time = function(date){
        return Math.floor((+new Date(date)) / 1000);
    }
    return time(date1) - time(date2);
}
var channelId = {$list['channel_info']['id']};
var today = '{$list["today"]}';
var dates = '{$list["dates"]}';
var stime = '{$list["stime"]}';
var scheduleInfo = {$schedule_info};
var isExpired = {$list['is_expired']};
var isProgram = {$list['is_program']};
var globalStartTime = 0;
if(today == dates){
    globalStartTime = stringToTime(stime) + 5 * 60;
}
var isEdit = false;
if($.isArray(scheduleInfo) && scheduleInfo.length > 0){
    isEdit = true;
}

$(function(){
    var ohmsInstance = $('#ohms-instance').ohms();

    $('#drop-box').plans({
        isExpired : isExpired,
        ohms : ohmsInstance,
        'save-ajax-url' : 'run.php?mid=' + gMid + '&a=edit',
        'schedule-info' :  scheduleInfo,
        sort : function(){
            $('#drop-program').program('hide');
        },
        save : function(){
            $('#drop-program').program('show');
        }
    }).plans('refresh');
    $('#file-box').file({
        'cat-ajax-url' : 'run.php?mid=' + gMid + '&a=get_vod_node&fid={{fid}}',
        'list-ajax-url' : 'run.php?mid=' + gMid + '&a=get_vod_info&page={{pp}}&counts=12&vod_sort_id={{cat}}&title={{title}}&date_search={{date}}'
    });
    $('#stream-box').stream({
        'cat-ajax-url' : 'run.php?mid=' + gMid + '&a=get_channel_node&fid={{fid}}',
        'list-ajax-url' : 'run.php?mid=' + gMid + '&a=get_channel_info&page={{pp}}&counts=12&node_id={{cat}}&title={{title}}&date_search={{date}}',
        'shiyi-ajax-url' : 'run.php?mid=' + gMid + '&a=get_time_shift_info&dates=' + dates + '&stime=' + stime
    });

    $('#drop-program').program({
        dates : dates,
        'channel-id' : channelId,
        'ok-url' : 'run.php?mid=' + gMid + '&a=schedule2program',
        'program-url' : 'run.php?mid=' + gMid + '&a=get_program_info'
    });

    $('.drag-title').click(function(){
        var state = $(this).data('state');
        state = !state;
        $(this).next()[state ? 'hide' : 'show']();
        $(this).data('state', state);
    });

    $('.d-t').on({
        click : function(){
            if($(this).hasClass('on')) return;
            var index = $('.d-t').index(this);
            $(this).addClass('on').siblings().removeClass('on');
            $('.d-b').eq(index).show().siblings().hide();
        }
    }).eq(0).click();

    top != self && $(top).off('scroll.plan').on({
        'scroll.plan' :  function(){
            var scrollTop = $(top).scrollTop();
            var topVal = top.$('#mainwin').offset().top;
            var navVal = $('.nav-box').outerHeight(true);
            var selfTop = 80;
            var box = $('#drag-box');
            if(scrollTop > topVal + navVal + selfTop){
                box.stop().animate({
                    top : scrollTop - (topVal + navVal) + 'px'
                }, 500);
            }else{
                box.stop().css('top', selfTop + 'px');
            }

        }
    });

    window.onload = function(){
        window.focus();
    }

});
</script>


<div id="hidden-nav-option">
    <a class="gray back" href="./run.php?mid={$_INPUT['main_mid']}&amp;a=frame" target="mainwin">返回串联单</a>
</div>
<div id="ohms-instance" style="position:absolute;display:none;"></div>
<div class="wrap main-box">
    <div class="head-box">
    {if $list['is_expired']}
    <span style="color:red;float:right;margin-right:10px;">串联单已经过期，再设置无效</span>
    {else}
        <span class="common-button-group" style="float:right;margin-right:10px;">
	    {if $_configs['App_program']}
	        {if !$list['is_program']}
	        <a class="make-program blue" style="{if !$list['schedule_info']}display:none;{/if}">生成节目单</a>
	        {/if}
	        <a class="watch-program blue" style="{if !$list['is_program']}display:none;{/if}" href="./run.php?a=relate_module_show&app_uniq=program&mod_uniq=program&mod_a=show&mod_main_uniq=channel&channel_id={$list['channel_info']['id']}&infrm=1&dates={$list['dates']}" target="mainwin">查看已生成节目单</a>
	        <a class="make-new-program blue" style="{if !$list['is_program']}display:none;{/if}">重新生成节目单</a>
        {/if}
        <a class="ok-program blue" style="display:none;">提交</a>
        <a class="no-program gray" style="display:none;">取消</a>

        <a class="save blue" style="display:none;">保存</a>
        </span>
    {/if}
    <div class="copy-box common-button-group">
        <a class="copy-program blue">复制</a>
        <div class="copy-info">复制到<input readonly="readonly"/><br/><a class="copy-btn blue">确定</a></div>
    </div>
    {$list['channel_info']['name']}<span class="head-date">{$list['dates']}</span>
    </div>
    <div class="drop-area">
        <div id="drop-box" class="drop-items"></div>
        <div id="drop-time-zhou"></div>
        <div class="drop-wu">请从左边拖动选择</div>
    </div>
    <div id="drag-box" class="d-d" style="position:absolute;top:80px;">
        <div class="d-ts">
            <span class="d-file d-t">文件</span>
            <span class="d-stream d-t">频道</span>
        </div>
        <div class="d-bs">
            <div id="file-box" class="d-b">
                <div class="file-cat common_category">
                    <div class="file-cat-inner menu-inner"></div>
                </div>
                <div class="file-content box-content">
                    <div class="file-condition common-search-area" style="float:left;padding-right:140px;">
                        <div class="select-area">
                        </div>
                        <div class="search" style="width:138px;">
                             <input type="text" class="key-word"/>
                             <span class="btn"></span>
                        </div>
                    </div>
                    <div class="list-outer">
                        <div class="list-inner">
                            <ul class="file-list common-vod-area clearfix"></ul>
                            <div class="common-page-link fr" style="margin-top:15px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="stream-box" class="d-b">
                <div id="pindao-box">
                    <div class="stream-cat common_category">
                        <div class="stream-cat-inner menu-inner"></div>
                    </div>
                    <div class="stream-content box-content">
                        <div class="list-outer">
                            <div class="list-inner">
                                <ul class="stream-list clearfix"></ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="shiyi-box">
                    <div class="shiyi-left">
                    <div class="shiyi-back">
                        <div class="shiyi-back-all">全部频道</div>
                        <div class="shiyi-current"></div>
                    </div>
                    <ul class="shiyi-cat"></ul>
                    </div>
                    <div class="shiyi-content-outer">
                        <div class="shiyi-content"></div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

<div id="drop-program" class="drop-items" style="display:none;position:absolute;z-index:10000;"></div>

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

<!--stream模板-->
<script type="text/x-jquery-tmpl" id="stream-cat-tpl">
    <ul class="stream-cat-item">
        <li class="stream-cat-title" _fid="${fid}"><a class="title">${title}</a></li>
        {{each list}}
        <li class="stream-cat-li" _fid="{{= $value.fid}}" _name="{{= $value.name}}">
            <a class="title">{{= $value.name}}</a>
            {{if $value.child}}
            <a class="stream-cat-child arrow"></a>
            {{/if}}
        </li>
        {{/each}}
    </ul>
</script>
<script type="text/x-jquery-tmpl" id="stream-cat-place-tpl">
    <ul class="stream-cat-item">
        <li class="stream-cat-title" _fid="${fid}"><a class="title">${title}</a></li>
        <li class="cat-loading"><a class="title">{{html img}}</a></li>
    </ul>
</script>
<script type="text/x-jquery-tmpl" id="stream-list-li-tpl">
{{if list}}
    {{each list}}
    <li class="stream-list-li" _id="{{= $value.id}}" _name="{{= $value.name}}">
        <a class="pic">
             <img src="{{= $value.src}}" />
        </a>
        <a class="name">{{= $value.title}}</a>
    </li>
    {{/each}}
{{else}}
    <li class="list-wu">无</li>
{{/if}}
</script>
<script type="text/x-jquery-tmpl" id="stream-list-more-tpl">
    <li class="stream-list-more">更多</li>
</script>





<!--时移模板-->
<script type="text/x-jquery-tmpl" id="shiyi-cat-tpl">
    {{each list}}
    <li class="shiyi-cat-item" _id="{{= $value.title}}">
        {{= $value.title}}
    </li>
    {{/each}}
</script>
<script type="text/x-jquery-tmpl" id="shiyi-list-tpl">
    {{each items}}
        <div class="shiyi-list-item">
        {{each $value}}
            <ul>
            {{each $value}}
                <li class="shiyi-list-li" _id="{{= $value.id}}"><span class="shiyi-time">{{= $value.start}}</span><span class="channel-list-name">{{= $value.theme}}</span></li>
            {{/each}}
            </ul>
        {{/each}}
        </div>
    {{/each}}
</script>



{template:foot}