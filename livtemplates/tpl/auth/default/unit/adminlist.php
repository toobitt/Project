<li class="common-list-data clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}">
	<div class="common-list-left">
        <div class="common-list-item admin-paixu">
            <div class="common-list-cell">
                <a class="lb"  name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
            </div>
        </div>
        {code}
        	$avatar = '';
        	if ($v['avatar'])
        	{
        		$avatar = $v['avatar']['host'].$v['avatar']['dir'].'40x36/'.$v['avatar']['filepath'].$v['avatar']['filename'];
        	}else{
        		$avatar = $RESOURCE_URL.'avatar.jpg';
        	}
        {/code}
        <div class="contribute-fengmian common-list-item"><em><img alt="头像" src="{$avatar}" width="41px" height="37px"></em></div>
    </div>
	<div class="common-list-right">
        <div class="common-list-item admin-bj">
            <div class="common-list-cell">
                <a class="btn-box" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1"><em class="b2"></em></a>
            </div>
        </div>
        <div class="common-list-item admin-sc">
            <div class="common-list-cell">
                 <a class="btn-box" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3"></em></a>
            </div>
        </div>
        <div class="common-list-item admin-js">
            <div class="common-list-cell">
              <span id="contribute_sort_{$v['id']}">{$v['name']}</span>
            </div>
        </div>
        <div class="common-list-item admin-bdmb">
            <div class="common-list-cell">
			    <span id="contribute_cardid_{$v['id']}"> {if $v['cardid']}<a href="infocenter.php?a=get_user_mibao&id={$v['id']}">下载密保卡</a>{else}未绑定{/if}</span>
            </div>
        </div>
        <div class="common-list-item admin-tjr">
            <div class="common-list-cell">
                <span>{$v['user_name_add']}</span>
			   <span class="create-time">{$v['create_time']}</span>
            </div>
        </div>
   </div>
   <div class="common-list-biaoti ">
	    <div class="common-list-item admin-biaoti biaoti-transition">
			   <div class="common-list-cell">
			   <span class="common-list-overflow admin-biaoti-overflow">
		          <span id="contribute_title_{$v['id']}" class="m2o-common-title">{$v['user_name']}</span>
               </span>
            </div>  
	    </div>
   </div>
</li> 
