{template:head}
{template:head/nav}
{js:mms_control}

{js:jquery-ui-1.8.16.custom.min}
{js:live/live_form}
{css:common/common_form}
{css:live_form}
{code}/*print_r($formdata);exit();*/{/code}

{code}
	if (!empty($relate_menu))
	{
		foreach ($relate_menu AS $k => $v)
		{
			$relate_mid = $k;
			$menuid = $v;
		}
	}
{/code}

<input type="hidden" id="channel_id" value="{$formdata['id']}" />
<input type="hidden" id="stream_id" value="{$formdata['stream_id']}" />
<input type="hidden" id="chg2_stream_id" value="{$formdata['chg2_stream_id']}" />
<input type="hidden" id="audio_only" value="{$formdata['audio_only']}" />
<input type="hidden" id="down_stream_url" value="{$formdata['down_stream_url'][0]}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<div class="live">

    <div class="option-iframe-back-box">
<!--         <a class="option-iframe-back" href="./run.php?mid=167&a=frame&menuid=187">返回实时播控</a> -->
        <a class="option-iframe-back" href="./run.php?mid={$relate_mid}&a=frame&menuid={$menuid}">返回实时播控</a>
        <span class="live-title">{$formdata['name']}频道实时播控</span>
        <span class="live-back" id="live_back">
        	{if $formdata['chg2_stream_id']}
        	<a onclick="hg_emergency_change({$formdata['id']},{$formdata['stream_id']},'stream','live_back');" class="live-back-a">
        		返回直播
        	</a>
        	{else}
        	<a>正在直播</a>
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
                <div class="play-right play-item" _id="{$formdata['live_stream_output']['stream_id']}" _type="{$formdata['live_stream_output']['chg_type']}">
                    <span class="play-tip">输出监视 PGM</span>
                    <span class="play-title">延时{$formdata['live_delay']}秒</span>
                    <div id="flashOut"></div>
                </div>
            </div>

            <div class="play-bottom play clearfix">
                {code}
                    !$formdata['live_stream_beibo'] && $formdata['live_stream_beibo'] = array();
                    $hasSelected = array();

                    $zhu = array();
                    $zhu['id'] = $formdata['stream_id'];
                    $zhu['out_url'] = $formdata['primary_stream_url'];
                    $zhu['ch_name'] = $formdata['stream_mark'];
                    array_unshift($formdata['live_stream_beibo'], $zhu);
                    $i = 1;

                    $formdata['live_stream_beibo'][] = array();
                    $formdata['live_stream_beibo'][] = array();
                    $formdata['live_stream_beibo'][] = array();
                {/code}


                    {foreach $formdata['live_stream_beibo'] as $k => $v}
                        <div class="play-item-{$i} play-item {if $i > 1}item-drop{/if}" _index="{$i}" _url="{$v['out_url'][0]}" _id="{$v['id']}" _type="stream" _name="{$v['ch_name']}">
                            <div class="item" id="play-item-{$i}"></div>

                            <span class="item-mask"></span>
                            <span class="item-title"><span class="item-number">{$i}</span><span>{$v['ch_name']}</span></span>
                        </div>
                        {code}
                            array_push($hasSelected, $v['id']);
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
        <div class="bei-title"><span class="bei-drop-box">备播信号<span class="bei-drop"></span></span><span class="bei-tip">下面的备播信号可以拖动至上面的<span>2</span><span style="margin:0 3px;">3</span><span>4</span>处备选信号区</span></div>
        <div class="bei-content clearfix">
            {if $formdata['live_stream_info']}
                {code}$i = 1;{/code}
                {foreach $formdata['live_stream_info'] as $k => $v}
                    <div style="{if !($i%8)}margin-right:0;{/if}" class="stream-item {code}if(in_array($v['id'], $hasSelected)){echo 'stream-item-current';}{/code}" id="stream-{$v['id']}" _id="{$v['id']}" _url="{$v['out_url'][0]}" _name="{$v['ch_name']}" _type="stream">{$v['ch_name']}</div>
                    {code}$i++;{/code}
                {/foreach}
            {/if}
        </div>
    </div>

    <div class="live-file live-bei">
        <div class="bei-title"><span class="bei-drop-box">备播文件<span class="bei-drop"></span></span></div>

        <div class="bei-content clearfix">
            <div class="file-all">


                {if $formdata['live_backup_info']}
                    {code}$i = 1;{/code}
                    <div class="each-page">
                    {foreach $formdata['live_backup_info'] as $k => $v}

                        <div class="file-item" _id="{$v['id']}" _url="{$v['file_uri']}" _type="file" _name="{$v['title']}" style="{if !($i%7)}margin-right:0;{/if}">
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
            <span>第<em>1</em>页/共<em>{code}echo ceil($formdata['live_backup_count']['total'] / 7);{/code}</em>页</span>
            <span class="page-next"></span>
        </div>
    </div>

    <textarea id="tpl-file-item" style="display:none;">
    <div class="file-item" _id="{{id}}" _url="{{url}}" _type="file" _name="{{title}}">
        <img src="{{src}}"/>
        <span class="file-qie"></span>
        <span class="file-des">{{title}}</span>
    </div>
    </textarea>

    <div class="live-br"></div>
</div>
{template:foot}
