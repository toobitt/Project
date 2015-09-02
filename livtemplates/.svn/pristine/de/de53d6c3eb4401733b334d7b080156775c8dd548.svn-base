<li class="common-list-data clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['id']}">
	<div class="common-list-left">
        <div class="common-list-item common-paixu">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title=""  /></a>
            </div>
        </div>
        <div class="common-list-item share-slt">
            <div class="common-list-cell">
                <img src="{if $v['picurl']}{$v['picurl']}{else}{$image_resource}hill.png{/if}"   width="40" height="30"   id="img_{$v['id']}"  />
            </div>
        </div>
    </div>
	<div class="common-list-right">
        <div class="common-list-item share-bj">
            <div class="common-list-cell">
			    <a class="btn-box" onclick="hg_showAddShare({$v['id']});"><em class="b2"></em></a>
            </div>
        </div>
        <div class="common-list-item share-sc">
            <div class="common-list-cell">
			    <a class="btn-box" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3" ></em></a>
            </div>
        </div>
        <div class="common-list-item share-fl">
            <div class="common-list-cell">
                <span id="share_type_{$v['id']}">
								{code} 
								if(!empty($_configs['share_plat'][$v['type']]['name_ch'])) 
								echo $_configs['share_plat'][$v['type']]['name_ch'];
								{/code}
		       </span>
            </div>
        </div>
        <div class="common-list-item share-zt">
            <div class="common-list-cell">
                <span id="share_status_{$v['id']}">{if $v['status']==2}未启用{else}启用{/if}</span>
            </div>
        </div>
        <div class="common-list-item share-tjsj">
            <div class="common-list-cell">
			    <span>{code}echo date('H:i:s',$v['addtime']){/code}</span>
			   <span class="create-time">{code}echo date('Y-m-d',$v['addtime']){/code}</span>
            </div>
        </div>
   </div>
   <div class="common-list-biaoti">
	    <div class="common-list-item share-biaoti biaoti-transition">
			   <div class="common-list-cell">
		         <span onclick="hg_show_opration_info({$v['id']})"  id="share_title_{$v['id']}">{$v['name']}</a></span>
            </div>  
	    </div>
   </div>
</li>