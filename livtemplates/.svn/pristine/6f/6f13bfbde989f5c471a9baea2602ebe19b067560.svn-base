<li class="common-list-data clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['id']}">
	<div class="common-list-left">
        <div class="common-list-item common-paixu">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title=""  /></a>
        </div>
        <div class="common-list-item share-slt">
                <img src="{if $v['picurl']}{$v['picurl']}{else}{$image_resource}hill.png{/if}"   width="40" height="30"   id="img_{$v['id']}" class="biaoti-img" />
        </div>
    </div>
	<div class="common-list-right">
	  <!--  
        <div class="common-list-item wd60">
			    <a class="btn-box" onclick="hg_showAddShare({$v['id']});"><em class="b2"></em></a>
        </div>
        <div class="common-list-item wd60">
			    <a class="btn-box" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3" ></em></a>
        </div>-->
        <div class="common-list-item wd80 overflow">
                <span id="share_type_{$v['id']}">
								{code} 
								if(!empty($_configs['share_plat'][$v['type']]['name_ch'])) 
								echo $_configs['share_plat'][$v['type']]['name_ch'];
								{/code}
		       </span>
        </div>
        <div class="common-list-item wd60">
                <span id="share_status_{$v['id']}">{if $v['status']==2}未启用{else}<span style="color:#17b202;">启用</span>{/if}</span>
        </div>
        <div class="common-list-item wd150">
			    <span class="common-name"></span>
			   <span class="common-time">{code}echo date('Y-m-d H:i',$v['addtime']){/code}</span>
        </div>
   </div>
   <div class="common-list-i" onclick="hg_show_opration_info({$v['id']});"></div>
   <div class="common-list-biaoti">
	    <div class="common-list-item share-biaoti biaoti-transition">
		         <a href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" class="common-title"><span class="m2o-common-title">{$v['name']}</span></a>
	    </div>
   </div>
</li>