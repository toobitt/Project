{template:head}
{css:plan_list}
{js:jqueryfn/jquery.tmpl.min}

{js:live/my-rhms}
{js:live/my-ohms}
{js:live/plan_list_plans}
{js:live/plan_list_file}
{js:live/plan_list_stream}
{js:live/plan_list_channel}

<script>
$(function(){
return;
    var ohmsInstance = $('#ohms-instance').ohms();

    $('#drop-box').plans({
        ohms : ohmsInstance
    });
    $('#file-box').file();
    $('#stream-box').stream();
    /*$('#channel-box').channel({
        date : currentDate
    });*/

    $('.drag-title').click(function(){
        var state = $(this).data('state');
        state = !state;
        $(this).next()[state ? 'hide' : 'show']();
        $(this).data('state', state);
    });


});
</script>

<!--
<div class="bg-box" id="plan-box">
    {for $i = 0; $i < 24; $i++}
        <div class="bg-time bg-time-{$i}" value="{$i}">{$i}</div>
    {/for}
</div>
-->

<!--
<div id="ohms-instance" style="position:absolute;display:none;"></div>

<div class="drop-area">
    <div class="drop-start-time-bg"></div>
    <div class="drop-end-time-bg"></div>
    <div class="drop-hms-bg"></div>
    <div id="drop-box"></div>
</div>

<div id="drag-box">
    <div class="drag-each">
        <div class="drag-title">文件</div>
        <div id="file-box">
            <div class="file-cat">
                <div class="file-cat-inner"></div>
            </div>
            <div class="file-content">
                <div class="file-condition"></div>
                <div class="file-list"></div>
            </div>
        </div>
    </div>
    <div class="drag-each">
        <div class="drag-title">信号</div>
        <div id="stream-box" class="clearfix"></div>
    </div>
    <div class="drag-each">
        <div class="drag-title">时移</div>
        <div id="channel-box" class="clearfix"></div>
    </div>
</div>


<script type="text/x-jquery-tmpl" id="file-tpl">
    <div class="file-cat">
        <div class="file-cat-inner"></div>
    </div>
    <div class="file-content">
        <div class="file-condition"></div>
        <div class="file-list"></div>
    </div>
</script>
<script type="text/x-jquery-tmpl" id="file-cat-tpl">
    <div class="file-cat-item">
        <div class="file-cat-title" _fid="${fid}">${title}</div>
        <ul>
            {{each list}}
            <li class="file-cat-li" _fid="{{= $value.fid}}" _name="{{= $value.name}}">
                {{if $value.child}}
                <span class="file-cat-child">>></span>
                {{/if}}
                {{= $value.name}}
            </li>
            {{/each}}
        </ul>
    </div>
</script>
<script type="text/x-jquery-tmpl" id="file-cat-place-tpl">
    <div class="file-cat-item">
        <div class="file-cat-title" _fid="${fid}">${title}</div>
        <div class="file-cat-loading">{{html img}}</div>
    </div>
</script>
<script type="text/x-jquery-tmpl" id="file-list-li-tpl">
    {{each list}}
    <div class="file-list-li" _id="{{= $value.id}}">
        <img src="{{= $value.src}}" class="file-list-img"/>
        <div class="file-list-title">{{= $value.title}}</div>
    </div>
    {{/each}}
</script>
<script type="text/x-jquery-tmpl" id="file-list-more-tpl">
    <div class="file-list-more">更多</div>
</script>


<script type="text/x-jquery-tmpl" id="stream-tpl">
    {{each list}}
    <div class="stream-item" _id="{{= $value.id}}">
        <span class="stream-name">{{= $value.ch_name}}</span>
        <span class="stream-status">{{if $value.s_status}}{{else}}未启动{{/if}}</span>
    </div>
    {{/each}}
</script>


<script type="text/x-jquery-tmpl" id="channel-cat-tpl">
    {{each list}}
    <div class="channel-cat-item" _id="{{= $value.id}}">
        <span class="channel-cat-name">{{= $value.name}}</span>
    </div>
    {{/each}}
</script>
<script type="text/x-jquery-tmpl" id="channel-list-tpl">
    <div class="channel-list-each">
    <div class="channel-list-title">${date}</div>
    <div class="channel-list-content clearfix">
        {{each items}}
        <div class="channel-list-item {{if $value.display>0}}channel-list-item-can{{/if}}" _id="{{= $value.id}}"><span class="channel-list-time">{{= $value.start}}</span><span class="channel-list-name">{{= $value.theme}}</span></div>
        {{/each}}
    </div>
    </div>
</script>
-->


{template:foot}