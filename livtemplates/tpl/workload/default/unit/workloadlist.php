<li class="common-list-data clear" orderid="{$v['order_id']}"  order_id="{$v['order_id']}" _id="{$v['id']}" id="r_{$v['id']}" class="h"   name="{$v['id']}">
	<div class="common-list-left">
        <div class="common-list-item common-paixu">
            <div class="common-list-cell">
                <a class="lb"  name="alist[]" ><input type="checkbox"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  name="infolist[]"></a>
            </div>
        </div>
    </div>
    <div class="common-list-right">
        <div class="common-list-item">
            <div class="common-list-cell">
			    <span>{$v['count']}</span>
            </div>
        </div>
 		<div class="common-list-item">
            <div class="common-list-cell">
			    <span>{$v['statued']}</span>
            </div>
        </div>  
 		<div class="common-list-item">
            <div class="common-list-cell">
			    <span>{$v['unstatued']}</span>
            </div>
        </div>  
 		<div class="common-list-item">
            <div class="common-list-cell">
			    <span>{$v['publish']}</span>
            </div>
        </div>  
 		<div class="common-list-item">
            <div class="common-list-cell">
			    <span>{$v['published']}</span>
            </div>
        </div>  
   </div>
   <div class="common-list-biaoti">
	    <div class="common-list-item vote-biaoti biaoti-transition">
			   <div class="common-list-cell">
			   <a class="common-title" href="./run.php?mid={$_INPUT['mid']}&a=detail&user_id={$v['user_id']}&infrm=1{$param}" target="formwin">
			   	{if $v['avatar']}
			   		{code}$avatars = $v['avatar']['host'].$v['avatar']['dir'].$v['avatar']['filepath'].$v['avatar']['filename'];{/code}
			   	{else}
			   		{code}$avatars = $RESOURCE_URL.'avatar.jpg';{/code}
			   	{/if}
			   	<img class="biaoti-img" src="{$avatars}">
                <span style="max-width: 200px;" class="common-list-overflow vote-biaoti-overflow m2o-common-title" title="{$v['title']}" _onclick="hg_getQestionOption({$v['id']});">{$v['user_name']}</span>
               </a>
            </div>  
	    </div>
   </div>
   <div class="common-list-i" onclick="hg_show_opration_info({$v['id']});"></div>
</li>
