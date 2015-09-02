<li class="common-list-data clear" _id="{$v['id']}" id="r_{$v['id']}" name="{$v['id']}" video_order_id="{$v['video_order_id']}" cname="{$v['cid']}" corderid="{$v['order_id']}">
    <div class="common-list-left">
        <div class="common-list-item paixu">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
            </div>
        </div>
        <div class="vod-fengmian common-list-item wd60">
                <img _src="{$v['img_info']['host']}{$v['img_info']['dir']}{$v['img_info']['filepath']}{$v['img_info']['filename']}" width="40" height="30" id="img_{$v['id']}" title="点击(显示/关闭)截图 " />
        </div>
    </div>
    <div class="common-list-right">
        <div class="vod-fabu common-list-item common-list-pub-overflow">
            	<div class="common-list-pub-overflow">
                {code}
                $step = '';
                {/code}
                {if $v['column_id']}           	
                    {foreach $v['column_id'] as $kk => $vv}
					    	{if $v['column_url'][$kk]}
					    		{if is_numeric($v['column_url'][$kk])}
					    			<span class="common-list-pub">{$step}{$vv}</span>
					    		{else}
					    		    <span class="common-list-pub">{$step}{$vv}</span>
					    		{/if}												    	
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
        <div class="vod-maliu common-list-item wd70">
                <span style="background:{$v['bitrate_color']}"  id="bitrate_{$v['id']}">{$v['bitrate']}</span>
        </div>
        <div class="vod-fenlei common-list-item wd80">
            <div class="overflow"><span style="color:{$v['vod_sort_color']}" id="sortname_{$v['id']}">{$v['vod_sort_id']}</span></div>
        </div>
        {template:list/list_weight,vod-quanzhong,$v['weight']}
        <div class="vod-zhuangtai common-list-item wd60" >
               <span class="export-file export-file-btn {if $v['is_export']==1} is_export {else} un_export {/if}" _type="0">{if $v['is_export']==1} 已导出 {else} 导出 {/if}</span>
        </div>
        <div class="vod-ren common-list-item wd100">
        		
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
    <div class="common-list-i" onclick="hg_show_opration_info({$v['id']});"></div>
    <div class="common-list-biaoti" href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
        <div class="common-list-item biaoti-transition">
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
                {code}$each_title = $v['title'] ? $v['title'] : '无标题';{/code}
                <span class="common-list-overflow max-wd fz14 m2o-common-title" style="display:inline-block;">{$each_title}</span>
                {if $v['starttime']}
                <span class="vod-date">{$v['starttime']}</span>
                {/if}
                <span class="vod-duration" id="duration_{$v['id']}">{$v['duration']}</span>
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