{template:head}
{template:head/nav}
{js:mms_control}

{js:jquery-ui-1.8.16.custom.min}
{js:live/live_form}
{css:common/common_form}
{css:live_form}

<input type="hidden" id="channel_id" value="{$formdata['id']}" />
<input type="hidden" id="stream_id" value="{$formdata['stream_id']}" />
<input type="hidden" id="audio_only" value="{$formdata['audio_only']}" />
<input type="hidden" id="down_stream_url" value="{$formdata['down_stream_url'][0]}" />

<div class="live">

    <div class="option-iframe-back-box">
        <span class="option-iframe-back">返回实时播控</span>
        <span class="live-title">实时播控</span>
    </div>

    <div class="live-play clearfix">
        <div class="play-vod">
            <div class="play clearfix">
                <div class="play-left play-item">
                    <span class="play-tip">信号预监 PVW</span>
                    <span class="play-title"></span>
                    <div id="flashYujian"></div>
                </div>
                <div class="play-right play-item">
                    <span class="play-tip">输出预监 PGM</span>
                    <span class="play-title">延时30秒</span>
                    <div id="flashContent"></div>
                </div>
            </div>

            <div class="play-bottom play clearfix">

                {if $formdata['live_stream_info']}
                    {code}
                        $i = 1;
                        $hasSelected = array();
                    {/code}
                    {foreach $formdata['live_stream_info'] as $k => $v}

                        <div class="play-item-{$i} play-item {if $i > 1}item-drop{/if}" _url="{$v['out_url'][0]}" _id="{$v['id']}" _type="stream" _name="{$v['s_name']}">
                            <div class="item" id="play-item-{$i}"></div>

                            <span class="item-mask"></span>
                            <span class="item-title"><span class="item-number">{$i}</span><span>{$v['s_name']}</span></span>
                        </div>
                        {code}
                            array_push($hasSelected, $v['id']);
                            $i++; if($i > 4) break;
                        {/code}
                    {/foreach}
                {/if}
            </div>
        </div>
        <div class="play-button">
            <span class="qiebo-tip"></span>

            <div class="qiebo">
                <div class="qiebo-button"></div>
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
        <div class="bei-title">备播信号<span class="bei-drop"></span></div>
        <div class="bei-content clearfix">
            {if $formdata['live_stream_info']}
                {foreach $formdata['live_stream_info'] as $k => $v}
                    <div class="stream-item {code}if(in_array($v['id'], $hasSelected)){echo 'stream-item-current';}{/code}" id="stream-{$v['id']}" _id="{$v['id']}" _url="{$v['out_url'][0]}" _name="{$v['s_name']}">{$v['s_name']}</div>
                {/foreach}
            {/if}
        </div>
    </div>

    <div class="live-file live-bei">
        <div class="bei-title">备播文件<span class="bei-drop"></span></div>

        <div class="bei-content clearfix">
            <div class="file-all">


                {if $formdata['live_backup_info']}
                    {code}
                        $j = 0;
                        $total = count($formdata['live_backup_info']);
                        $totalPage = ceil( $total / 7 );
                    {/code}
                    <div class="each-page">
                    {foreach $formdata['live_backup_info'] as $k => $v}

                        <div class="file-item" _id="{$v['id']}" _url="{$v['beibo_file_url']}" _type="file" _name="{$v['title']}">
                            <img src="{$v['img']}"/>
                            <span class="file-qie"></span>
                            <span class="file-des">{$v['title']}</span>
                        </div>

                        {code}
                        $j++;
                        if(!$j % 7){
                        {/code}
                            </div>
                            <div class="each-page">
                        {code}
                        }
                        {/code}
                    {/foreach}
                    </div>
                {/if}
            </div>
        </div>

        <div class="file-page">
            <span class="page-prev"></span>
            <span>第<em>1</em>页/共<em>{$totalPage}</em>页</span>
            <span class="page-next"></span>
        </div>
    </div>

    <textarea id="tpl-file-item" style="display:none;">
    <div class="file-item"><img src="{{src}}"/></div>
    </textarea>

    <div class="live-br"></div>
</div>
{template:foot}
