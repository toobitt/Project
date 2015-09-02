<li class="common-list-data clear"  id="r_{$v['id']}"  name="{$v['id']}"  orderid="{$v['order_id']}"  cname="{$v['cid']}"  corderid="{$v['order_id']}">
	<div class="common-list-left">
        <div class="common-list-item affix-paixu">
            <div class="common-list-cell">
                <a class="lb" name="alist[]"><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  />
            </div>
        </div>
        <div class="common-list-item affix-slt">
            <div class="common-list-cell">
                {if $v['mark']}
				<a><img  src="{$v['url']}" width="40" height="30" title="点击(显示/关闭)缩略图尺寸列表" onclick="check_menu({$v['id']});"/></a>
				{else}
				{/if}
            </div>
        </div>
    </div>
	<div class="common-list-right">
        <div class="common-list-item affix-bj">
            <div class="common-list-cell">
                <a class="btn-box" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1"><em class="b2"></em></a>
            </div>
        </div>
        <div class="common-list-item affix-sc">
            <div class="common-list-cell">
                 <a class="btn-box" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3" ></em></a>
            </div>
        </div>
        <div class="common-list-item affix-ssyy">
            <div class="common-list-cell">
              <span id="contribute_sort_{$v['id']}">{$v['bundle_id']}</span>
            </div>
        </div>
        <div class="common-list-item affix-wjdx">
            <div class="common-list-cell">
			    <span id="contribute_cardid_{$v['id']}">{$v['filesize']}</span>
            </div>
        </div>
        <div class="common-list-item affix-scip">
            <div class="common-list-cell">
                <span id="contribute_client_{$v['id']}">{$v['ip']}</span>
            </div>
        </div>
        <div class="common-list-item affix-scr">
            <div class="common-list-cell">
                <span>{$v['user_name']}</span>
			   <span class="create-time">{$v['create_time']}</span>
            </div>
        </div>
   </div>
   <div class="common-list-biaoti ">
	    <div class="common-list-item affix-biaoti biaoti-transition">
		   <div class="common-list-cell">
			   <span class="common-list-overflow affix-biaoti-overflow m2o-common-title">
		          {if $v['mark']}{$v['filename']}{else}{$v['code']}{/if}
               </span>
           </div>  
	    </div>
   </div>
   {if $v['mark']}
		 <div class="content_more clear" id="content_{$v['id']}"  style="display:none;">
	         <div id="show_list_{$v['id']}" class="pic_list_r">
				  {foreach $v['thumb'] as $tk => $tv}
					<div class="material_list" style="position:relative;" id="thumb_{$v['id']}_{$tk}">
	                    <div style="background:#000000;color:#FFFFFF;width:10px;line-height:10px;height:10px;opacity:0.7;filter:alpha(opacity=70);position:absolute; top:0; right:0;cursor:pointer;padding:0 0 3px 5px;display:block;font-weight:bold;" title="删除该尺寸缩略图" onclick="hg_delete_thumb_size('{$v['ori_path']}','{$v['thumb_size'][$tk]}',{$v['id']});">x</div>
						<a href="{$v['thumb_url'][$tk]}" target="_blank"><img src="{$tv}" width="100" height="75"/></a>
						<span style="color:#FFFFFF;font-weight:bold;display:block;position:absolute;top:30px;left:20px;font-size:18px;">{$v['thumb_size'][$tk]}</span>
		            </div>
				 {/foreach}
			</div>
	    </div>
{/if}
</li> 
                