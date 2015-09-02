{template:head}

{css:2013/iframe}
{css:common/common_category}
{css:live_form}
{js:jqueryfn/jquery.tmpl.min}
{js:video/video_file}
{js:live_control/live_form}
{js:live_control/live_control}
{js:live_control/live_page}

{code}
/*hg_pre($formdata);*/
{/code}

{if is_array($formdata)}
	{foreach $formdata AS $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}



<input type="hidden" id="channel_id" value="{$id}" />
<input type="hidden" id="stream_id" value="{$stream_id}" />
<input type="hidden" id="server_id" value="{$server_id}" />
<input type="hidden" id="change_id" value="{$change_id}" />
<input type="hidden" id="change_type" value="{$change_type}" />
<input type="hidden" id="is_audio" value="{$is_audio}" />
<input type="hidden" id="output_url_rtmp" value="{$channel_stream[0]['output_url_rtmp']}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />

<div class="wrap clear">

<div class="live">

    <div class="option-iframe-back-box">
        <a class="option-iframe-back" href="./run.php?mid=354&a=frame&menuid=385" style="display:none;">返回实时播控</a>
        <span class="live-title">{$name}频道实时播控</span>
        <span class="live-back" id="notify">
        	{if $change_id}
        	<a onclick="hg_change({$id},'0','stream','1','0');" class="live-back-a" style="color:#fff;">
        		返回直播
        	</a>
        	{else}
        	<a style="color:#fff;">正在直播</a>
        	{/if}
        </span>
    </div>

    <div class="live-play clearfix">
        <div class="play-vod">
            <div class="play clearfix">
                <div class="play-left play-item">
                    <span class="play-tip">信号预监 PVW</span>
                    <span class="play-title"></span>
                    <div id="flashContent"></div>
                </div>
                {code}
                	$change_id = $change_id ? $change_id : $id;
                {/code}
                <div class="play-right play-item" _id="{$change_id}" _streamid="{$stream_id}" _type="{$change_type}">
                    <span class="play-tip">输出监视 PGM</span>
                    <span class="play-title">延时{$delay}秒</span>
                    <div id="flashOut"></div>
                </div>
            </div>

            <div class="play-bottom play clearfix">
                {code}
                    !$stream_beibo && $stream_beibo = array();
                    $hasSelected = array();

                    array_unshift($stream_beibo, $stream_main);
                    $i = 1;

                    $stream_beibo[] = array();
                    $stream_beibo[] = array();
                    $stream_beibo[] = array();
                {/code}

                    {code}//print_r($stream_beibo);{/code}
                    {foreach $stream_beibo AS $k => $v}
                        <div class="play-item-{$i} play-item {if $i > 1}item-drop{/if}" _index="{$i}" _url="{$v['input_url']}" _id="{$v['change_id']}" _streamid="{$v['id']}" _type="stream" _name="{$v['change_name']}">
                            <div class="item" id="play-item-{$i}"></div>

                            <span class="item-mask"></span>
                            <span class="item-title"><span class="item-number">{$i}</span><span>{$v['change_name']}</span></span>
                        </div>
                        {code}
                            array_push($hasSelected, $v['change_id']);
                            $i++; if($i > 4) break;
                        {/code}
                    {/foreach}

            </div>
        </div>
        <div class="play-button">
            <span class="qiebo-tip"></span>

            <div class="qiebo">
                <div class="qiebo-button"><span>切播</span></div>
            </div>

            <div class="kuaisu-button">
                <div class="button-item">1</div>
                <div class="button-item">2</div>
                <div class="button-item">3</div>
                <div class="button-item">4</div>
            </div>
        </div>
    </div>

    <div class="live-stream live-bei">
        <div class="bei-title"><span class="bei-drop-box">备播信号<span class="bei-drop"></span></span><span class="bei-tip"><!-- 下面的备播信号可以拖动至上面的<span>2</span><span style="margin:0 3px;">3</span><span>4</span>处备选信号区 --></span></div>
        <div class="bei-content clearfix">
            {if !empty($channel_info)}
                {code}$i = 1;{/code}
                {foreach $channel_info AS $k => $v}
                    <div style="{if !($i%8)}margin-right:0;{/if}" class="stream-item {code}if(in_array($v['id'], $hasSelected)){echo 'stream-item-current';}{/code}" id="stream-{$v['id']}" _id="{$v['id']}" _url="{$v['channel_stream'][0]['output_url_rtmp']}" _name="{$v['name']}" _type="stream">{$v['name']}</div>
                    {code}$i++;{/code}
                {/foreach}
            {/if}
        </div>
    </div>

    <!-- 先隐藏
    <div class="live-file live-bei" style="display:none;">
        <div class="bei-title"><span class="bei-drop-box">备播文件<span class="bei-drop"></span></span></div>

        <div class="bei-content clearfix">
            <div class="file-all">


                {if !empty($backup_info['info'])}
                    {code}$i = 1;{/code}
                    <div class="each-page">
                    {foreach $backup_info['info'] AS $k => $v}

                        <div class="file-item" _id="{$v['id']}" _url="{$v['vodurl']}" _type="file" _name="{$v['title']}" style="{if !($i%7)}margin-right:0;{/if}">
                            <img src="{$v['img']}"/>
                            <span class="file-qie"><span>发送到预监</span></span>
                            <span class="file-des" title="{$v['title']}">{$v['title']}</span>
                        </div>
                        {code}$i++;{/code}
                    {/foreach}
                    </div>
                {/if}
            </div>
        </div>

        <div class="file-page">
            <span class="page-prev"></span>
            <span>第<em>1</em>页/共<em>{code}echo ceil($backup_info['total'] / 7);{/code}</em>页</span>
            <span class="page-next"></span>
        </div>
    </div>

    <textarea id="tpl-file-item" style="display:none;">
    <div class="file-item" _id="{{id}}" _url="{{url}}" _type="file" _name="{{title}}">
        <img src="{{src}}"/>
        <span class="file-qie"><span>发送到预监</span></span>
        <span class="file-des">{{title}}</span>
    </div>
    </textarea>

    -->

    <div class="live-br"></div>
</div>

<div id="file-box">
    
    <div class="file-content box-content">
    <!-- title -->
	<div class="file-content-title">
		<h3>备播文件</h3>
		<div class="file-content-more">
			<span class="more">更多</span>
			<div class="file-content-more-area">
				<div class="file-condition common-search-area">
				    <div class="search">
						<input type="text" class="key-word" value=""/>
						<span class="btn"></span>
		            </div>
					<div class="select-area"></div>
		         </div>
			      <div class="file-cat common_category">
			           <div class="file-cat-inner menu-inner"></div>
			      </div>
		        
			</div>
		</div>
	</div>
      <!-- title -->  
        <div class="list-outer">
            <div class="list-inner">
                <ul class="file-list common-vod-area clearfix"></ul>
                <div class="common-page-link fr"></div>
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


<div id="send-yujian" class="file-qie"><span>发送到预监</span></div>

</div>

<script type="text/javascript">
	function hg_update_start_time()
	{
		var channel_id = "{$id}";
		if (!channel_id)
		{
			return;
		}
		var url = "./run.php?mid=" + gMid + "&a=update_start_time&channel_id=" + channel_id;
		hg_request_to(url, '', 'get', '', 1);
		setTimeout("hg_update_start_time()", 10000);
	}
	//setTimeout("hg_update_start_time()", 10000);
</script>


{template:foot}
