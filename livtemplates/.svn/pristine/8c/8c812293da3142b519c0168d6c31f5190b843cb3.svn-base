<li class="common-list-data clear" _id="{$v['id']}" id="r_{$v['id']}" name="{$v['id']}" orderid="{$v['order_id']}" cname="{$v['cid']}" corderid="{$v['order_id']}">
    <div class="common-list-left">
        <div class="vod-paixu common-list-item">
            <div class="common-list-cell">
                <a class="lb" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
            </div>
        </div>
        <div class="vod-fengmian common-list-item">
            <div class="common-list-cell">
                <img src="{$v['img']}" width="40" height="30" onclick="hg_get_img({$v['id']});" id="img_{$v['id']}" title="点击(显示/关闭)截图 " />
            </div>
        </div>
    </div>
    <div class="common-list-right">
        <div class="vod-fabu common-list-item">
            <div class="common-list-cell">
                {code}
                $step = '';
                {/code}
                {if $v['pubinfo']}
                    {foreach $v['pubinfo'] as $vp}
                        {if $vp}
                            {foreach $vp as $vvp}
                            <a class="common-list-pub overflow">{$step}{$vvp}</a>
                            {code}
                            $step = ',';
                            {/code}
                            {/foreach}
                        {/if}
                    {/foreach}
                {/if}
            </div>
        </div>
        <div class="vod-maliu common-list-item">
            <div class="common-list-cell">
                <span style="background:{$v['bitrate_color']}"  id="bitrate_{$v['id']}">{$v['bitrate']}</span>
            </div>
        </div>
        <div class="vod-fenlei common-list-item">
            <div class="common-list-cell"><div class="overflow"><span style="color:{$v['vod_sort_color']}" id="sortname_{$v['id']}">{$v['vod_sort_id']}</span></div></div>
        </div>
        <div class="vod-zhuangtai common-list-item">
            <div class="common-list-cell">
                <span id="text_{$v['id']}" class="zt_a">{$v['status']}</span>
                <span id="tool_{$v['id']}" style="display:none;" class="zt_b"  title="" >
                    <span class="jd" id="status_{$v['id']}" style="width:0px;" ></span>
                </span>
            </div>
        </div>
        <div class="vod-ren common-list-item">
            <div class="common-list-cell">
                <span id="hg_t_{$v['id']}" class="hg_t_time" style="display:none"></span>
                <span class="vod-name">{$v['addperson']}</span>
                <span class="vod-time">{$v['create_time']}</span>
            </div>
        </div>
    </div>
    <div class="common-list-i" onclick="hg_show_opration_info({$v['id']});"></div>
    <div class="vod-biaoti">
        <div class="common-list-cell biaoti-transition">
                <span class="c_a">
                    {if $v['collects']}
                        <span class="jh"><em id="img_jh_{$v['id']}"  onclick="hg_get_collect_info({$v['id']},{$_INPUT['mid']});"    onmouseover="hg_fabu_jh({$v[id]})"  onmouseout="hg_back_fabu_jh({$v[id]})" ></em></span>
                    {/if}
                    {if $v['colname']}
                        {$v['colname']}
                    {else}
                        {if $v['pubinfo'][1]}
                             <span class="lm"><em class="{if $v['status_display'] == 2}{else}b{/if}"  id="img_lm_{$v['id']}"    onmouseover="hg_fabu({$v[id]})"  onmouseout="hg_back_fabu({$v[id]})"></em></span>
                        {/if}
                        {if $v['pubinfo'][2]}
                            <span class="sj"><em class="{if $v['status_display'] == 2}{else}b{/if}"  id="img_sj_{$v['id']}"    onmouseover="hg_fabu_phone({$v[id]})"  onmouseout="hg_back_fabu_phone({$v[id]})"></em></span>
                        {/if}
                    {/if}
                </span>
                <a id="t_{$v['id']}" href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" class="option-iframe">
                <span class="overflow" style="display:inline-block;max-width:450px;">{$v['title']}</span>
                {if $v['starttime']}
                <span class="vod-date">{$v['starttime']}</span>
                {/if}
                <span class="vod-duration" id="duration_{$v['id']}">{$v['duration']}</span>
                </a>
        </div>
    </div>
    <div class="content_more clear" id="content_{$v['id']}"  style="display:none;">
         <div id="show_list_{$v['id']}" class="pic_list_r">
             <div class="img_box"></div>
             <div class="img_box"></div>
             <div class="img_box"></div>
             <div class="img_box"></div>
             <div class="img_box"></div>
             <div class="img_box"></div>
             <div class="img_box"></div>
             <div class="img_box"></div>
             <div class="img_box"></div>
        </div>
         <div id="add_img_content_{$v['id']}"   class="add_img_content">
           <div id="add_from_compueter_{$v['id']}"></div>
         </div>
    </div>
</li>