<li class="common-list-data clear" _id="{$v['id']}" id="r_{$v['id']}" name="{$v['id']}" orderid="{$v['video_order_id']}" cname="{$v['cid']}" corderid="{$v['order_id']}">
    <div class="common-list-left">
        <div class="vod-paixu common-list-item">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
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
            	<div class="common-list-pub-overflow">
                {code}
                $step = '';
                {/code}
                {if $v['pub']}           	
                    {foreach $v['pub'] as $kk => $vv}
					    	{if $v['pub_url'][$kk]}
								<a href="{$v['pub_url'][$kk]}" target="_blank"><span class="common-list-pub">{$step}{$vv}</span></a>				    	
					    	{else}
					    		<span class="common-list-pre-pub">{$step}{$vv}</span>
					    	{/if}
                           {code}
                          	 $step = ' ';
                           {/code}
                    {/foreach}
                {/if}
                </div>
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
        <div class="vod-quanzhong common-list-item">
            <div class="common-list-cell">
			 	<div class="common-quanzhong-box">
					<div class="common-quanzhong-box{$v['weight']}" _level="{$v['weight']}">
						<div class="common-quanzhong">{$levelLabel[$v['weight']]}</div>
						<div class="common-quanzhong-option">
						    <div class="common-quanzhong-down"></div>
							<div class="common-quanzhong-up"></div>
						</div>
					</div>
				</div>
            </div>
        </div>
        <div class="vod-zhuangtai common-list-item" onmouseover="hg_control_status('#hg_t_{$v[id]}',1);" onmouseout="hg_control_status('#hg_t_{$v[id]}',0);">
            <div class="common-list-cell">
                <span id="text_{$v['id']}" class="zt_a">{$v['status']}</span>
                <span id="tool_{$v['id']}" style="display:{if $v['status_display'] == 4 }block;{else}none;{/if}" class="zt_b"  title="" >
                    <span class="jd" id="status_{$v['id']}" style="width:0px;" ></span>
                </span>
            </div>
        </div>
        <div class="vod-ren common-list-item">
            <div class="common-list-cell">
                <span id="hg_t_{$v['id']}" class="hg_t_time" style="display:none;background:#EEEFF1;height:38px;" onmouseover="hg_control_status(this,1);" onmouseout="hg_control_status(this,0);">
                	{if $v['status_display'] == -1 && $v['vod_leixing'] != 4}
                	<a class="button_6"  href="run.php?mid={$_INPUT['mid']}&id={$v['id']}&a=retranscode" onclick="return hg_ajax_post(this,'重新转码',0);"  style='margin-left:24px;margin-top:7px;' >重新转码</a>
                	{else if $v['status_display'] == 0}
                	<input type='button' value='暂停' class='button_6' style='margin-left:24px;margin-top:7px;'  onclick="hg_controlTranscodeTask({$v['id']},1);" />
                	{else if $v['status_display'] == 4}
                	<input type='button' value='恢复' class='button_6' style='margin-left:24px;margin-top:7px;'  onclick="hg_controlTranscodeTask({$v['id']},0);"  />
                	{else if $v['vod_leixing'] != 4}
                	<!--
                	<a class="button_6"  href="run.php?mid={$_INPUT['mid']}&id={$v['id']}&a=multi_bitrate" onclick="return hg_ajax_post(this,'新增多码流',0);"  style='margin-left:24px;margin-top:7px;' >新增多码流</a>
                	-->
                	{/if}
                </span>
                <span class="vod-name">{$v['addperson']}</span>
                <span class="vod-time">{$v['create_time']}</span>
            </div>
        </div>
    </div>
    <div class="common-list-i" onclick="hg_show_opration_info({$v['id']});"></div>
    <div class="vod-biaoti option-iframe" href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
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
                <a id="t_{$v['id']}" href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" onclick="javascript:void(0)">
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
{if $v['childs']}
	{foreach $v['childs'] as $c}
		{template:unit/vod_publishlist}
	{/foreach}
{/if}